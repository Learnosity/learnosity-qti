<?php

namespace Learnosity\Mappers\QtiV2\Import\Validation;

use Learnosity\Entities\QuestionTypes\clozeassociation_validation;
use Learnosity\Entities\QuestionTypes\clozeassociation_validation_alt_responses_item;
use Learnosity\Entities\QuestionTypes\clozeassociation_validation_valid_response;
use Learnosity\Exceptions\MappingException;
use Learnosity\Mappers\QtiV2\Import\ResponseProcessingTemplate;
use Learnosity\Utils\ArrayUtil;
use qtism\common\datatypes\DirectedPair;
use qtism\data\state\MapEntry;
use qtism\data\state\Mapping;
use qtism\data\state\ResponseDeclaration;

class GapMatchInteractionValidationBuilder
{
    private $exceptions = [];
    private $validation = null;
    private $isDuplicatedResponse = false;

    public function __construct(
        array $gapIdentifiers,
        array $possibleResponses,
        ResponseDeclaration $responseDeclaration = null,
        ResponseProcessingTemplate $responseProcessingTemplate = null
    )
    {
        if (!empty($responseProcessingTemplate) && !empty($responseDeclaration)) {
            $template = $responseProcessingTemplate->getTemplate();
            if ($template === ResponseProcessingTemplate::MATCH_CORRECT) {
                $this->validation = $this->buildMatchCorrectValidation($gapIdentifiers, $possibleResponses, $responseDeclaration);
            } elseif ($template === ResponseProcessingTemplate::MAP_RESPONSE) {
                $this->validation = $this->buildMapResponseValidation($gapIdentifiers, $possibleResponses, $responseDeclaration);
            } else {
                $this->exceptions[] = new MappingException(
                    'Does not support template ' . $template .
                    ' on <responseProcessing>'
                );
            }
        }
    }

    public function isDuplicatedResponse()
    {
        return $this->isDuplicatedResponse;
    }

    private function buildMatchCorrectValidation(array $gapIdentifiers, array $possibleResponses, ResponseDeclaration $responseDeclaration)
    {
        $gapIdentifiersIndexMap = array_flip($gapIdentifiers);
        $validResponses = [];
        $responseIndexSet = [];
        foreach ($responseDeclaration->getCorrectResponse()->getValues() as $value) {
            /** @var DirectedPair $valuePair */
            $valuePair = $value->getValue();
            $responseValue = $possibleResponses[$valuePair->getFirst()];
            $responseIndex = $gapIdentifiersIndexMap[$valuePair->getSecond()];
            if (!$this->isDuplicatedResponse) {
                if (!isset($responseIndexSet[$responseIndex])) {
                    $responseIndexSet[$responseIndex] = true;
                } else {
                    $this->isDuplicatedResponse = true;
                }
            }
            // Build valid response array in the correct order matching the `gap` elements
            $validResponses[$responseIndex][] = $responseValue;
        }
        if (count($gapIdentifiers) !== count($validResponses)) {
            $this->exceptions[] =
                new MappingException(
                    'Amount of Gap Identifiers ' . count($gapIdentifiers) . ' does not match the amount ' .
                    count($validResponses) . ' for responseDeclaration',
                    MappingException::CRITICAL);
            return null;
        }

        ksort($validResponses);
        $combinationValidResponse = ArrayUtil::mutateResponses($validResponses);

        // First response pair shall be mapped to `valid_response`
        $firstValidResponseValue = array_shift($combinationValidResponse);
        $validResponse = new clozeassociation_validation_valid_response();
        $validResponse->set_score(1);
        $validResponse->set_value($firstValidResponseValue);

        // Others go in `alt_responses`
        $altResponses = [];
        foreach ($combinationValidResponse as $otherResponseValues) {
            $item = new clozeassociation_validation_alt_responses_item();
            $item->set_score(1);
            $item->set_value($otherResponseValues);
            $altResponses[] = $item;
        }

        $validation = new clozeassociation_validation();
        $validation->set_scoring_type('exactMatch');
        $validation->set_valid_response($validResponse);

        if (!empty($altResponses)) {
            $validation->set_alt_responses($altResponses);
        }
        return $validation;
    }

