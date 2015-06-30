<?php

namespace Learnosity\Mappers\QtiV2\Import\MergedInteractions;

use Learnosity\Entities\QuestionTypes\clozedropdown;
use Learnosity\Exceptions\MappingException;
use Learnosity\Mappers\QtiV2\Import\Utils\QtiComponentUtil;
use Learnosity\Mappers\QtiV2\Import\Validation\InlineChoiceInteractionValidationBuilder;
use qtism\data\content\interactions\InlineChoice;
use qtism\data\content\interactions\Interaction;
use qtism\data\content\ItemBody;

class MergedInlineChoiceInteraction extends AbstractMergedInteraction
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

        $validationBuilder = new InlineChoiceInteractionValidationBuilder(
            $possibleResponsesMap,
            $this->responseDeclarations,
            $this->responseProcessingTemplate
        );
        $validation = $validationBuilder->getValidation();
        if (!empty($validation)) {
            $clozedropdown->set_validation($validation);
        }

        $isCaseSensitive = $validationBuilder->isCaseSensitive();
        $clozedropdown->set_case_sensitive($isCaseSensitive);
        if ($isCaseSensitive) {
            $this->exceptions[] = new MappingException('Partial `caseSensitive` per response is not supported.
                Thus setting all validation as case sensitive');
        }

        $this->exceptions = array_merge($this->exceptions, $validationBuilder->getExceptions());
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
}
