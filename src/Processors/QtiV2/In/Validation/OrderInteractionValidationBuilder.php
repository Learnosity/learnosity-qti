<?php

namespace LearnosityQti\Processors\QtiV2\In\Validation;

use LearnosityQti\Processors\Learnosity\In\ValidationBuilder\ValidationBuilder;
use LearnosityQti\Processors\Learnosity\In\ValidationBuilder\ValidResponse;
use LearnosityQti\Services\LogService;
use qtism\data\state\ResponseDeclaration;
use qtism\data\state\Value;

class OrderInteractionValidationBuilder extends BaseInteractionValidationBuilder
{
    private $orderMapping;

    public function __construct(array $orderMapping, ResponseDeclaration $responseDeclaration = null)
    {
        parent::__construct($responseDeclaration);
        $this->orderMapping = $orderMapping;
    }

    protected function getMatchCorrectTemplateValidation(array $scores = null)
    {
        $scores = $this->getScoresForInteraction($scores);
        list($score, $mode) = $this->getValidationScoringData($scores);

        // TODO: Validate against mismatch possible responses and correct response
        // Build the `value` object on `valid_response`
        $values = [];
        foreach ($this->responseDeclaration->getCorrectResponse()->getValues() as $v) {
            /** @var Value $v */
            $value = $v->getValue();
            if (!isset($this->orderMapping[$value])) {
                LogService::log('Cannot locate ' . $value . ' in responseDeclaration');
                continue;
            }
            $values[] = $this->orderMapping[$value];
        }
        return ValidationBuilder::build('orderlist', $mode, [new ValidResponse($score, $values)]);
    }
}
