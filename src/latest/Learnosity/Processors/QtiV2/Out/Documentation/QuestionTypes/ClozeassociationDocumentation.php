<?php

namespace Learnosity\Processors\QtiV2\Out\Documentation\QuestionTypes;

use Learnosity\Processors\QtiV2\Out\Documentation\LearnosityDoc;
use Learnosity\Processors\QtiV2\Out\Documentation\QuestionTypeDocumentationInterface;

class ClozeassociationDocumentation implements QuestionTypeDocumentationInterface
{
    public static function getDocumentation()
    {
        return [
            'stimulus' => LearnosityDoc::support('This is mapped to `prompt`'),
            'template' => LearnosityDoc::support('This is mapped to the interaction content, the {{response}} would be replaced with `gap` elements'),
            'possible_responses' => LearnosityDoc::support('This is mapped to the interaction gap choices, would be mapped as either `gapText` or `gapImg`'),
            'duplicate_responses' => LearnosityDoc::support('When this option sets to true, then it would map the interaction ' .
                '`matchMax` attribute with the highest number of choices possibles')
        ];
    }

    public static function getIntroductionNotes()
    {
        return
            "This question type is mapped to <gapMatchInteraction> and only `exactMatch` validation can be supported. Response processing would by default mapped using `match_correct` template.";
    }
}
