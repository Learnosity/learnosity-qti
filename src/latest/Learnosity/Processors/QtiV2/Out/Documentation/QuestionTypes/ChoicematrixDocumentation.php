<?php

namespace latest\Learnosity\Processors\QtiV2\Out\Documentation\QuestionTypes;

use Learnosity\Processors\QtiV2\Out\Documentation\LearnosityDoc;

class ChoicematrixDocumentation
{
    public static function getDocumentation()
    {
        return [
            'stimulus' => LearnosityDoc::support('This is mapped to `prompt`')
        ];
    }

    public static function getIntroductionNotes()
    {
        return
            "This question type is mapped to <matchInteraction> and only `exactMatch` validation can be supported";
    }
}
