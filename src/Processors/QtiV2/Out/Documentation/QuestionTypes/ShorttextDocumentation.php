<?php

namespace LearnosityQti\Processors\QtiV2\Out\Documentation\QuestionTypes;

use LearnosityQti\Processors\QtiV2\Out\Documentation\LearnosityDoc;
use LearnosityQti\Processors\QtiV2\Out\Documentation\QuestionTypeDocumentationInterface;

class ShorttextDocumentation implements QuestionTypeDocumentationInterface
{
    public static function getDocumentation()
    {
        return [
            'stimulus' => LearnosityDoc::support('Since TextEntryInteraction is inline and does not have prompt, this `stimulus` is then prepended to the itemBody content'),
            'placeholder' => LearnosityDoc::support('This is mapped to the TextEntryInteraction interaction `placeholder` attribute'),
            'max_length' => LearnosityDoc::support('This is mapped to the TextEntryInteraction interaction `expectedLength` attribute, if not set then defaulted to 15'),
            'case_sensitive' => LearnosityDoc::support('This is mapped to each `mapEntry` case sensitive attribute at `mapping`')
        ];
    }

    public static function getIntroductionNotes()
    {
        return
            "This question type is mapped to <textEntryInteraction> and currently only support `exactMatch` validation. " .
            "Response processing would by default mapped using `map_response` template.";
    }
}
