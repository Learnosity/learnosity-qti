<?php

namespace Learnosity\Processors\QtiV2\In\MergedInteractions;

use Learnosity\Entities\QuestionTypes\clozedropdown;
use Learnosity\Exceptions\MappingException;
use Learnosity\Processors\QtiV2\In\Utils\QtiComponentUtil;
use Learnosity\Processors\QtiV2\In\Validation\InlineChoiceInteractionValidationBuilder;
use qtism\data\content\interactions\InlineChoice;
use qtism\data\content\interactions\Interaction;
use qtism\data\content\ItemBody;

class MergedInlineChoiceInteractionMapper extends AbstractMergedInteractionMapper
{
    public function getQuestionType()
    {
        $interactionComponents = $this->itemBody->getComponentsByClassName('inlineChoiceInteraction', true);

        //TODO: Throw all the warnings to an array
        $interactionXmls = [];
        $possibleResponsesMap = [];
        foreach ($interactionComponents as $component) {
            /** @var Interaction $component */
            $interactionXmls[] = QtiComponentUtil::marshall($component);
            /** @var InlineChoice $inlineChoice */
            foreach ($component->getComponents() as $inlineChoice) {
                $possibleResponsesMap[$component->getResponseIdentifier()][$inlineChoice->getIdentifier()] =
                    QtiComponentUtil::marshallCollection($inlineChoice->getContent());
            }
        }

        $template = $this->buildTemplate($this->itemBody, $interactionXmls);
        $clozedropdown = new clozedropdown('clozedropdown', $template, array_values(array_map('array_values', $possibleResponsesMap)));

//        $validationBuilder = new InlineChoiceInteractionValidationBuilder(
//            $possibleResponsesMap,
//            $this->responseDeclarations,
//            $this->responseProcessingTemplate
//        );
//        $validation = $validationBuilder->getValidation();
        $isCaseSensitive = false;
        $validation = $this->buildValidation($possibleResponsesMap, $isCaseSensitive);
        if (!empty($validation)) {
            $clozedropdown->set_validation($validation);
        }

        $clozedropdown->set_case_sensitive($isCaseSensitive);
        if ($isCaseSensitive) {
            $this->exceptions[] = new MappingException('Partial `caseSensitive` per response is not supported.
                Thus setting all validation as case sensitive');
        }

//        $this->exceptions = array_merge($this->exceptions, $validationBuilder->getExceptions());
        return $clozedropdown;
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

    private function buildValidation(array $possibleResponses, &$isCaseSensitive)
    {
        if (!$this->responseProcessingTemplate) {
            $this->exceptions[] =
                new MappingException(
                    'Response Processing Template is not defined so validation is not available.',
                    MappingException::WARNING
                );
            return null;
        }

        $validationBuilder = new InlineChoiceInteractionValidationBuilder(
            $this->responseProcessingTemplate,
            $this->responseDeclarations,
            'clozedropdown'
        );
        $validationBuilder->init($possibleResponses);
        $validation = $validationBuilder->buildValidation();
        $isCaseSensitive = $validationBuilder->isCaseSensitive();
        $this->exceptions = array_merge($this->exceptions, $validationBuilder->getExceptions());
        return $validation;
    }
}
