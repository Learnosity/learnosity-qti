<?php

namespace LearnosityQti\Processors\QtiV2\Out\QuestionTypes;

use LearnosityQti\Entities\BaseQuestionType;
use LearnosityQti\Processors\QtiV2\Out\Validation\PlaintextValidationBuilder;
use LearnosityQti\Entities\QuestionTypes\plaintext;
use qtism\data\content\interactions\ExtendedTextInteraction;
use qtism\data\content\interactions\TextFormat;

class PlaintextMapper extends AbstractQuestionTypeMapper
{
    public function convert(BaseQuestionType $questionType, $interactionIdentifier, $interactionLabel)
    {
        /** @var plaintext $question */
        $question = $questionType;
        $questionData = $question->to_array();
     
        $metadata = $question->get_metadata();
        $feedbackOptions = [];
        
        if (isset($metadata) && !empty($metadata->get_distractor_rationale())) {
            $feedbackOptions['genral_feedback'] = $metadata->get_distractor_rationale();
        }
        
        $interaction = new ExtendedTextInteraction($interactionIdentifier);
        $interaction->setLabel($interactionLabel);
        $interaction->setPrompt($this->convertStimulusForPrompt($question->get_stimulus()));
        $interaction->setFormat(TextFormat::PLAIN);
        $interaction->setMinStrings(1);
        if(isset($questionData['max_length'])){
            $interaction->setExpectedLength($questionData['max_length']);
            $interaction->setMaxStrings($questionData['max_length']);
        }else{
            $interaction->setMaxStrings(1);
        }

        $placeholderText = $question->get_placeholder();
        if (!empty($placeholderText)) {
            $interaction->setPlaceholderText($placeholderText);
        }

        $builder = new PlaintextValidationBuilder();
        list($responseDeclaration,$responseProcessing) = $builder->buildValidation($interactionIdentifier, $question->get_validation(), $feedbackOptions);
        
        return [$interaction, $responseDeclaration, $responseProcessing];
    }
}
