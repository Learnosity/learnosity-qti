<?php

namespace LearnosityQti\Processors\QtiV2\Out\QuestionTypes;

use LearnosityQti\Entities\BaseQuestionType;
use LearnosityQti\Entities\QuestionTypes\shorttext;
use LearnosityQti\Processors\QtiV2\Out\ContentCollectionBuilder;
use LearnosityQti\Processors\QtiV2\Out\Validation\ShorttextValidationBuilder;
use LearnosityQti\Utils\QtiMarshallerUtil;
use qtism\data\content\FlowCollection;
use qtism\data\content\interactions\TextEntryInteraction;
use qtism\data\content\xhtml\text\Div;

class ShorttextMapper extends AbstractQuestionTypeMapper
{
    private $extraContent;

    public function convert(BaseQuestionType $questionType, $interactionIdentifier, $interactionLabel)
    {
        /** @var shorttext $question */
        $question = $questionType;

        // Extra text that can't be mapped since we are in textEntryInteraction which does not have prompt
        $stimulus = $question->get_stimulus();
        $this->extraContent = "<div>" . $stimulus . "</div>";

        $metadata = $question->get_metadata();
        $feedbackOptions = [];

        if (isset($metadata) && !empty($metadata->get_distractor_rationale())) {
            $feedbackOptions['general_feedback'] = $metadata->get_distractor_rationale();
        }

        $interaction = new TextEntryInteraction($interactionIdentifier);
        $interaction->setLabel($interactionLabel);

        // Build placeholder
        $placeholderText = $question->get_placeholder();
        if (!empty($placeholderText)) {
            $interaction->setPlaceholderText($placeholderText);
        }

        // Use 15 as default
        $interaction->setExpectedLength($question->get_max_length() ? $question->get_max_length() : 15);

        // Build those validation
        $isCaseSensitive = $question->get_case_sensitive() === null ? false : $question->get_case_sensitive();
        $validationBuilder = new ShorttextValidationBuilder($isCaseSensitive);
        list($responseDeclaration, $responseProcessing) = $validationBuilder->buildValidation($interactionIdentifier, $question->get_validation(), $isCaseSensitive, $feedbackOptions);

        // TODO: This is a freaking hack
        // Wrap this interaction in a block since our `shorttext` meant to be blocky and not inline
        $div = new Div();
        $contentCollection = QtiMarshallerUtil::unmarshallElement($this->extraContent);
        $extracontent = ContentCollectionBuilder::buildFlowCollectionContent($contentCollection); 
        $div->setContent($extracontent);
        $content = new FlowCollection();
        $content->merge($extracontent);
        $content->attach($interaction);
        $div->setContent($content);
        return [$div, $responseDeclaration, $responseProcessing];
    }

    public function getExtraContent()
    {
        return $this->extraContent;
    }
}