    private function buildMapResponseValidation(array $gapIdentifiers, array $possibleResponses, ResponseDeclaration $responseDeclaration)
    {
        $this->isDuplicatedResponse = false;
        $responseIndexSet = [];
        if (!($responseDeclaration->getMapping() instanceof Mapping)) {
            return null;
        }
        $mapEntries = $responseDeclaration->getMapping()->getMapEntries();
        $gapMapping = [];
        /** @var MapEntry $mapEntry */
        foreach ($mapEntries as $mapEntry) {
            $mapKey = $mapEntry->getMapKey();
            if ($mapKey instanceof DirectedPair) {
                if (isset($possibleResponses[$mapKey->getFirst()])) {
                    $responseIndex = $mapKey->getFirst();
                    $gapIndex = $mapKey->getSecond();
                } else {
                    $responseIndex = $mapKey->getSecond();
                    $gapIndex = $mapKey->getFirst();
                }
                if (!isset($gapMapping[$gapIndex])) {
                    $gapMapping[$gapIndex] = [];
                }
                if (!$this->isDuplicatedResponse) {
                    if (!isset($responseIndexSet[$responseIndex])) {
                        $responseIndexSet[$responseIndex] = true;
                    } else {
                        $this->isDuplicatedResponse = true;
                    }
                }
                // wrap the identifier=>score into an array as the identifier can be duplicated (Duplicated Response)
                $gapMapping[$gapIndex][] = [[$responseIndex => $mapEntry->getMappedValue()]];
            }
        }
        $responseValue = [];
        foreach ($gapIdentifiers as $key => $value) {
            if (!isset($gapMapping[$value])) {
                $this->exceptions[] = new MappingException(
                    'Gap Identifier ' . $value . ' does not exist',
                    MappingException::CRITICAL
                );
                return null;
            }
            $responseValue[$key] = $gapMapping[$value];
        }
        $responseValue = ArrayUtil::mutateResponses($responseValue);
        // we make sure the first item is having the highest score
        usort($responseValue, function ($a, $b) {
            return array_sum(ArrayUtil::arrayValsMulti($a)) < array_sum(ArrayUtil::arrayValsMulti($b));
        });

        $validResponse = null;
        $altResponses = [];

        if (count($responseValue) === 0) {
            return null;
        }
        $validResponse = new clozeassociation_validation_valid_response();
        $validResponse->set_score(array_sum(ArrayUtil::arrayValsMulti($responseValue[0])));
        $responseIDList = ArrayUtil::arrayKeysMulti($responseValue[0]);
        $validResponse->set_value($this->getGapValueList($responseIDList, $possibleResponses));
        if (count($responseValue) > 1) {
            for ($i = 1; $i < count($responseValue); $i++) {
                $altResponse = new clozeassociation_validation_alt_responses_item();
                $altResponse->set_score(array_sum(ArrayUtil::arrayValsMulti($responseValue[$i])));
                $responseIDList = ArrayUtil::arrayKeysMulti($responseValue[$i]);
                $altResponse->set_value($this->getGapValueList($responseIDList, $possibleResponses));
                $altResponses[] = $altResponse;
            }
        }
        $validation = new clozeassociation_validation();
        $validation->set_scoring_type('exactMatch');
        $validation->set_valid_response($validResponse);
        if (!empty($altResponses)) {
            $validation->set_alt_responses($altResponses);
        }
        return $validation;
    }

    public function getExceptions()
    {
        return $this->exceptions;
    }

    public function getValidation()
    {
        return $this->validation;
    }

    private function getGapValueList(array $gapKeys, array $possibleResponses)
    {
        $gapValue = [];
        foreach ($gapKeys as $gapKey) {
            if (isset($possibleResponses[$gapKey])) {
                $gapValue[] = $possibleResponses[$gapKey];
            }
        }
        return $gapValue;
    }
}
