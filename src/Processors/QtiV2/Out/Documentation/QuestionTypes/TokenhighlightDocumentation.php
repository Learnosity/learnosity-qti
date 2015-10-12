<?php

namespace Learnosity\Processors\QtiV2\Out\Documentation\QuestionTypes;

use Learnosity\Processors\QtiV2\Out\Documentation\LearnosityDoc;
use Learnosity\Processors\QtiV2\Out\Documentation\QuestionTypeDocumentationInterface;

class TokenhighlightDocumentation implements QuestionTypeDocumentationInterface
{
    public static function getDocumentation()
    {
        return [
            'stimulus' => LearnosityDoc::support('This is mapped to `prompt`'),
            'template' => LearnosityDoc::support('This is mapped to the Hottext interaction content'),
            'max_selection' => LearnosityDoc::support('This is mapped to the interaction `maxChoices`')
        ];
    }

    public static function getIntroductionNotes()
    {
        return
            "This question type is mapped to <hottextInteraction> and currently only support `exactMatch` validation. ' .
            'Response processing would by default mapped using `match_correct` template.";
    }
}
