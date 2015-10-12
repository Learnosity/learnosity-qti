<?php

namespace LearnosityQti\Processors\QtiV2\Out\Documentation\QuestionTypes;

use LearnosityQti\Processors\QtiV2\Out\Documentation\LearnosityDoc;
use LearnosityQti\Processors\QtiV2\Out\Documentation\QuestionTypeDocumentationInterface;

class McqDocumentation implements QuestionTypeDocumentationInterface
{
    public static function getDocumentation()
    {
        return [
            'stimulus' => LearnosityDoc::support('This is mapped to `prompt`'),
            'options' => LearnosityDoc::support('This is mapped to list of simpleChoice'),
            'options[].value' => LearnosityDoc::support('This is mapped to `simpleChoice` @identifier'),
            'options[].label' => LearnosityDoc::support('This is mapped to `simpleChoice` value'),
            'shuffle_options' => LearnosityDoc::support('Yes this is mapped to shuffle at `orderInteraction`'),
        ];
    }

    public static function getIntroductionNotes()
    {
        return
            "This question type is mapped to <choiceInteraction> and currently only support `exactMatch` validation. Response processing would by default mapped using `match_correct` template.";
    }
}
