<?php

namespace LearnosityQti\Processors\QtiV2\Out\QuestionTypes;

use LearnosityQti\Entities\BaseQuestionType;
use LearnosityQti\Processors\QtiV2\Out\Validation\LongtextValidationBuilder;
use LearnosityQti\Entities\QuestionTypes\longtextV2;
use qtism\data\content\interactions\ExtendedTextInteraction;
use qtism\data\content\interactions\TextFormat;

class LongtextV2Mapper extends AbstractQuestionTypeMapper
{
    public function convert(BaseQuestionType $questionType, $interactionIdentifier, $interactionLabel)
    {
        /** @var longtextV2 $question */
        $question = $questionType;

        $interaction = new ExtendedTextInteraction($interactionIdentifier);
        $interaction->setLabel($interactionLabel);
        $interaction->setPrompt($this->convertStimulusForPrompt($question->get_stimulus()));
        $interaction->setFormat(TextFormat::XHTML);
        $interaction->setMinStrings(1);
        $interaction->setMaxStrings(1);

        $placeholderText = $question->get_placeholder();
        if (!empty($placeholderText)) {
            $interaction->setPlaceholderText($placeholderText);
        }

        $builder = new LongtextValidationBuilder();
        
        list($responseDeclaration) = $builder->buildValidation($interactionIdentifier, $question->get_validation(),[]);
        return [$interaction, $responseDeclaration, null];
    }
}
