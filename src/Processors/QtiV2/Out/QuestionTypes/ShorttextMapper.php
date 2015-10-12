<?php

namespace Learnosity\Processors\QtiV2\Out\QuestionTypes;

use Learnosity\Entities\BaseQuestionType;
use Learnosity\Entities\QuestionTypes\shorttext;
use Learnosity\Processors\QtiV2\Out\Validation\ShorttextValidationBuilder;
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
        $this->extraContent = $question->get_stimulus();

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
        $isCaseSensitive = $question->get_case_sensitive() === null ? true : $question->get_case_sensitive();
        $validationBuilder = new ShorttextValidationBuilder($isCaseSensitive);
        list($responseDeclaration, $responseProcessing) = $validationBuilder->buildValidation(
            $interactionIdentifier,
            $question->get_validation(),
            $isCaseSensitive
        );

        // TODO: This is a freaking hack
        // Wrap this interaction in a block since our `shorttext` meant to be blocky and not inline
        $div = new Div();
        $content = new FlowCollection();
        $content->attach($interaction);
        $div->setContent($content);
        return [$div, $responseDeclaration, $responseProcessing];
    }

    public function getExtraContent()
    {
        return $this->extraContent;
    }
}
