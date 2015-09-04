<?php

namespace Learnosity\Processors\QtiV2\In\Validation;

use Learnosity\Exceptions\MappingException;
use Learnosity\Processors\Learnosity\In\ValidationBuilder;
use Learnosity\Processors\Learnosity\In\ValidationBuilder\ValidResponse;
use Learnosity\Utils\ArrayUtil;
use qtism\common\datatypes\DirectedPair;
use qtism\data\state\MapEntry;
use qtism\data\state\ResponseDeclaration;

class GapMatchInteractionValidationBuilder extends BaseInteractionValidationBuilder
{
    private $questionTypeName;
    private $gapIdentifiers;
    private $possibleResponses;
    private $responseDeclaration;

    private $isDuplicatedResponse = false;

    public function __construct(
        $questionTypeName,
        array $gapIdentifiers,
        array $possibleResponses,
        ResponseDeclaration $responseDeclaration = null
    ) {
        $this->questionTypeName = $questionTypeName;
        $this->gapIdentifiers = $gapIdentifiers;
        $this->possibleResponses = $possibleResponses;
        $this->responseDeclaration = $responseDeclaration;
    }

    public function isDuplicatedResponse()
    {
        return $this->isDuplicatedResponse;
    }

    protected function getMatchCorrectTemplateValidation()
    {
        $gapIdentifiersIndexMap = array_flip($this->gapIdentifiers);
        $responses = [];
        $responseIndexSet = [];

        foreach ($this->responseDeclaration->getCorrectResponse()->getValues() as $value) {
            /** @var DirectedPair $valuePair */
            $valuePair = $value->getValue();

            // Map response value and index based from `DirectedPair` Value, try to guess which one is which since they
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
            $responses[$responseIndex][] = new ValidResponse(1, [$responseValue]);
        }

        $this->assertEachGapHasCorrespondingValidResponses($responses);
        $responses = ArrayUtil::cartesianProduct($responses);
        $responses = array_map(function ($combination) {
            $value = [];
            /** @var ValidResponse $response */
            foreach ($combination as $response) {
                $value = array_merge($value, $response->getValue());
            }
            return new ValidResponse(1, $value);
        }, $responses);

        return ValidationBuilder\ValidationBuilder::build($this->questionTypeName, 'exactMatch', $responses);
    }

    protected function getMapResponseTemplateValidation()
    {
        $gapIdentifiersIndexMap = array_flip($this->gapIdentifiers);
        $responseIndexSet = [];
        $responses = [];

        foreach ($this->responseDeclaration->getMapping()->getMapEntries() as $mapEntry) {
            /** @var MapEntry $mapEntry */
            /** @var DirectedPair $mapKey */
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
        return ValidationBuilder\ValidationBuilder::build($this->questionTypeName, 'exactMatch', $responses);
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
