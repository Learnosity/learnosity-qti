<?php
namespace LearnosityQti\Processors\QtiV2\In\Validation;

use \LearnosityQti\Processors\Learnosity\In\ValidationBuilder\ValidationBuilder;
use \LearnosityQti\Processors\Learnosity\In\ValidationBuilder\ValidResponse;
use \LearnosityQti\Services\LogService;
use \LearnosityQti\Utils\ArrayUtil;
use \qtism\data\state\MapEntry;
use \qtism\data\state\ResponseDeclaration;
use \qtism\data\state\OutcomeDeclarationCollection;
use \qtism\data\state\Value;

class TextEntryInteractionValidationBuilder extends BaseInteractionValidationBuilder
{
    private $isCaseSensitive = false;
    private $responseDeclarations = [];

    public function __construct(
        array $interactionIdentifiers = [],
        array $unsortedResponseDeclarations = [],
        ResponseDeclaration $responseDeclaration = null,
        OutcomeDeclarationCollection $outcomeDeclarations = null
    ) {
        //TODO: Technically incorrect, but this is simply used to auto-detect response processing template so it doesnt matter much
        parent::__construct($responseDeclaration, $outcomeDeclarations);

        // Need to sort based on interaction identifiers first
        foreach ($interactionIdentifiers as $interactionIdentifier) {
            if (isset($unsortedResponseDeclarations[$interactionIdentifier])) {
                $this->responseDeclarations[$interactionIdentifier] = $unsortedResponseDeclarations[$interactionIdentifier];
            }
        }
    }

    protected function getMatchCorrectTemplateValidation(array $scores = null)
    {
        $interactionResponses = [];
        foreach ($this->responseDeclarations as $responseIdentifier => $responseDeclaration) {
            /** @var ResponseDeclaration $responseDeclaration */
            if (!empty($responseDeclaration->getCorrectResponse())) {
                $correctResponses = $responseDeclaration->getCorrectResponse()->getValues()->getArrayCopy(true);
                $interactionResponses[] = array_map(function ($value) {
                    /** @var Value $value */
                    return new ValidResponse(1, [(string)$value->getValue()]);
                }, $correctResponses);
            }
        }

        if (empty($interactionResponses)) {
            // there was nothing in the response declaration
            if (!empty($scores['correct'])) {
                if (is_array($scores['correct'])) {
                    foreach ($scores['correct'] as $correct) {
                        $interactionResponses[][] = new ValidResponse($correct['score'], [(string)$correct['answer']]);
                    }
                }
            }
        }

        if (empty($interactionResponses)) {
            LogService::log('Response declaration has no valid correct response values. Thus, validation ignored');
        }

        $responses = ArrayUtil::cartesianProductForResponses($interactionResponses);
        return ValidationBuilder::build('clozetext', 'exactMatch', $responses);
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
                    [(string)$mapEntry->getMapKey()]
                );
                // Find out if one of them is case sensitive
                if ($mapEntry->isCaseSensitive()) {
                    $this->isCaseSensitive = true;
                }
            }
            $interactionResponses[] = $responses;
        }

        $responses = ArrayUtil::cartesianProductForResponses($interactionResponses);
        return ValidationBuilder::build('clozetext', 'exactMatch', $responses);
    }

    public function isCaseSensitive()
    {
        return $this->isCaseSensitive;
    }
}
