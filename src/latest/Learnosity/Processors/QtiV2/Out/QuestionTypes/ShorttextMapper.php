<?php

namespace Learnosity\Processors\QtiV2\Out\QuestionTypes;

use Learnosity\Entities\BaseQuestionType;
use Learnosity\Entities\QuestionTypes\shorttext;
use Learnosity\Processors\QtiV2\Out\Validation\ShorttextValidationBuilder;
use qtism\data\content\interactions\ExtendedTextInteraction;
use qtism\data\content\interactions\TextFormat;

class ShorttextMapper extends AbstractQuestionTypeMapper
{
    public function convert(BaseQuestionType $questionType, $interactionIdentifier, $interactionLabel)
    {
        /** @var shorttext $question */
        $question = $questionType;

        $interaction = new ExtendedTextInteraction($interactionIdentifier);
        $interaction->setLabel($interactionLabel);

        // Build the prompt
        $interaction->setPrompt($this->convertStimulusForPrompt($question->get_stimulus()));

        // Build placeholder
        $placeholderText = $question->get_placeholder();
        if (!empty($placeholderText)) {
            $interaction->setPlaceholderText($placeholderText);
        }

        $interaction->setMaxStrings(1);
        $interaction->setMinStrings(1);
        $interaction->setFormat(TextFormat::PLAIN);

        // This is derived since the maximum length of a `shorttext` is 250 characters and the fact that
        // the shape of a `shorttext` input imply the expected response is a single line
        $interaction->setExpectedLength(250);
        $interaction->setExpectedLines(1);

        // Build those validation
        $validationBuilder = new ShorttextValidationBuilder();
        list($responseDeclaration, $responseProcessing) = $validationBuilder->buildValidation(
            $interactionIdentifier,
            $question->get_validation(),
            $question->get_case_sensitive()
        );

        return [$interaction, $responseDeclaration, $responseProcessing];
    }
}
