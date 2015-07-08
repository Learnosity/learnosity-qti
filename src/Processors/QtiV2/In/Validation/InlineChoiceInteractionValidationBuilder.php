<?php

namespace Learnosity\Processors\QtiV2\In\Validation;

use Learnosity\Utils\ArrayUtil;
use qtism\data\state\MapEntry;
use qtism\data\state\ResponseDeclaration;

class InlineChoiceInteractionValidationBuilder extends BaseQtiValidationBuilder
{

    private $isCaseSensitive = false;
    private $possibleResponses;

    public function init(array $possibleResponses)
    {
        $this->possibleResponses = $possibleResponses;
        $this->scoringType = 'exactMatch';
    }

    public function isCaseSensitive()
    {
        return $this->isCaseSensitive;
    }

    protected function handleMatchCorrectTemplate()
    {
        $validResponsesValues = [];
        foreach ($this->responseDeclarations as $responseIdentifier => $responseDeclaration) {
            /** @var ResponseDeclaration $responseDeclaration */
            $values = [];
            foreach ($responseDeclaration->getCorrectResponse()->getValues() as $value) {
                $data = new \stdClass();
                $data->values = [$this->possibleResponses[$responseIdentifier][$value->getValue()]];
                $data->score = 1;
                $values[] =
                    [
                        $data
                    ];
            }
            $validResponsesValues[] = $values;
        }
        $this->originalResponseData = ArrayUtil::mutateResponses($validResponsesValues);
    }

    protected function handleMapResponseTemplate()
    {
        $keyScoreMapping = [];
        /** @var ResponseDeclaration $responseDeclaration */
        foreach ($this->responseDeclarations as $responseIdentifier => $responseDeclaration) {
            $mapping = [];
            foreach ($responseDeclaration->getMapping()->getMapEntries()->getArrayCopy(true) as $mapEntry) {
                /** @var MapEntry $mapEntry */
                $responseValue = $this->possibleResponses[$responseIdentifier][$mapEntry->getMapKey()];
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
            $data = new \stdClass();
            $data->values = $responseValues;
            $data->score = $score;
            $correctResponses[] = $data;
        }

        // Sort by score value, as the first/biggest would be used for `valid_response` object
        usort($correctResponses, function ($a, $b) {
            return $a->score < $b->score;
        });

        $this->originalResponseData = $correctResponses;
    }

    protected function handleCC2MapResponseTemplate()
    {
        $this->handleMapResponseTemplate();
    }

    protected function prepareOriginalResponseData()
    {
        $responseList = [];
        foreach ($this->originalResponseData as $resp) {
            $scores = 0;
            $values = [];
            if (is_array($resp)) {
                foreach ($resp as $r) {
                    $scores += $r->score;
                    $values = array_merge($values, $r->values);
                }
            } else {
                $scores = $resp->score;
                $values = $resp->values;
            }

            $responseList[] = [
                'score' => $scores,
                'value' => $values
            ];
        }
        $this->originalResponseData = $responseList;
    }
}
