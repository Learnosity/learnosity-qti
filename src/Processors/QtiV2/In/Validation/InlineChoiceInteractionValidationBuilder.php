<?php

namespace LearnosityQti\Processors\QtiV2\In\Validation;

use LearnosityQti\Processors\Learnosity\In\ValidationBuilder\ValidationBuilder;
use LearnosityQti\Processors\Learnosity\In\ValidationBuilder\ValidResponse;
use LearnosityQti\Services\LogService;
use LearnosityQti\Utils\ArrayUtil;
use qtism\data\state\MapEntry;
use qtism\data\state\ResponseDeclaration;
use qtism\data\state\Value;

class InlineChoiceInteractionValidationBuilder extends BaseInteractionValidationBuilder
{
    private $isCaseSensitive = false;
    private $possibleResponses;
    protected $responseDeclarations = [];

    public function __construct(array $unsortedResponseDeclarations = [], array $possibleResponses = [])
    {
        //TODO: Technically incorrect, but this is simply used to auto-detect response processing template so it doesnt matter much
        parent::__construct(null);
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
                if (!$mapEntry->isCaseSensitive()) {
                    LogService::log('Could not support `caseSensitive` attribute for this interaction type. This question validation is always case sensitive');
                }
            }
            $interactionResponses[] = $responses;
        }

        $responses = ArrayUtil::cartesianProductForResponses($interactionResponses);
        return ValidationBuilder::build('clozedropdown', 'exactMatch', $responses);
    }
}
