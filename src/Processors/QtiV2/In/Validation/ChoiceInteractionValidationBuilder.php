<?php

namespace Learnosity\Processors\QtiV2\In\Validation;

use Learnosity\Exceptions\MappingException;
use qtism\data\state\ResponseDeclaration;
use qtism\data\state\Value;

class ChoiceInteractionValidationBuilder extends BaseQtiValidationBuilder
{
    protected function handleMatchCorrectTemplate()
    {
        /** @var ResponseDeclaration $responseDeclaration */
        $responseDeclaration = $this->responseDeclarations[0];
        $correctResponse = $responseDeclaration->getCorrectResponse();

        $validResponseValues = [];
        /** @var Value $value */
        foreach ($correctResponse->getValues() as $value) {
            $validResponseValues[] = $value->getValue();
        }

        $this->scoringType = 'exactMatch';
        $this->originalResponseData = [
            [
                'score' => 1,
                'value' => $validResponseValues
            ]
        ];
    }

    protected function handleMapResponseTemplate()
    {
        $this->exceptions[] =
            new MappingException('This interaction does not support `map_response` validation. Validation is not available');
    }

    protected function prepareOriginalResponseData()
    {
    }
}
