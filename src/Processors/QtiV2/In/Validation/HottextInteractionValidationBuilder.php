<?php

namespace LearnosityQti\Processors\QtiV2\In\Validation;

use LearnosityQti\Processors\Learnosity\In\ValidationBuilder\ValidationBuilder;
use LearnosityQti\Processors\Learnosity\In\ValidationBuilder\ValidResponse;
use LearnosityQti\Utils\ArrayUtil;
use qtism\data\state\MapEntry;
use qtism\data\state\ResponseDeclaration;
use qtism\data\state\Value;

class HottextInteractionValidationBuilder extends BaseInteractionValidationBuilder
{
    private $hottextComponents;
    private $maxChoices;

    public function __construct(array $hottextComponents, $maxChoices, ResponseDeclaration $responseDeclaration = null)
    {
        parent::__construct($responseDeclaration);
        $this->maxChoices = $maxChoices;
        $this->hottextComponents = $hottextComponents;
    }

    protected function getMatchCorrectTemplateValidation(array $scores = null)
    {
        $scores = $this->getScoresForInteraction($scores);
        list($score, $mode) = $this->getValidationScoringData($scores);

        $validResponseValues = [];
        foreach ($this->responseDeclaration->getCorrectResponse()->getValues() as $value) {
            /** @var Value $value */
            $hottextIdentifier = $value->getValue();
            $validResponseValues[] = $this->hottextComponents[$hottextIdentifier];
        }
        return ValidationBuilder::build('tokenhighlight', $mode, [new ValidResponse($score, $validResponseValues)]);
    }

    protected function getMapResponseTemplateValidation()
    {
        $mapEntryValueMap = [];
        foreach ($this->responseDeclaration->getMapping()->getMapEntries() as $mapEntry) {
            /** @var MapEntry $mapEntry */
            $mapEntryValueMap[] = [
                'key' => $this->hottextComponents[$mapEntry->getMapKey()],
                'score' => $mapEntry->getMappedValue()
            ];
        }

        $combinations = ArrayUtil::combinations($mapEntryValueMap);
        $correctResponses = [];
        foreach ($combinations as $combination) {
            if (count($combination) > 0 && count($combination) <= $this->maxChoices) {
                $score = array_sum(array_column($combination, 'score'));
                $value = array_column($combination, 'key');
                $correctResponses[] = new ValidResponse($score, $value);
            }
        }

        return ValidationBuilder::build('tokenhighlight', 'exactMatch', $correctResponses);
    }
}
