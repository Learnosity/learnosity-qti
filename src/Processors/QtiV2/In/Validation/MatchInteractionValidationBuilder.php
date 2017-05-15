<?php

namespace LearnosityQti\Processors\QtiV2\In\Validation;

use LearnosityQti\Exceptions\MappingException;
use LearnosityQti\Processors\Learnosity\In\ValidationBuilder\ValidationBuilder;
use LearnosityQti\Processors\Learnosity\In\ValidationBuilder\ValidResponse;
use qtism\common\datatypes\QtiDirectedPair;
use qtism\data\state\MapEntry;
use qtism\data\state\ResponseDeclaration;

class MatchInteractionValidationBuilder extends BaseInteractionValidationBuilder
{
    private $stemsMapping;
    private $optionsMapping;

    public function __construct(array $stemsMapping, array $optionsMapping, ResponseDeclaration $responseDeclaration = null)
    {
        parent::__construct($responseDeclaration);
        $this->stemsMapping = $stemsMapping;
        $this->optionsMapping = $optionsMapping;
    }

    protected function getMatchCorrectTemplateValidation(array $scores = null)
    {
        $mode = 'exactMatch';
        $score = 1;

        if (!empty($scores['scoring_type']) && $scores['scoring_type'] === 'partial') {
            $mode = 'partialMatch';

            if (!empty($scores['score'])) {
                $score = floatval($scores['score']);
            }
        }

        // Build `value` array for a `valid_response` objects
        $values = [];
        foreach ($this->responseDeclaration->getCorrectResponse()->getValues() as $value) {
            /** @var QtiDirectedPair $valuePair */
            $valuePair = $value->getValue();

            // Map response value and index based from `QtiDirectedPair` Value, try to guess which one is which since they
            // apparently can swap :(
            if (isset($this->stemsMapping[$valuePair->getFirst()]) && isset($this->optionsMapping[$valuePair->getSecond()])) {
                $responseIndex = $this->stemsMapping[$valuePair->getFirst()];
                $responseValue = $this->optionsMapping[$valuePair->getSecond()];
            } else if (isset($this->stemsMapping[$valuePair->getSecond()]) && isset($this->optionsMapping[$valuePair->getFirst()])) {
                $responseIndex = $this->stemsMapping[$valuePair->getSecond()];
                $responseValue = $this->optionsMapping[$valuePair->getFirst()];
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

        // Just to make sure we don't screw the order
        ksort($values);
        return ValidationBuilder::build('choicematrix', $mode, [new ValidResponse($score, $values)]);
    }

    protected function getMapResponseTemplateValidation(array $scores = null)
    {
        $mode = 'exactMatch';

        // Build `value` array for a `valid_response` objects
        $values = [];
        $score = 0;
        foreach ($this->responseDeclaration->getMapping()->getMapEntries() as $mapEntry) {
            /** @var MapEntry $mapEntry */
            $score += $mapEntry->getMappedValue();
            $mapKey = $mapEntry->getMapKey();

            // Map response value and index based from `QtiDirectedPair` Value, try to guess which one is which since they
            // apparently can swap :(
            if (isset($this->stemsMapping[$mapKey->getFirst()]) && isset($this->optionsMapping[$mapKey->getSecond()])) {
                $responseIndex = $this->stemsMapping[$mapKey->getFirst()];
                $responseValue = $this->optionsMapping[$mapKey->getSecond()];
            } else if (isset($this->stemsMapping[$mapKey->getSecond()]) && isset($this->optionsMapping[$mapKey->getFirst()])) {
                $responseIndex = $this->stemsMapping[$mapKey->getSecond()];
                $responseValue = $this->optionsMapping[$mapKey->getFirst()];
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

        // Just to make sure we don't screw the order
        ksort($values);

        if (!empty($scores['scoring_type']) && $scores['scoring_type'] === 'partial') {
            $mode = 'partialMatch';

            if (!empty($scores['score'])) {
                $score = floatval($scores['score']);
            }
        }
        return ValidationBuilder::build('choicematrix', $mode, [new ValidResponse($score, $values)]);
    }
}
