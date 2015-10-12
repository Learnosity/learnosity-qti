<?php

namespace Learnosity\Processors\QtiV2\In\Validation;

use Learnosity\Processors\Learnosity\In\ValidationBuilder\ValidationBuilder;
use Learnosity\Processors\Learnosity\In\ValidationBuilder\ValidResponse;
use Learnosity\Services\LogService;
use Learnosity\Utils\ArrayUtil;
use qtism\common\enums\Cardinality;
use qtism\data\state\MapEntry;
use qtism\data\state\ResponseDeclaration;
use qtism\data\state\Value;

class ChoiceInteractionValidationBuilder extends BaseInteractionValidationBuilder
{
    private $options;
    private $maxChoices;

    public function __construct(ResponseDeclaration $responseDeclaration = null, array $options, $maxChoices)
    {
        parent::__construct($responseDeclaration);
        $this->options = $options;
        $this->maxChoices = $maxChoices;
    }

    protected function getMatchCorrectTemplateValidation()
    {
        // Build the `value` object for `valid_response`
        $values = [];
        /** @var Value $value */
        foreach ($this->responseDeclaration->getCorrectResponse()->getValues() as $value) {
            if (!isset($this->options[$value->getValue()])) {
                LogService::log('Invalid choice `' . $value->getValue() .  '`');
                continue;
            }
            $values[] = new ValidResponse(1, [$value->getValue()]);
        }

        // Handle `multiple` cardinality
        if ($this->responseDeclaration->getCardinality() === Cardinality::MULTIPLE) {
            $combinationChoicesCount = $this->maxChoices === 0 ? count($values) : $this->maxChoices;
            $combinationResponses = ArrayUtil::combinations($values, $combinationChoicesCount);
            $values = ArrayUtil::combineValidResponsesWithFixedScore($combinationResponses, 1);
        }

        return ValidationBuilder::build('mcq', 'exactMatch', $values);
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
