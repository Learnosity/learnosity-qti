<?php

namespace Learnosity\Processors\QtiV2\Out\QuestionTypes;

use Learnosity\Entities\BaseQuestionType;
use Learnosity\Entities\QuestionTypes\clozedropdown;
use Learnosity\Exceptions\MappingException;
use Learnosity\Processors\QtiV2\Out\ContentCollectionBuilder;
use Learnosity\Processors\QtiV2\Out\Validation\ClozedropdownValidationBuilder;
use Learnosity\Utils\QtiMarshallerUtil;
use qtism\data\content\interactions\InlineChoice;
use qtism\data\content\interactions\InlineChoiceCollection;
use qtism\data\content\interactions\InlineChoiceInteraction;
use qtism\data\content\TextOrVariableCollection;
use qtism\data\content\TextRun;
use qtism\data\content\xhtml\text\Div;

class ClozedropdownMapper extends AbstractQuestionTypeMapper
{
    private $extraContent;

    public function convert(BaseQuestionType $questionType, $interactionIdentifier, $interactionLabel)
    {
        /** @var clozedropdown $question */
        $question = $questionType;

        // Extra text that can't be mapped since we are in textEntryInteraction which does not have prompt
        $this->extraContent = $question->get_stimulus();

        // Replace {{ response }} with `textEntryInteraction` elements
        $valueIdentifierMapPerInlineChoices = [];
        $index = 0;
        $possibleResponses = $question->get_possible_responses();
        $template = preg_replace_callback('/{{response}}/', function ($match) use (
            &$index,
            &$valueIdentifierMapPerInlineChoices,
            $possibleResponses,
            $interactionIdentifier,
            $interactionLabel
        ) {
            $inlineChoiceCollection = new InlineChoiceCollection();
            if (!isset($possibleResponses[$index])) {
                throw new MappingException('Invalid `possible_responses`, missing entries');
            }
            foreach ($possibleResponses[$index] as $choiceIndex => $choiceValue) {
                $inlineChoiceIdentifier = 'INLINECHOICE_' . $choiceIndex;
                $valueIdentifierMapPerInlineChoices[$index][$choiceValue] = $inlineChoiceIdentifier; // Update this map so can be used later upon building responseDeclaration objects
                $inlineChoice = new InlineChoice($inlineChoiceIdentifier);
                $inlineChoiceContent = new TextOrVariableCollection();
                $inlineChoiceContent->attach(new TextRun($choiceValue));
                $inlineChoice->setContent($inlineChoiceContent);
                $inlineChoiceCollection->attach($inlineChoice);
            }
            $interaction = new InlineChoiceInteraction($interactionIdentifier . '_' . $index, $inlineChoiceCollection);
            $interaction->setLabel($interactionLabel);
            $index++;
            $replacement = QtiMarshallerUtil::marshall($interaction);
            return $replacement;
        }, $question->get_template());

        // Wrap this interaction in a block since our `clozedropdown` `template` meant to be blocky and not inline
        $div = new Div();
        $div->setClass('lrn-template');
        $div->setContent(ContentCollectionBuilder::buildFlowCollectionContent(QtiMarshallerUtil::unmarshallElement($template)));

        // Build validation
        $validationBuilder = new ClozedropdownValidationBuilder($valueIdentifierMapPerInlineChoices);
        list($responseDeclaration, $responseProcessing) = $validationBuilder->buildValidation($interactionIdentifier, $question->get_validation());

        return [$div, $responseDeclaration, $responseProcessing];
    }

    public function getExtraContent()
    {
        return $this->extraContent;
    }
}
