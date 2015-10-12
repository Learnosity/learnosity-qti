<?php

namespace Learnosity\Processors\QtiV2\Out\Documentation\QuestionTypes;

use Learnosity\Processors\QtiV2\Out\Documentation\LearnosityDoc;
use Learnosity\Processors\QtiV2\Out\Documentation\QuestionTypeDocumentationInterface;

class ImageclozeassociationDocumentation implements QuestionTypeDocumentationInterface
{
    public static function getDocumentation()
    {
        return [
            'stimulus' => LearnosityDoc::support('This is mapped to `prompt`'),
            'image.src' => LearnosityDoc::support('This is mapped to the interaction background image `object`'),
            'possible_responses' => LearnosityDoc::support('These are mapped to `gapImg`(s). Texts would be converted to base64 image `object`'),
            'response_positions' => LearnosityDoc::support('These are mapped to `associableHostpot`(s)')
        ];
    }

    public static function getIntroductionNotes()
    {
        return
            "This question type is mapped to <graphicGapMatchInteraction> and only `exactMatch` validation can be supported. Response processing would by default mapped using `match_correct` template.";
    }
}
