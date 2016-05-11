<?php

namespace LearnosityQti\Processors\QtiV2\In\Validation;

use LearnosityQti\Processors\Learnosity\In\ValidationBuilder\ValidationBuilder;
use LearnosityQti\Processors\Learnosity\In\ValidationBuilder\ValidResponse;
use qtism\data\state\ResponseDeclaration;
use qtism\data\state\Value;

class HotspotInteractionValidationBuilder extends BaseInteractionValidationBuilder
{
    private $choices;
    private $maxChoices;

    public function __construct(ResponseDeclaration $responseDeclaration, array $choices, $maxChoices)
    {
        parent::__construct($responseDeclaration);
        $this->choices = $choices;
        $this->maxChoices = $maxChoices;
    }

    protected function getMatchCorrectTemplateValidation()
    {
        $choicesIndexes = array_flip(array_keys($this->choices));
        $correctResponses = $this->responseDeclaration->getCorrectResponse()->getValues()->getArrayCopy(true);

        $values = [];
        /** @var Value $response */
        foreach ($correctResponses as $response) {
            $identifier = $response->getValue();
            $values[] = strval($choicesIndexes[$identifier]);
        }

        // No alt responses for hotspot interaction
        return ValidationBuilder::build('hotspot', 'exactMatch', [
            new ValidResponse(1, $values)
        ]);
    }
}
