<?php

namespace Learnosity\Processors\QtiV2\In\Validation;

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
            $responseIndex = $this->stemsMapping[$valuePair->getFirst()];
            $responseValue = $this->optionsMapping[$valuePair->getSecond()];

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
            $responseIndex = $this->stemsMapping[$mapEntry->getMapKey()->getFirst()];
            $responseValue = $this->optionsMapping[$mapEntry->getMapKey()->getSecond()];

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
