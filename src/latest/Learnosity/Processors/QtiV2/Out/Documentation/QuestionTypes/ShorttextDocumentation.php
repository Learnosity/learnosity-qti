<?php

namespace Learnosity\Processors\QtiV2\Out\Documentation\QuestionTypes;

use Learnosity\Processors\QtiV2\Out\Documentation\LearnosityDoc;
use Learnosity\Processors\QtiV2\Out\Documentation\QuestionTypeDocumentationInterface;

class ShorttextDocumentation implements QuestionTypeDocumentationInterface
{
    public static function getDocumentation()
    {
        return [
            'stimulus' => LearnosityDoc::support('Since textEntryInteraction is inline and does not have prompt, this `stimulus` is then prepended to the itembody content'),
            'placeholder' => LearnosityDoc::support('This is mapped to the Hottext interaction `placeholder` attribute'),
            'max_length' => LearnosityDoc::support('This is mapped to the Hottext interaction `expectedLength` attribute, if not set then defaulted to 15'),
            'case_sensitive' => LearnosityDoc::support('This is mapped to each `mapEntry` case sensitive attribute at `mapping`')
        ];
    }

    public static function getIntroductionNotes()
    {
        return
            "This question type is mapped to <textEntryInteraction>";
    }
}
