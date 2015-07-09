<?php

namespace Learnosity\Processors\QtiV2\In\Validation;

use Learnosity\Exceptions\MappingException;
use Learnosity\Processors\Learnosity\In\ValidationBuilder\ValidationBuilder;
use Learnosity\Processors\Learnosity\In\ValidationBuilder\ValidResponse;
use qtism\data\state\ResponseDeclaration;

class HotspotInteractionValidationBuilder extends BaseInteractionValidationBuilder
{
    private $marker;
    private $responsePositionIndexMap;
    private $responseDeclaration;

    public function __construct($marker, array $responsePositionIndexMap, ResponseDeclaration $responseDeclaration = null)
    {
        $this->marker = $marker;
        $this->responsePositionIndexMap = $responsePositionIndexMap;
        $this->responseDeclaration = $responseDeclaration;
    }

    protected function getMatchCorrectTemplateValidation()
    {
        $responses = [];
        foreach ($this->responseDeclaration->getCorrectResponse()->getValues() as $v) {
            if (!isset($this->responsePositionIndexMap[$v->getValue()])) {
                $this->exceptions[] = new MappingException('Ignoring invalid correct
                    response value `' . $v->getValue() . '`');
                continue;
            }
            // Build `value` object for valid responses
            // ie. [null, null, 'X', null]
            $value = array_fill(0, count($this->responsePositionIndexMap), null);
            $value[$this->responsePositionIndexMap[$v->getValue()]] = $this->marker;
            $responses[] = new ValidResponse(1, $value);
        }
        return ValidationBuilder::build('imageclozeassociation', 'exactMatch', $responses);
    }

    protected function getMapResponseTemplateValidation()
    {
    }
}
