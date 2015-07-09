<?php
namespace Learnosity\Processors\QtiV2\In\Validation;

use Learnosity\Exceptions\MappingException;
use Learnosity\Processors\Learnosity\In\ValidationBuilder\ValidationBuilder;
use Learnosity\Processors\Learnosity\In\ValidationBuilder\ValidResponse;
use qtism\data\state\ResponseDeclaration;
use qtism\data\state\Value;

class OrderInteractionValidationBuilder extends BaseInteractionValidationBuilder
{
    private $orderMapping;
    private $responseDeclaration;

    public function __construct(array $orderMapping, ResponseDeclaration $responseDeclaration = null)
    {
        $this->orderMapping = $orderMapping;
        $this->responseDeclaration = $responseDeclaration;
    }

    protected function getMatchCorrectTemplateValidation()
    {
        // Build the `value` object on `valid_response`
        $values = [];
        foreach ($this->responseDeclaration->getCorrectResponse()->getValues() as $v) {
            /** @var Value $v */
            $value = $v->getValue();
            if (!isset($this->orderMapping[$value])) {
                $this->exceptions[] = new MappingException('Cannot locate ' . $value . ' in responseDeclaration');
                continue;
            }
            $values[] = $this->orderMapping[$value];
        }
        return ValidationBuilder::build('orderlist', 'exactMatch', [new ValidResponse(1, $values)]);
    }
}
