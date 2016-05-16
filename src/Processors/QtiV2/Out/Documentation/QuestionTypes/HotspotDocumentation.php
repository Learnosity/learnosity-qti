<?php

namespace LearnosityQti\Processors\QtiV2\Out\Documentation\QuestionTypes;

use LearnosityQti\Processors\QtiV2\Out\Documentation\LearnosityDoc;
use LearnosityQti\Processors\QtiV2\Out\Documentation\QuestionTypeDocumentationInterface;

class HotspotDocumentation implements QuestionTypeDocumentationInterface
{
    public static function getDocumentation()
    {
        return [
            'stimulus' => LearnosityDoc::support('This is mapped to `prompt`'),
            'options' => LearnosityDoc::support('This is mapped to list of simpleChoice'),
            'image' => LearnosityDoc::support('This is mapped to the image object width'),
            'image.source' => LearnosityDoc::support('This is mapped to interaction image object, we will use its extension to map it to its type,'
                . 'ie. `hello.png` would be assumed to have image type of `image/png`'),
            'image.width' => LearnosityDoc::support('This is mapped to the image object width'),
            'image.height' => LearnosityDoc::support('This is mapped to the image object height'),
            'areas' => LearnosityDoc::support('This is mapped to a collection of hotspotChoice. Each hotspotChoice would be of a shape Poly and '
                . 'its percentage-based coordinates would be mapped to QTI fixed-based coordinates'),
            'multiple_responses' => LearnosityDoc::support('When this option sets to true, then it would map the interaction ' .
                '`maxChoices` attribute with the highest number of choices possible')
        ];
    }

    public static function getIntroductionNotes()
    {
        return
            "This question type is mapped to <choiceInteraction> and currently only support `exactMatch` validation. Response processing would by default mapped using `match_correct` template.";
    }
}
