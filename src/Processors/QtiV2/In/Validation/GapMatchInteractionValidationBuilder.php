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
            $responseValue = $this->possibleResponses[$valuePair->getFirst()];
            $responseIndex = $gapIdentifiersIndexMap[$valuePair->getSecond()];

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
            $mapKey = $mapEntry->getMapKey();
            // TODO: Might need to do validation here for invalid identifiers
            $responseValue = $this->possibleResponses[$mapKey->getFirst()];
            $responseIndex = $gapIdentifiersIndexMap[$mapKey->getSecond()];

            // Check for duplicated response
            if (!$this->isDuplicatedResponse) {
                if (!isset($responseIndexSet[$responseValue])) {
                    $responseIndexSet[$responseValue] = true;
                } else {
                    $this->isDuplicatedResponse = true;
                }
            }

            // wrap the identifier=>score into an array as the identifier can be duplicated (Duplicated Response)
            // Build ValidResponse object array in the correct order matching the `gap` elements
            $responses[$responseIndex][] = new ValidResponse($mapEntry->getMappedValue(), [$responseValue]);
        }

        $this->assertEachGapHasCorrespondingValidResponses($responses);
        $responses = ArrayUtil::cartesianProductForResponses($responses);
        return ValidationBuilder\ValidationBuilder::build($this->questionTypeName, 'exactMatch', $responses);
    }

    private function assertEachGapHasCorrespondingValidResponses(array $responses)
    {
        // TODO: Previously this was hiding `critical` exception in an array?? What`s the purpose~
        // TODO: Need to fix up exceptions vs. error messages things
        if (count($this->gapIdentifiers) !== count($responses)) {
            throw new MappingException(
                'Amount of Gap Identifiers ' . count($this->gapIdentifiers) . ' does not match the amount ' .
                count($responses) . ' for responseDeclaration',
                MappingException::CRITICAL
            );
        }
    }
}
