<?php

namespace LearnosityQti\Processors\QtiV2\In\Validation;

use LearnosityQti\Exceptions\MappingException;
use LearnosityQti\Processors\Learnosity\In\ValidationBuilder;
use LearnosityQti\Processors\Learnosity\In\ValidationBuilder\ValidResponse;
use LearnosityQti\Utils\ArrayUtil;
use qtism\common\datatypes\QtiDirectedPair;
use qtism\data\state\MapEntry;
use qtism\data\state\ResponseDeclaration;
use \qtism\data\state\OutcomeDeclarationCollection;

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
        ResponseDeclaration $responseDeclaration = null,
        OutcomeDeclarationCollection $outcomeDeclarations = null
    ) {
        parent::__construct($responseDeclaration, $outcomeDeclarations);
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
        $scores = $this->getScoresForInteraction($scores);
        list($score, $mode) = $this->getValidationScoringData($scores);

        $gapIdentifiersIndexMap = array_flip($this->gapIdentifiers);
        $responses = [];
        $responseIndexSet = [];

        if (!empty($this->responseDeclaration->getCorrectResponse())) {
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

            $this->assertEachGapHasCorrespondingValidResponses($responses);
            $responses = ArrayUtil::cartesianProduct($responses);
            $responses = array_map(function ($combination) use ($score) {
                $value = [];
                /** @var ValidResponse $response */
                foreach ($combination as $response) {
                    $value = array_merge($value, $response->getValue());
                }
                return new ValidResponse($score, $value);
            }, $responses);
        }

        return ValidationBuilder\ValidationBuilder::build($this->questionTypeName, $mode, $responses);
    }

    protected function getMapResponseTemplateValidation()
    {
        $gapIdentifiersIndexMap = array_flip($this->gapIdentifiers);
        $responseIndexSet = [];
        $responses = [];

        // FIXME: Remove this hard-coded variable and implement handling of mode
        // for map response template validation
        $mode = 'exactMatch';

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

        $this->assertEachGapHasCorrespondingValidResponses($responses);
        $responses = ArrayUtil::cartesianProductForResponses($responses);
        return ValidationBuilder\ValidationBuilder::build($this->questionTypeName, $mode, $responses);
    }

    private function assertEachGapHasCorrespondingValidResponses(array $responses)
    {
        if (count($this->gapIdentifiers) !== count($responses)) {
            throw new MappingException(
                'Amount of gap identifiers ' . count($this->gapIdentifiers) . ' does not match the amount ' .
                count($responses) . ' for <responseDeclaration>'
            );
        }
    }
}
