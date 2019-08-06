<?php

namespace LearnosityQti\Processors\QtiV2\Out\QuestionTypes;

use LearnosityQti\Entities\BaseQuestionType;
use LearnosityQti\Processors\QtiV2\Out\Validation\AudioplayerValidationBuilder;
use LearnosityQti\Utils\MimeUtil;
use LearnosityQti\Processors\QtiV2\Out\Constants;
use qtism\data\content\interactions\MediaInteraction;
use qtism\data\content\xhtml\ObjectElement;

class AudioplayerMapper extends AbstractQuestionTypeMapper
{
    public function convert(BaseQuestionType $questionType, $interactionIdentifier, $interactionLabel)
    {
        /** @var audioplayer $question */
        $question = $questionType;
        $questionData = $question->to_array();
        $src = str_replace('/vendor/learnosity/itembank/assets/', '../'.Constants::DIRNAME_AUDIO.'/', $questionData['src']);
        
        $object = new ObjectElement($src, MimeUtil::guessMimeType($src));
        // Build final interaction and its corresponding <responseDeclaration>, and its <responseProcessingTemplate>
        $interaction = new MediaInteraction($interactionIdentifier, true, $object);
        $interaction->setAutostart(true);
        $interaction->setMinPlays(1);
        if(isset($questionData['playback_limit'])) {
            $interaction->setMaxPlays($questionData['playback_limit']);
        }
        $interaction->setLabel($interactionLabel);

        // Set loop
        $interaction->setLoop(true);
        $builder = new AudioplayerValidationBuilder();
        list($responseDeclaration, $responseProcessing) = $builder->buildValidation($interactionIdentifier, '', []);
       
        return [$interaction, $responseDeclaration, $responseProcessing];
    }
}
