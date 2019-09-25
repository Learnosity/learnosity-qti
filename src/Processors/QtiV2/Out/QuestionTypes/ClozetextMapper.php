<?php

namespace LearnosityQti\Processors\QtiV2\Out\QuestionTypes;

use LearnosityQti\Entities\BaseQuestionType;
use LearnosityQti\Entities\QuestionTypes\clozetext;
use LearnosityQti\Entities\QuestionTypes\clozetext_metadata;
use LearnosityQti\Processors\QtiV2\Out\ContentCollectionBuilder;
use LearnosityQti\Processors\QtiV2\Out\Validation\ClozetextValidationBuilder;
use LearnosityQti\Utils\QtiMarshallerUtil;
use qtism\data\content\interactions\TextEntryInteraction;
use qtism\data\content\FlowStaticCollection;
use qtism\data\content\FlowCollection;
use qtism\data\content\FeedbackInline;
use qtism\data\content\InlineCollection;
use qtism\data\content\TextRun;
use qtism\data\content\xhtml\text\Div;
use qtism\data\content\xhtml\text\P;

class ClozetextMapper extends AbstractQuestionTypeMapper
{
    private $extraContent;

    public function convert(BaseQuestionType $questionType, $interactionIdentifier, $interactionLabel)
    {
        /** @var clozetext $question */
        $question = $questionType;

        // Extra text that can't be mapped since we are in textEntryInteraction which does not have prompt
        $stimulus = $question->get_stimulus();
        $this->extraContent = "<div>".$stimulus."</div>";

        // Check if distractor_rationale_response_level exists
        $metadata = $question->get_metadata();
        $feedbackOptions = [];

        if(isset($metadata) && !empty($metadata->get_distractor_rationale())){
            $feedbackOptions['general_feedback'] = $metadata->get_distractor_rationale();
        }

        // Replace {{ response }} with `textEntryInteraction` elements
        $maxLength = !is_null($question->get_max_length()) ? intval($question->get_max_length()) : 15; // Set default to `15` if not set
        $index = 0;
        $template = preg_replace_callback('/{{response}}/', function ($match) use (
            &$index,
            $interactionIdentifier,
            $interactionLabel,
            $maxLength
        ) {
            $interactionIdentifier = $interactionIdentifier . '_' . $index;
            $interaction = new TextEntryInteraction($interactionIdentifier);
            $interaction->setLabel($interactionLabel);
            $interaction->setExpectedLength($maxLength);
            $index++;
            $replacement = QtiMarshallerUtil::marshall($interaction);
            return $replacement;
        }, $question->get_template());

        // Wrap this interaction in a block since our `clozetext` `template` meant to be blocky and not inline
        $interactionContent = ContentCollectionBuilder::buildFlowCollectionContent(QtiMarshallerUtil::unmarshallElement($template));
        $div = new Div();
        $div->setClass('lrn-template');
        $contentCollection = QtiMarshallerUtil::unmarshallElement($this->extraContent);
        $extracontent = ContentCollectionBuilder::buildFlowCollectionContent($contentCollection);
        $div->setContent($extracontent);
        $content = new FlowCollection();
        $content->merge($extracontent);
        $content->merge($interactionContent);
        if (isset($choiceContent)) {
            $content->merge($choiceContent);
        }
        $div->setContent($content);

        // Build validation
        $isCaseSensitive = is_null($question->get_case_sensitive()) ? true : $question->get_case_sensitive();
        $validationBuilder = new ClozetextValidationBuilder($isCaseSensitive);
        list($responseDeclaration, $responseProcessing) = $validationBuilder->buildValidation($interactionIdentifier, $question->get_validation(), $isCaseSensitive, $feedbackOptions);

        return [$div, $responseDeclaration, $responseProcessing];
    }

    public function getExtraContent()
    {
        return $this->extraContent;
    }
}
