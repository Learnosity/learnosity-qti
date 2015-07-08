<?php

namespace Learnosity\Processors\QtiV2\In\Validation;

use Learnosity\Utils\ArrayUtil;
use qtism\data\state\MapEntry;
use qtism\data\state\ResponseDeclaration;

class MergedTextEntryInteractionValidationBuilder extends BaseQtiValidationBuilder
{
    private $interactionIdentifiers;
    private $isCaseSensitive;

    public function init(array $interactionIdentifiers)
    {
        $this->interactionIdentifiers = $interactionIdentifiers;
        $this->isCaseSensitive = false;
        $this->scoringType = 'exactMatch';
    }

    protected function handleMatchCorrectTemplate()
    {
        foreach ($this->interactionIdentifiers as $interactionIdentifier) {
            $score = 1;
            $answers = [];
            /* @var $responseElement ResponseDeclaration */
            $responseElement = $this->responseDeclarations[$interactionIdentifier];
            foreach ($responseElement->getCorrectResponse()->getValues() as $value) {
                $answers[] = [$value->getValue() => $score];
            }
            $this->originalResponseData[] = $answers;
        }
    }

    protected function handleMapResponseTemplate()
    {
        foreach ($this->interactionIdentifiers as $interactionIdentifier) {
            /* @var $responseElement ResponseDeclaration */
            $responseElement = $this->responseDeclarations[$interactionIdentifier];
            $mapEntryElements = $responseElement->getMapping()->getMapEntries();
            $interactionResponse = [];
            /* @var $mapEntryElement MapEntry */
            foreach ($mapEntryElements as $mapEntryElement) {
                $interactionResponse[] = [$mapEntryElement->getMapKey() => $mapEntryElement->getMappedValue()];
                if (!$this->isCaseSensitive && $mapEntryElement->isCaseSensitive()) {
                    $this->isCaseSensitive = $mapEntryElement->isCaseSensitive();
                }
            }
            $this->originalResponseData[] = $interactionResponse;
        }
    }

    protected function handleCC2MapResponseTemplate()
    {
        $this->handleCC2MapResponseTemplate();
    }

    protected function prepareOriginalResponseData()
    {
        $mutatedOriginalResponses = ArrayUtil::mutateResponses($this->originalResponseData);

        // Order score from highest to lowest
        usort($mutatedOriginalResponses, function ($a, $b) {
            return array_sum(array_values($a)) < array_sum(array_values($b));
        });

        $responseList = [];
        for ($i = 0; $i < count($mutatedOriginalResponses); $i++) {
            $scoreGlobal = 0;
            $value = [];
            foreach ($mutatedOriginalResponses[$i] as $answer => $score) {
                $value[] = $answer;
                $scoreGlobal += $score;
            }
            $responseList[] = [
                'score' => $scoreGlobal,
                'value' => $value
            ];
        }
        $this->originalResponseData = $responseList;
    }

    public function isCaseSensitive()
    {
        return $this->isCaseSensitive;
    }
}
