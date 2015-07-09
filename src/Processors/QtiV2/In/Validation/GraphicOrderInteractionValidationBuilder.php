<?php

namespace Learnosity\Processors\QtiV2\In\Validation;

use Learnosity\Exceptions\MappingException;
use Learnosity\Processors\Learnosity\In\ValidationBuilder\ValidationBuilder;
use Learnosity\Processors\Learnosity\In\ValidationBuilder\ValidResponse;
use qtism\data\state\ResponseDeclaration;
use qtism\data\state\Value;

class GraphicOrderInteractionValidationBuilder extends BaseInteractionValidationBuilder
{
    private $responsePositionIndexMap;
    private $responseDeclaration;

    public function __construct(array $responsePositionIndexMap, ResponseDeclaration $responseDeclaration = null)
    {
        $this->responsePositionIndexMap = $responsePositionIndexMap;
        $this->responseDeclaration = $responseDeclaration;
    }

    protected function getMatchCorrectTemplateValidation()
    {
        // Build the `value` object on `valid_response`
        $values = [];
        foreach ($this->responseDeclaration->getCorrectResponse()->getValues() as $v) {
            /** @var Value $v */
            $value = $v->getValue();
            if (!isset($this->responsePositionIndexMap[$value])) {
                $this->exceptions[] = new MappingException('Cannot locate ' . $value . ' in responseDeclaration');
                continue;
            }
            $values[] = $this->responsePositionIndexMap[$value];
        }

        // Validate against mismatch possible responses and correct response count
        if (count($this->responsePositionIndexMap) !== count($values)) {
            throw new MappingException(
                'Count of `hotspotChoice` ' . count($this->responsePositionIndexMap) . ' not equal to count of correct response value ' .
                count($values) . ' on responseDeclaration. Ignoring validation',
                MappingException::CRITICAL
            );
        }

        return ValidationBuilder::build('imageclozeassociation', 'exactMatch', [new ValidResponse(1, $values)]);
    }
}
