<?php

namespace Learnosity\Mappers\QtiV2\Import\MergedInteractions;

use Learnosity\Entities\QuestionTypes\clozetext;
use Learnosity\Entities\QuestionTypes\clozetext_validation;
use Learnosity\Entities\QuestionTypes\clozetext_validation_alt_responses_item;
use Learnosity\Entities\QuestionTypes\clozetext_validation_valid_response;
use Learnosity\Exceptions\MappingException;
use Learnosity\Mappers\QtiV2\Import\ResponseProcessingTemplate;
use Learnosity\Mappers\QtiV2\Import\Utils\QtiComponentUtil;
use Learnosity\Mappers\QtiV2\Import\Validation\TextEntryInteractionValidationBuilder;
use Learnosity\Utils\ArrayUtil;
use qtism\data\content\interactions\Interaction;
use qtism\data\content\interactions\TextEntryInteraction;
use qtism\data\content\ItemBody;
use qtism\data\state\MapEntry;
use qtism\data\state\ResponseDeclaration;

class MergedTextEntryInteraction extends AbstractMergedInteraction
{
    private $interactionComponents;

    public function getQuestionType()
    {
        // we assume the function maintain the order of the xml element
        $this->interactionComponents = $this->itemBody->getComponentsByClassName('textEntryInteraction', true);
        $interactionXmls = [];
        $interactionIdentifiers = [];
        /** @var TextEntryInteraction $component */
        foreach ($this->interactionComponents as $component) {
            $interactionXmls[] = QtiComponentUtil::marshall($component);
            $interactionIdentifiers[] = $component->getResponseIdentifier();
        }
        $validation = $this->buildValidation($interactionIdentifiers, $isCaseSensitive);
        $closetext = new clozetext('clozetext', $this->buildTemplate($this->itemBody, $interactionXmls));
        if ($validation) {
            $closetext->set_validation($validation);
        }
        $isMultiLine = false;
        $maxLength = $this->getExpectedLength($isMultiLine);
        $closetext->set_max_length($maxLength);
        $closetext->set_multiple_line($isMultiLine);
        $closetext->set_case_sensitive($isCaseSensitive);
        return $closetext;
    }

    private function getExpectedLength(&$isMultiLine)
    {
        $maxExpectedLength = -1;
        /** @var TextEntryInteraction $component */
        foreach ($this->interactionComponents as $component) {
            $length = $component->getExpectedLength();;
            if ($maxExpectedLength < $length) {
                $maxExpectedLength = $length;
            }
        }
        if ($maxExpectedLength > 250) {
            $maxExpectedLength = 250;
            $isMultiLine = true;
        }
        return $maxExpectedLength;
    }

    private function buildTemplate(ItemBody $itemBody, array $interactionXmls)
    {
        // Build item's HTML content
        $content = QtiComponentUtil::marshallCollection($itemBody->getComponents());
        foreach ($interactionXmls as $interactionXml) {
            $content = str_replace($interactionXml, '{{response}}', $content);
        }
        return $content;
    }

    public function getItemContent()
    {
        return '<span class="learnosity-response question-' . $this->questionReference . '"></span>';
    }

    public function buildValidation(array $interactionIdentifiers, &$isCaseSensitive)
    {
        $isCaseSensitive = false;
        $originalResponseData = [];
        if (!($this->responseProcessingTemplate instanceof ResponseProcessingTemplate)) {
            $this->exceptions[] =
                new MappingException('Response declaration is not defined',
                    MappingException::CRITICAL);
            return null;
        }
        foreach ($interactionIdentifiers as $interactionIdentifier) {
            if (!isset($this->responseDeclarations[$interactionIdentifier])) {
                $this->exceptions[] =
                    new MappingException("Unable to locate {$interactionIdentifier}" . ' in response declarations',
                        MappingException::CRITICAL);
                continue;
            }
            switch ($this->responseProcessingTemplate->getTemplate()) {
                case ResponseProcessingTemplate::MATCH_CORRECT:
                    $score = 1;
                    $answers = [];
                    /* @var $responseElement ResponseDeclaration */
                    $responseElement = $this->responseDeclarations[$interactionIdentifier];
                    foreach ($responseElement->getCorrectResponse()->getValues() as $value) {
                        $answers[] = [$value->getValue() => $score];
                    }
                    $originalResponseData[] = $answers;
                    break;
                case ResponseProcessingTemplate::CC2_MAP_RESPONSE:
                case ResponseProcessingTemplate::MAP_RESPONSE:
                    /* @var $responseElement ResponseDeclaration */
                    $responseElement = $this->responseDeclarations[$interactionIdentifier];
                    $mapEntryElements = $responseElement->getMapping()->getMapEntries();
                    $interactionResponse = [];
                    /* @var $mapEntryElement MapEntry */
                    foreach ($mapEntryElements as $mapEntryElement) {
                        $interactionResponse[] = [$mapEntryElement->getMapKey() => $mapEntryElement->getMappedValue()];
                        if (!$isCaseSensitive && $mapEntryElement->isCaseSensitive()) {
                            $isCaseSensitive = $mapEntryElement->isCaseSensitive();
                        }
                    }
                    $originalResponseData[] = $interactionResponse;
                    break;
                default:
                    $this->exceptions[] =
                        new MappingException('Unrecognised response processing template. Validation is not available');
                    return null;

            }
        }

        if (!$originalResponseData) {
            return null;
        }

        $mutatedOriginalResponses = ArrayUtil::mutateResponses($originalResponseData);
        // order score from highest to lowest
        usort($mutatedOriginalResponses, function ($a, $b) {
            return array_sum(array_values($a)) < array_sum(array_values($b));
        });

        $validationBuilder = new TextEntryInteractionValidationBuilder();
        return $validationBuilder->buildValidation($mutatedOriginalResponses);
    }
}
