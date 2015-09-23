<?php

namespace Learnosity\Processors\QtiV2\Out\Documentation\QuestionTypes;

use Learnosity\Processors\QtiV2\Out\Documentation\LearnosityDoc;
use Learnosity\Processors\QtiV2\Out\Documentation\QuestionTypeDocumentationInterface;

class ChoicematrixDocumentation implements QuestionTypeDocumentationInterface
{
    public static function getDocumentation()
    {
        return [
            'stimulus' => LearnosityDoc::support('This is mapped to `prompt`'),
            'options' => LearnosityDoc::support('This is mapped to `target` collection for QTI `simpleMatchSet'),
            'stems' => LearnosityDoc::support('This is mapped to `source` collection for QTI `simpleMatchSet`'),
            'multiple_responses' => LearnosityDoc::support('When this option sets to true, then it would map the interaction ' .
            '`maxAssociation` attribute with the highest number of association possible. It also map the `source` (stems) collection to have highest `matchMax` possible')
        ];
    }

    public static function getIntroductionNotes()
    {
        return
            "This question type is mapped to <matchInteraction> and only `exactMatch` validation can be supported. Response processing would by default mapped using `match_correct` template.";
    }
}
