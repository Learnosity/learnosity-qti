<?php
namespace Learnosity\Processors\QtiV2\In\Validation;

use Learnosity\Processors\Learnosity\In\ValidationBuilder\ValidationBuilder;
use Learnosity\Processors\Learnosity\In\ValidationBuilder\ValidResponse;
use Learnosity\Utils\ArrayUtil;
use qtism\data\state\MapEntry;
use qtism\data\state\ResponseDeclaration;
use qtism\data\state\Value;

class TextEntryInteractionValidationBuilder extends BaseInteractionValidationBuilder
{
    private $isCaseSensitive = false;
    private $responseDeclarations = [];

    public function __construct(array $interactionIdentifiers = [], array $unsortedResponseDeclarations = [])
    {
        //TODO: Technically incorrect, but this is simply used to auto-detect response processing template so it doesnt matter much
        parent::__construct(null);

        // Need to sort based on interaction identifiers first
        foreach ($interactionIdentifiers as $interactionIdentifier) {
            if (isset($unsortedResponseDeclarations[$interactionIdentifier])) {
                $this->responseDeclarations[$interactionIdentifier] = $unsortedResponseDeclarations[$interactionIdentifier];
            }
        }
    }

    protected function getMatchCorrectTemplateValidation()
    {
        $interactionResponses = [];
        foreach ($this->responseDeclarations as $responseIdentifier => $responseDeclaration) {
            /** @var ResponseDeclaration $responseDeclaration */
            $correctResponses = $responseDeclaration->getCorrectResponse()->getValues()->getArrayCopy(true);
            $interactionResponses[] = array_map(function ($value) {
                /** @var Value $value */
                return new ValidResponse(1, [$value->getValue()]);
            }, $correctResponses);
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
                    [$mapEntry->getMapKey()]
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
