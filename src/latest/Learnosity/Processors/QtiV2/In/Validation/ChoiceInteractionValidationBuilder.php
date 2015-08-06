<?php

namespace Learnosity\Processors\QtiV2\In\Validation;

use Learnosity\Processors\Learnosity\In\ValidationBuilder\ValidationBuilder;
use Learnosity\Processors\Learnosity\In\ValidationBuilder\ValidResponse;
use Learnosity\Services\LogService;
use qtism\data\state\ResponseDeclaration;
use qtism\data\state\Value;

class ChoiceInteractionValidationBuilder extends BaseInteractionValidationBuilder
{
    private $responseDeclaration;
    private $options;

    public function __construct(ResponseDeclaration $responseDeclaration = null, array $options = [])
    {
        $this->responseDeclaration = $responseDeclaration;
        $this->options = $options;
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
            $values[] = $value->getValue();
        }
        $responses = [new ValidResponse(1, $values)];
        return ValidationBuilder::build('mcq', 'exactMatch', $responses);
    }
}
