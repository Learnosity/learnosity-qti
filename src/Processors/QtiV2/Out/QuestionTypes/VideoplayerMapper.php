<?php

namespace LearnosityQti\Processors\QtiV2\Out\QuestionTypes;

use LearnosityQti\Entities\BaseQuestionType;
use LearnosityQti\Entities\QuestionTypes\videoplayer;
use LearnosityQti\Processors\QtiV2\Out\Constants;
use LearnosityQti\Processors\QtiV2\Out\Validation\VideoplayerValidationBuilder;
use LearnosityQti\Utils\MimeUtil;
use qtism\data\content\interactions\MediaInteraction;
use qtism\data\content\xhtml\ObjectElement;

class VideoplayerMapper extends AbstractQuestionTypeMapper
{
    public function convert(BaseQuestionType $questionType, $interactionIdentifier, $interactionLabel)
    {
        /** @var videoplayer $question */
        $question = $questionType;
        $questionData = $question->to_array();
        $src = str_replace('/vendor/learnosity/itembank/assets/', '../'.Constants::DIRNAME_VIDEO.'/', $questionData['src']);
        

        $object = new ObjectElement($src, MimeUtil::guessMimeType($src));
        // Build final interaction and its corresponding <responseDeclaration>, and its <responseProcessingTemplate>
        $interaction = new MediaInteraction($interactionIdentifier, true, $object);
        $interaction->setAutostart(true);
        $interaction->setMinPlays(1);
        $interaction->setMaxPlays(5);
        $interaction->setLabel($interactionLabel);

        // Set loop
        $interaction->setLoop(false);
        $builder = new VideoplayerValidationBuilder();
        list($responseDeclaration, $responseProcessing) = $builder->buildValidation($interactionIdentifier, '', []);
       
        return [$interaction, $responseDeclaration, $responseProcessing];
    }
}
