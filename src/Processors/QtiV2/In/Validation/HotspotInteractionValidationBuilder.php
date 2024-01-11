<?php

namespace LearnosityQti\Processors\QtiV2\In\Validation;

use Exception;
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

    protected function getMatchCorrectTemplateValidation(array $scores = null)
    {
        $scores = $this->getScoresForInteraction($scores);
        list($score, $mode) = $this->getValidationScoringData($scores);

        $choicesIndexes = array_flip(array_keys($this->choices));

        if (($this->responseDeclaration->getCorrectResponse())) {
            $correctResponses = $this->responseDeclaration->getCorrectResponse()->getValues()->getArrayCopy(true);
        } else {
            $correctResponses = [];
            throw new Exception('Could not determine hotspot validation.');
        }

        $values = [];
        /** @var Value $response */
        foreach ($correctResponses as $response) {
            $identifier = $response->getValue();
            $values[] = strval($choicesIndexes[$identifier]);
        }

        // No alt responses for hotspot interaction
        return ValidationBuilder::build('hotspot', $mode, [
            new ValidResponse($score, $values)
        ]);
    }
}
