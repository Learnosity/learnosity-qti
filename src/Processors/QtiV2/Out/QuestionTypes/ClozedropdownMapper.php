<?php

namespace LearnosityQti\Processors\QtiV2\Out\QuestionTypes;

use LearnosityQti\Entities\BaseQuestionType;
use LearnosityQti\Entities\QuestionTypes\clozedropdown;
use LearnosityQti\Exceptions\MappingException;
use LearnosityQti\Processors\QtiV2\Out\ContentCollectionBuilder;
use LearnosityQti\Processors\QtiV2\Out\Validation\ClozedropdownValidationBuilder;
use LearnosityQti\Utils\QtiMarshallerUtil;
use qtism\data\content\interactions\InlineChoice;
use qtism\data\content\interactions\InlineChoiceCollection;
use qtism\data\content\interactions\InlineChoiceInteraction;
use qtism\data\content\TextOrVariableCollection;
use qtism\data\content\FlowStaticCollection;
use qtism\data\content\FlowCollection;
use qtism\data\content\FeedbackInline;
use qtism\data\content\InlineCollection;
use qtism\data\content\TextRun;
use qtism\data\content\xhtml\text\Div;
use qtism\data\content\xhtml\text\P;

class ClozedropdownMapper extends AbstractQuestionTypeMapper
{
    private $extraContent;

    public function convert(BaseQuestionType $questionType, $interactionIdentifier, $interactionLabel)
    {
        /** @var clozedropdown $question */
        $question = $questionType;

        // Extra text that can't be mapped since we are in textEntryInteraction which does not have prompt
        $this->extraContent = $question->get_stimulus();
        
        // Check if distractor_rationale_response_level exists
        $feedbackOptions = [];
        $metadata = $question->get_metadata();
        if(isset($metadata)){
           
            $feedbackOptions = $metadata->get_distractor_rationale_response_level();
            if(!empty($feedbackOptions)){
                $p = new P();
                $choiceContent = new FlowCollection();
                $i = 1;
                foreach($feedbackOptions as $feedback){
                    $content = new InlineCollection(array(new TextRun($feedback)));
                    $feedbackinline = new FeedbackInline('FEEDBACK','IDENTIFIER_'.$i,'true');
                    $feedbackinline->setContent($content);
                    $choiceContent->attach($feedbackinline);
                    $i++;
                }
            }

            if(!empty($metadata->get_distractor_rationale())){
                $feedbackOptions['genral_feedback'] = $metadata->get_distractor_rationale();
            }
        }

        // Replace {{ response }} with `inlineInteraction` elements
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
        $interactionContent = ContentCollectionBuilder::buildFlowCollectionContent(QtiMarshallerUtil::unmarshallElement($template));
        $div = new Div();
        $div->setClass('lrn-template');
        $contentCollection = QtiMarshallerUtil::unmarshallElement($this->extraContent);
        $extracontent = ContentCollectionBuilder::buildFlowCollectionContent($contentCollection); 
        $div->setContent($extracontent);
        
        $content = new FlowCollection();
        $content->merge($extracontent);
        $content->merge($interactionContent);
        if(isset($choiceContent)){
            $content->merge($choiceContent);
        }
        $div->setContent($content);
        // Build validation
        $validationBuilder = new ClozedropdownValidationBuilder($valueIdentifierMapPerInlineChoices);
        list($responseDeclaration, $responseProcessing) = $validationBuilder->buildValidation($interactionIdentifier, $question->get_validation(), $feedbackOptions);

        return [$div, $responseDeclaration, $responseProcessing];
    }

    public function getExtraContent()
    {
        return $this->extraContent;
    }
}
