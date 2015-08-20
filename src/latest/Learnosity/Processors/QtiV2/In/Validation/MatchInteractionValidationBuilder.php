<?php

namespace Learnosity\Processors\QtiV2\In\Validation;

use Learnosity\Exceptions\MappingException;
use Learnosity\Processors\Learnosity\In\ValidationBuilder\ValidationBuilder;
use Learnosity\Processors\Learnosity\In\ValidationBuilder\ValidResponse;
use qtism\common\datatypes\DirectedPair;
use qtism\data\state\MapEntry;
use qtism\data\state\ResponseDeclaration;

class MatchInteractionValidationBuilder extends BaseInteractionValidationBuilder
{
    private $stemsMapping;
    private $optionsMapping;
    private $responseDeclaration;

    private $isMultipleResponse = false;

    public function __construct(array $stemsMapping, array $optionsMapping, ResponseDeclaration $responseDeclaration = null)
    {
        $this->responseDeclaration = $responseDeclaration;
        $this->stemsMapping = $stemsMapping;
        $this->optionsMapping = $optionsMapping;
    }

    public function isMultipleResponse()
    {
        return $this->isMultipleResponse;
    }

    protected function getMatchCorrectTemplateValidation()
    {
        // Build `value` array for a `valid_response` objects
        $values = [];
        foreach ($this->responseDeclaration->getCorrectResponse()->getValues() as $value) {
            /** @var DirectedPair $valuePair */
            $valuePair = $value->getValue();

            // Map response value and index based from `DirectedPair` Value, try to guess which one is which since they
            // apparently can swap :(
            if (isset($this->stemsMapping[$valuePair->getFirst()]) && isset($this->optionsMapping[$valuePair->getSecond()])) {
                $responseValue = $this->stemsMapping[$valuePair->getFirst()];
                $responseIndex = $this->optionsMapping[$valuePair->getSecond()];
            } else if (isset($this->stemsMapping[$valuePair->getSecond()]) && isset($this->optionsMapping[$valuePair->getFirst()])) {
                $responseValue = $this->stemsMapping[$valuePair->getSecond()];
                $responseIndex = $this->optionsMapping[$valuePair->getFirst()];
            } else {
                throw new MappingException('Fail to match identifiers on Value from `correctResponse`');
            }

            // Build values array in the correct order
            if (!isset($values[$responseIndex])) {
                $values[$responseIndex] = [$responseValue];
            } else {
                array_push($values[$responseIndex], $responseValue);
            }
        }

        // Check for multiple responses
        $this->isMultipleResponse = max(array_map('count', $values)) > 1;

        // Just to make sure we don't screw the order
        ksort($values);
        return ValidationBuilder::build('choicematrix', 'exactMatch', [new ValidResponse(1, $values)]);
    }

    protected function getMapResponseTemplateValidation()
    {
        // Build `value` array for a `valid_response` objects
        $values = [];
        $score = 0;
        foreach ($this->responseDeclaration->getMapping()->getMapEntries() as $mapEntry) {
            /** @var MapEntry $mapEntry */
            $score += $mapEntry->getMappedValue();
            $mapKey = $mapEntry->getMapKey();

            // Map response value and index based from `DirectedPair` Value, try to guess which one is which since they
            // apparently can swap :(
            if (isset($this->stemsMapping[$mapKey->getFirst()]) && isset($this->optionsMapping[$mapKey->getSecond()])) {
                $responseValue = $this->stemsMapping[$mapKey->getFirst()];
                $responseIndex = $this->optionsMapping[$mapKey->getSecond()];
            } else if (isset($this->stemsMapping[$mapKey->getSecond()]) && isset($this->optionsMapping[$mapKey->getFirst()])) {
                $responseValue = $this->stemsMapping[$mapKey->getSecond()];
                $responseIndex = $this->optionsMapping[$mapKey->getFirst()];
            } else {
                throw new MappingException('Fail to match identifiers on `mapKey` attribute from `mapping`');
            }

            // Build values array in the correct order
            if (!isset($values[$responseIndex])) {
                $values[$responseIndex] = [$responseValue];
            } else {
                array_push($values[$responseIndex], $responseValue);
            }
        }

        // Check for multiple responses
        $this->isMultipleResponse = max(array_map('count', $values)) > 1;

        // Just to make sure we don't screw the order
        ksort($values);
        return ValidationBuilder::build('choicematrix', 'exactMatch', [new ValidResponse($score, $values)]);
    }
}
