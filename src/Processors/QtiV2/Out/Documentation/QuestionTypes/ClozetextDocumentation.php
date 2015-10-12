<?php

namespace Learnosity\Processors\QtiV2\Out\Documentation\QuestionTypes;

use Learnosity\Processors\QtiV2\Out\Documentation\LearnosityDoc;
use Learnosity\Processors\QtiV2\Out\Documentation\QuestionTypeDocumentationInterface;

class ClozetextDocumentation implements QuestionTypeDocumentationInterface
{
    public static function getDocumentation()
    {
        return [
            'stimulus' => LearnosityDoc::support('Since TextEntryInteraction is inline and does not have prompt, this `stimulus` is then prepended to the itembody content'),
            'max_length' => LearnosityDoc::support('This is mapped to the TextEntryInteraction interaction `expectedLength` attribute, if not set then defaulted to 15'),
            'template' => LearnosityDoc::support('This is mapped to the interaction content, the {{response}} would be replaced with `textEntryInteraction` elements'),
            'case_sensitive' => LearnosityDoc::support('This is mapped to each `mapEntry` case sensitive attribute at `mapping`')
        ];
    }

    public static function getIntroductionNotes()
    {
        return
            "This question type is mapped to multiple <textEntryInteraction> for each {{response}} at `template` attribute. Currently only support `exactMatch` validation. Response processing would by default mapped using `map_response` template.";
    }
}
