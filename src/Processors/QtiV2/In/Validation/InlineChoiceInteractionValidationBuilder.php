<?php

namespace Learnosity\Processors\QtiV2\In\Validation;

use Learnosity\Processors\Learnosity\In\ValidationBuilder\ValidationBuilder;
use Learnosity\Processors\Learnosity\In\ValidationBuilder\ValidResponse;
use Learnosity\Utils\ArrayUtil;
use qtism\data\state\MapEntry;
use qtism\data\state\ResponseDeclaration;
use qtism\data\state\Value;

class InlineChoiceInteractionValidationBuilder extends BaseInteractionValidationBuilder
{
    private $isCaseSensitive = false;
    private $possibleResponses;
    private $responseDeclarations = [];

    public function __construct(array $unsortedResponseDeclarations = [], array $possibleResponses = [])
    {
        $this->possibleResponses = $possibleResponses;

        // Need to sort based on interaction identifiers first, which assumed to be listed
        foreach ($possibleResponses as $interactionIdentifier => $interactionPossibleResponses) {
            if (isset($unsortedResponseDeclarations[$interactionIdentifier])) {
                $this->responseDeclarations[$interactionIdentifier] = $unsortedResponseDeclarations[$interactionIdentifier];
            }
        }
    }

    public function isCaseSensitive()
    {
        return $this->isCaseSensitive;
    }

    protected function getMatchCorrectTemplateValidation()
    {
        $interactionResponses = [];
        foreach ($this->responseDeclarations as $responseIdentifier => $responseDeclaration) {
            /** @var ResponseDeclaration $responseDeclaration */
            $correctResponses = $responseDeclaration->getCorrectResponse()->getValues()->getArrayCopy(true);
            $possibleResponses = $this->possibleResponses[$responseIdentifier];
            $interactionResponses[] = array_map(function ($value) use ($possibleResponses) {
                /** @var Value $value */
                return new ValidResponse(1, [$possibleResponses[$value->getValue()]]);
            }, $correctResponses);
        }
        $responses = ArrayUtil::cartesianProductForResponses($interactionResponses);
        return ValidationBuilder::build('clozedropdown', 'exactMatch', $responses);
    }

    protected function getMapResponseTemplateValidation()
    {
        $interactionResponses = [];
        /** @var ResponseDeclaration $responseDeclaration */
        foreach ($this->responseDeclarations as $responseIdentifier => $responseDeclaration) {
            $responses = [];
            foreach ($responseDeclaration->getMapping()->getMapEntries()->getArrayCopy(true) as $mapEntry) {
                /** @var MapEntry $mapEntry */
                $responses[] = new ValidResponse(
                    $mapEntry->getMappedValue(),
                    [$this->possibleResponses[$responseIdentifier][$mapEntry->getMapKey()]]
                );
                // Find out if one of them is case sensitive
                if ($mapEntry->isCaseSensitive()) {
                    $this->isCaseSensitive = true;
                }
            }
            $interactionResponses[] = $responses;
        }

        $responses = ArrayUtil::cartesianProductForResponses($interactionResponses);
        return ValidationBuilder::build('clozedropdown', 'exactMatch', $responses);
    }
}
