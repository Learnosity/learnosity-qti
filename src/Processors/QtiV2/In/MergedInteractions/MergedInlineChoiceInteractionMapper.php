<?php

namespace LearnosityQti\Processors\QtiV2\In\MergedInteractions;

use LearnosityQti\Entities\QuestionTypes\clozedropdown;
use LearnosityQti\Utils\QtiMarshallerUtil;
use LearnosityQti\Processors\QtiV2\In\Validation\InlineChoiceInteractionValidationBuilder;
use qtism\data\content\interactions\InlineChoice;
use qtism\data\content\interactions\Interaction;
use qtism\data\content\ItemBody;

class MergedInlineChoiceInteractionMapper extends AbstractMergedInteractionMapper
{
    public function getQuestionType()
    {
        $interactionComponents = $this->itemBody->getComponentsByClassName('inlineChoiceInteraction', true);

        $interactionXmls = [];
        $possibleResponsesMap = [];
        foreach ($interactionComponents as $component) {
            /** @var Interaction $component */
            $interactionXmls[] = QtiMarshallerUtil::marshall($component);
            /** @var InlineChoice $inlineChoice */
            foreach ($component->getComponents() as $inlineChoice) {
                $possibleResponsesMap[$component->getResponseIdentifier()][$inlineChoice->getIdentifier()] =
                    QtiMarshallerUtil::marshallCollection($inlineChoice->getContent());
            }
        }

        $template = $this->buildTemplate($this->itemBody, $interactionXmls);
        $clozedropdown = new clozedropdown('clozedropdown', $template, array_values(array_map('array_values', $possibleResponsesMap)));


        $validationBuilder = new InlineChoiceInteractionValidationBuilder(
            $this->responseDeclarations,
            $possibleResponsesMap,
            $this->outcomeDeclarations
        );

        // Build `validation`
        $validation = $validationBuilder->buildValidation($this->responseProcessingTemplate);
        if (!empty($validation)) {
            $clozedropdown->set_validation($validation);
        }

        return $clozedropdown;
    }

    private function buildTemplate(ItemBody $itemBody, array $interactionXmls)
    {
        // Build item's HTML content
        $content = QtiMarshallerUtil::marshallCollection($itemBody->getComponents());
        foreach ($interactionXmls as $interactionXml) {
            $content = str_replace($interactionXml, '{{response}}', $content);
        }
        return $content;
    }

    public function getItemContent()
    {
        return '<span class="learnosity-response question-' . $this->questionReference . '"></span>';
    }
}
