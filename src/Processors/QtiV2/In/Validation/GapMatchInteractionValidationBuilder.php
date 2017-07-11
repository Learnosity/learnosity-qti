<?php

namespace LearnosityQti\Processors\QtiV2\In\Validation;

use LearnosityQti\Exceptions\MappingException;
use LearnosityQti\Processors\Learnosity\In\ValidationBuilder;
use LearnosityQti\Processors\Learnosity\In\ValidationBuilder\ValidResponse;
use LearnosityQti\Services\LogService;
use LearnosityQti\Utils\ArrayUtil;
use qtism\common\datatypes\QtiDirectedPair;
use qtism\data\state\MapEntry;
use qtism\data\state\ResponseDeclaration;

class GapMatchInteractionValidationBuilder extends BaseInteractionValidationBuilder
{
    private $questionTypeName;
    private $gapIdentifiers;
    private $possibleResponses;
    private $isDuplicatedResponse = false;

    public function __construct(
        $questionTypeName,
        array $gapIdentifiers,
        array $possibleResponses,
        ResponseDeclaration $responseDeclaration = null
    ) {
        parent::__construct($responseDeclaration);
        $this->questionTypeName = $questionTypeName;
        $this->gapIdentifiers = $gapIdentifiers;
        $this->possibleResponses = $possibleResponses;
    }

    public function isDuplicatedResponse()
    {
        return $this->isDuplicatedResponse;
    }

    protected function getMatchCorrectTemplateValidation(array $scores = null)
    {
        $score = 1;
        $mode = 'exactMatch';
        if (!empty($scores['scoring_type']) && $scores['scoring_type'] === 'partial') {
            $mode = 'partialMatch';

            if (!empty($scores['score'])) {
                $score = floatval($scores['score']);
            }
        }

        $gapIdentifiersIndexMap = array_flip($this->gapIdentifiers);
        $responses = [];
        $responseIndexSet = [];

        foreach ($this->responseDeclaration->getCorrectResponse()->getValues() as $value) {
            /** @var QtiDirectedPair $valuePair */
            $valuePair = $value->getValue();

            // Map response value and index based from `QtiDirectedPair` Value, try to guess which one is which since they
            // apparently can swap :(
            if (isset($this->possibleResponses[$valuePair->getFirst()]) && isset($gapIdentifiersIndexMap[$valuePair->getSecond()])) {
                $responseValue = $this->possibleResponses[$valuePair->getFirst()];
                $responseIndex = $gapIdentifiersIndexMap[$valuePair->getSecond()];
            } else if (isset($this->possibleResponses[$valuePair->getSecond()]) && isset($gapIdentifiersIndexMap[$valuePair->getFirst()])) {
                $responseValue = $this->possibleResponses[$valuePair->getSecond()];
                $responseIndex = $gapIdentifiersIndexMap[$valuePair->getFirst()];
            } else {
                throw new MappingException('Fail to match identifiers on Value from `correctResponse`');
            }

            // Check for duplicated response
            if (!$this->isDuplicatedResponse) {
                if (!isset($responseIndexSet[$responseValue])) {
                    $responseIndexSet[$responseValue] = true;
                } else {
                    $this->isDuplicatedResponse = true;
                }
            }

            // Build ValidResponse object array in the correct order matching the `gap` elements
            $responses[$responseIndex][] = new ValidResponse($score, [$responseValue]);
        }

        $responses = $this->ensureEachGapHasCorrespondingValidResponses($responses);
        $responses = ArrayUtil::cartesianProduct($responses);
        $responses = array_map(function ($combination) use ($score) {
            $value = [];
            /** @var ValidResponse $response */
            foreach ($combination as $response) {
                $value = array_merge($value, $response->getValue());
            }
            return new ValidResponse($score, $value);
        }, $responses);

        return ValidationBuilder\ValidationBuilder::build($this->questionTypeName, $mode, $responses);
    }

    protected function getMapResponseTemplateValidation()
    {
        $gapIdentifiersIndexMap = array_flip($this->gapIdentifiers);
        $responseIndexSet = [];
        $responses = [];

        foreach ($this->responseDeclaration->getMapping()->getMapEntries() as $mapEntry) {
            /** @var MapEntry $mapEntry */
            /** @var QtiDirectedPair $mapKey */
            $mapKey = $mapEntry->getMapKey();

            // Map response value and index based from the `mapKey`, try to guess which one is which since they
            // apparently can swap :(
            if (isset($this->possibleResponses[$mapKey->getFirst()]) && isset($gapIdentifiersIndexMap[$mapKey->getSecond()])) {
                $responseValue = $this->possibleResponses[$mapKey->getFirst()];
                $responseIndex = $gapIdentifiersIndexMap[$mapKey->getSecond()];
            } else if (isset($this->possibleResponses[$mapKey->getSecond()]) && isset($gapIdentifiersIndexMap[$mapKey->getFirst()])) {
                $responseValue = $this->possibleResponses[$mapKey->getSecond()];
                $responseIndex = $gapIdentifiersIndexMap[$mapKey->getFirst()];
            } else {
                throw new MappingException('Fail to match identifiers on `mapKey` attribute from `mapping`');
            }

            // Check for duplicated response
            if (!$this->isDuplicatedResponse) {
                if (!isset($responseIndexSet[$responseValue])) {
                    $responseIndexSet[$responseValue] = true;
                } else {
                    $this->isDuplicatedResponse = true;
                }
            }

            // Wrap the identifier => score into an array as the identifier can be duplicated (Duplicated Response)
            // Build ValidResponse object array in the correct order matching the `gap` elements
            $responses[$responseIndex][] = new ValidResponse($mapEntry->getMappedValue(), [$responseValue]);
        }

        $responses = $this->ensureEachGapHasCorrespondingValidResponses($responses);

        $responses = ArrayUtil::cartesianProductForResponses($responses);
        return ValidationBuilder\ValidationBuilder::build($this->questionTypeName, 'exactMatch', $responses);
    }

    private function ensureEachGapHasCorrespondingValidResponses(array $responses)
    {
        // If the amount is not equal, log it
        if (count($this->gapIdentifiers) !== count($responses)) {
            LogService::log('Amount of gap identifiers ' . count($this->gapIdentifiers) . ' does not match the amount ' .
                count($responses) . ' for <responseDeclaration>');
            // Also, we need to populate its remaining gap identifiers with the only possible answer of being null
            foreach ($this->gapIdentifiers as $index => $identifier) {
                if (!isset($responses[$index])) {
                    $responses[$index] = [];
                }
                $responses[$index][] = new ValidResponse(0, [null]);
            }
            ksort($responses);
        }

        return $responses;
    }
}
