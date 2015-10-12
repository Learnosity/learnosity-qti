<?php

namespace LearnosityQti\Processors\QtiV2\Out\Documentation\QuestionTypes;

use LearnosityQti\Processors\QtiV2\Out\Documentation\LearnosityDoc;
use LearnosityQti\Processors\QtiV2\Out\Documentation\QuestionTypeDocumentationInterface;

class ClozedropdownDocumentation implements QuestionTypeDocumentationInterface
{
    public static function getDocumentation()
    {
        return [
            'stimulus' => LearnosityDoc::support('Since InlineChoiceInteraction is inline and does not have prompt, this `stimulus` is then prepended to the itembody content'),
            'template' => LearnosityDoc::support('This is mapped to the interaction content, the {{response}} would be replaced with `inlineChoiceInteraction` elements')
        ];
    }

    public static function getIntroductionNotes()
    {
        return
            "This question type is mapped to multiple <inlineChoiceInteraction> for each {{response}} at `template` attribute. Currently only support `exactMatch` validation. Response processing would by default mapped using `match_correct` template.";
    }
}
