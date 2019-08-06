<?php

namespace LearnosityQti\Processors\QtiV2\Out\QuestionTypes;

use LearnosityQti\Entities\BaseQuestionType;
use LearnosityQti\Processors\QtiV2\Out\Validation\LongtextValidationBuilder;
use LearnosityQti\Entities\QuestionTypes\longtext;
use qtism\data\content\interactions\ExtendedTextInteraction;
use qtism\data\content\interactions\TextFormat;

class LongtextMapper extends AbstractQuestionTypeMapper
{
    public function convert(BaseQuestionType $questionType, $interactionIdentifier, $interactionLabel)
    {
        /** @var longtext $question */
        $question = $questionType;
        $questionData = $question->to_array();
        
        // $feedbackOptions is used to create feedback or modelfeedback elements in responseProcessing
        $metadata = $question->get_metadata();
        $feedbackOptions = [];
        
        if (isset($metadata) && !empty($metadata->get_distractor_rationale())) {
            $feedbackOptions['genral_feedback'] = $metadata->get_distractor_rationale();
        }

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
        list($responseDeclaration, $responseProcessing) = $builder->buildValidation($interactionIdentifier, $question->get_validation(), $feedbackOptions);
        
        return [$interaction, $responseDeclaration, $responseProcessing];
    }
}
