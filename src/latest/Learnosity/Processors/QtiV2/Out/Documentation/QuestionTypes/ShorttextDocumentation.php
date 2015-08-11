<?php

namespace Learnosity\Processors\QtiV2\Out\Documentation\QuestionTypes;

use Learnosity\Processors\QtiV2\Out\Documentation\QuestionTypeDocumentationInterface;

class ShorttextDocumentation implements QuestionTypeDocumentationInterface
{
    public static function getDocumentation()
    {
        return [];
    }

    public static function getIntroductionNotes()
    {
        return "";
    }
}
