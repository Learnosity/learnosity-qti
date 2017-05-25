<?php

namespace LearnosityQti\Processors\QtiV2\In\Validation;

use \LearnosityQti\Processors\Learnosity\In\ValidationBuilder\ValidationBuilder;
use \LearnosityQti\Processors\Learnosity\In\ValidationBuilder\ValidResponse;
use \LearnosityQti\Services\LogService;
use \LearnosityQti\Utils\ArrayUtil;
use \qtism\common\enums\Cardinality;
use \qtism\data\state\MapEntry;
use \qtism\data\state\ResponseDeclaration;
use \qtism\data\state\Value;
use \qtism\data\state\OutcomeDeclarationCollection;

class ChoiceInteractionValidationBuilder extends BaseInteractionValidationBuilder
{
    private $options;
    private $maxChoices;

    public function __construct(
        ResponseDeclaration $responseDeclaration = null,
        array $options,
        $maxChoices,
        OutcomeDeclarationCollection $outcomeDeclarations = null
    ) {
        parent::__construct($responseDeclaration, $outcomeDeclarations);
        $this->options = $options;
        $this->maxChoices = $maxChoices;
    }

    protected function getMatchCorrectTemplateValidation(array $scores = null)
    {
        $score = 1;
        if (!empty($scores['correct'])) {
            $score = $scores['correct'];
        }

        $mode = 'exactMatch';
        if (!empty($scores['scoring_type']) && $scores['scoring_type'] === 'partial') {
            $mode = 'partialMatch';
        }

        // Build the `value` object for `valid_response`
        $values = [];
        /** @var Value $value */
        $correctResponseObject = $this->responseDeclaration->getCorrectResponse();
        if (isset($correctResponseObject)) {
            foreach ($correctResponseObject->getValues() as $value) {
                if (!isset($this->options[$value->getValue()])) {
                    LogService::log('Invalid choice `' . $value->getValue() .  '`');
                    continue;
                }
                $values[] = $value->getValue();
            }
        }

        return ValidationBuilder::build('mcq', $mode, [new ValidResponse($score, $values)]);
    }

    protected function getMapResponseTemplateValidation()
    {
        $validResponses = [];
        foreach ($this->responseDeclaration->getMapping()->getMapEntries() as $mapEntry) {
            /** @var MapEntry $mapEntry */
            if (!isset($this->options[$mapEntry->getMapKey()])) {
                LogService::log('Invalid choice `' . $mapEntry->getMapKey() .  '`');
                continue;
            }
            if ($mapEntry->getMappedValue() < 0) {
                LogService::log('Invalid score ` ' . $mapEntry->getMappedValue() . ' `. Negative score is ignored');
                continue;
            }
            $validResponses[] = new ValidResponse($mapEntry->getMappedValue(), [$mapEntry->getMapKey()]);
        }

        // Handle `multiple` cardinality
        if ($this->responseDeclaration->getCardinality() === Cardinality::MULTIPLE) {
            $combinationChoicesCount = $this->maxChoices === 0 ? count($validResponses) : $this->maxChoices;
            $combinationResponses = ArrayUtil::combinations($validResponses, $combinationChoicesCount);
            $validResponses = ArrayUtil::combineValidResponsesWithSummedScore($combinationResponses);
        }

        return ValidationBuilder::build('mcq', 'exactMatch', $validResponses);
    }
}
