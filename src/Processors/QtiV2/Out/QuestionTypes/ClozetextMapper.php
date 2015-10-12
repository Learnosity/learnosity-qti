<?php

namespace LearnosityQti\Processors\QtiV2\Out\QuestionTypes;

use LearnosityQti\Entities\BaseQuestionType;
use LearnosityQti\Entities\QuestionTypes\clozetext;
use LearnosityQti\Processors\QtiV2\Out\ContentCollectionBuilder;
use LearnosityQti\Processors\QtiV2\Out\Validation\ClozetextValidationBuilder;
use LearnosityQti\Utils\QtiMarshallerUtil;
use qtism\data\content\interactions\TextEntryInteraction;
use qtism\data\content\xhtml\text\Div;

class ClozetextMapper extends AbstractQuestionTypeMapper
{
    private $extraContent;

    public function convert(BaseQuestionType $questionType, $interactionIdentifier, $interactionLabel)
    {
        /** @var clozetext $question */
        $question = $questionType;

        // Extra text that can't be mapped since we are in textEntryInteraction which does not have prompt
        $this->extraContent = $question->get_stimulus();

        // Replace {{ response }} with `textEntryInteraction` elements
        $maxLength = !is_null($question->get_max_length()) ? intval($question->get_max_length()) : 15; // Set default to `15` if not set
        $index = 0;
        $template = preg_replace_callback('/{{response}}/', function ($match) use (
            &$index,
            $interactionIdentifier,
            $interactionLabel,
            $maxLength
        ) {
            $interaction = new TextEntryInteraction($interactionIdentifier . '_' . $index);
            $interaction->setLabel($interactionLabel);
            $interaction->setExpectedLength($maxLength);
            $index++;
            $replacement = QtiMarshallerUtil::marshall($interaction);
            return $replacement;
        }, $question->get_template());

        // Wrap this interaction in a block since our `clozetext` `template` meant to be blocky and not inline
        $div = new Div();
        $div->setClass('lrn-template');
        $div->setContent(ContentCollectionBuilder::buildFlowCollectionContent(QtiMarshallerUtil::unmarshallElement($template)));

        // Build validation
        $isCaseSensitive = is_null($question->get_case_sensitive()) ? true : $question->get_case_sensitive();
        $validationBuilder = new ClozetextValidationBuilder($isCaseSensitive);
        list($responseDeclaration, $responseProcessing) = $validationBuilder->buildValidation($interactionIdentifier, $question->get_validation(), $isCaseSensitive);

        return [$div, $responseDeclaration, $responseProcessing];
    }

    public function getExtraContent()
    {
        return $this->extraContent;
    }
}
