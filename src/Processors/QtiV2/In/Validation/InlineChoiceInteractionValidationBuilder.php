<?php

namespace Learnosity\Processors\QtiV2\In\Validation;

use Learnosity\Entities\QuestionTypes\clozedropdown_validation;
use Learnosity\Entities\QuestionTypes\clozedropdown_validation_alt_responses_item;
use Learnosity\Entities\QuestionTypes\clozedropdown_validation_valid_response;
use Learnosity\Exceptions\MappingException;
use Learnosity\Processors\Learnosity\In\ValidationBuilder;
use Learnosity\Processors\QtiV2\In\ResponseProcessingTemplate;
use Learnosity\Utils\ArrayUtil;
use qtism\data\state\MapEntry;
use qtism\data\state\ResponseDeclaration;

class InlineChoiceInteractionValidationBuilder
{
    private $exceptions = [];
    private $validation = null;
    private $isCaseSensitive = false;

    public function __construct(
        array $possibleResponses,
        array $responseDeclarations = null,
        ResponseProcessingTemplate $responseProcessingTemplate = null
    ) {
        if (!empty($responseProcessingTemplate) && ! empty($responseDeclarations)) {
            $template = $responseProcessingTemplate->getTemplate();
            if ($template === ResponseProcessingTemplate::MATCH_CORRECT) {
                $this->validation = $this->buildMatchCorrectValidation($possibleResponses, $responseDeclarations);
            } elseif ($template === ResponseProcessingTemplate::MAP_RESPONSE) {
                $this->validation = $this->buildMapResponseValidation($possibleResponses, $responseDeclarations);
            } else {
                $this->exceptions[] = new MappingException(
                    'Does not support template ' . $template .
                    ' on <responseProcessing>'
                );
            }
        }
    }

    private function buildMatchCorrectValidation(array $possibleResponses, array $responseDeclarations)
    {
        /** @var ResponseDeclaration $responseDeclaration */
        $validResponsesValues = [];
        foreach ($responseDeclarations as $responseIdentifier => $responseDeclaration) {
            $values = [];
            foreach ($responseDeclaration->getCorrectResponse()->getValues() as $value) {
                $values[] = $possibleResponses[$responseIdentifier][$value->getValue()];
            }
            $validResponsesValues[] = $values;
        }
        $combinationsValidResponseValues = ArrayUtil::mutateResponses($validResponsesValues);

        // Interaction count
        $interactionCount = count($responseDeclarations);

        // First response pair shall be mapped to `valid_response`
        $firstValidResponseValue = array_shift($combinationsValidResponseValues);
        $validResponse = new clozedropdown_validation_valid_response();
        $validResponse->set_score($interactionCount);
        $validResponse->set_value(is_array($firstValidResponseValue) ? $firstValidResponseValue : [$firstValidResponseValue]);

        // Others go in `alt_responses`
        $altResponses = [];
        foreach ($combinationsValidResponseValues as $otherResponseValues) {
            $item = new clozedropdown_validation_alt_responses_item();
            $item->set_score($interactionCount);
            $item->set_value(is_array($otherResponseValues) ? $otherResponseValues : [$otherResponseValues]);
            $altResponses[] = $item;
        }

        $validation = new clozedropdown_validation();
        $validation->set_scoring_type('exactMatch');
        $validation->set_valid_response($validResponse);

        if (!empty($altResponses)) {
            $validation->set_alt_responses($altResponses);
        }

        return $validation;
    }

    private function buildMapResponseValidation($possibleResponses, array $responseDeclarations)
    {
        /** Build key score mapping ie.
         *  [   'identifierOne' => ['mapKey' => 'mappedValue'],
         *      'identifierTwo' => ['mapKey' => 'mappedValue']  ] */

        $keyScoreMapping = [];
        /** @var ResponseDeclaration $responseDeclaration */
        foreach ($responseDeclarations as $responseIdentifier => $responseDeclaration) {
            $mapping = [];
            foreach ($responseDeclaration->getMapping()->getMapEntries()->getArrayCopy(true) as $mapEntry) {
                /** @var MapEntry $mapEntry */
                $responseValue = $possibleResponses[$responseIdentifier][$mapEntry->getMapKey()];
                $mapping[$mapEntry->getMapKey()] = [
                    'score' => $mapEntry->getMappedValue(),
                    'value' => $responseValue
                ];
                // Find out if one of them is case sensitive
                if ($mapEntry->isCaseSensitive()) {
                    $this->isCaseSensitive = true;
                }
            }
            $keyScoreMapping[] = $mapping;
        }

        // Get an array of correct responses for Learnosity object
        $correctResponses = [];
        foreach (ArrayUtil::mutateResponses(array_map('array_keys', array_values($keyScoreMapping))) as $combination) {
            $responseValues = [];
            $score = 0;
            $combination = is_array($combination) ? $combination : [$combination];
            foreach ($combination as $index => $mapKey) {
                $responseValues[] = $keyScoreMapping[$index][$mapKey]['value'];
                $score += $keyScoreMapping[$index][$mapKey]['score'];
            }
            $correctResponses[] = [
                'values' => $responseValues,
                'score' => $score
            ];
        }

        // Sort by score value, as the first/biggest would be used for `valid_response` object
        usort($correctResponses, function ($a, $b) {
            return $a['score'] < $b['score'];
        });

        $responseList = [];
        foreach ($correctResponses as $resp) {
            $responseList[] = [
                'score' => $resp['score'],
                'value' => $resp['values']
            ];
        }
        $validationBuilder = new ValidationBuilder('exactMatch', $responseList);
        return $validationBuilder->buildValidation('clozedropdown');
    }

    public function getExceptions()
    {
        return $this->exceptions;
    }

    public function getValidation()
    {
        return $this->validation;
    }

    public function isCaseSensitive()
    {
        return $this->isCaseSensitive;
    }
}
