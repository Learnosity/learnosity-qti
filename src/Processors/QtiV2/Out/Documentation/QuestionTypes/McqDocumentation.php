<?php

namespace Learnosity\Processors\QtiV2\Out\Documentation\QuestionTypes;

use Learnosity\Processors\QtiV2\Out\Documentation\LearnosityDoc;
use Learnosity\Processors\QtiV2\Out\Documentation\QuestionTypeDocumentationInterface;

class McqDocumentation implements QuestionTypeDocumentationInterface
{
    public static function getDocumentation()
    {
        return [
            'stimulus' => LearnosityDoc::support('This is mapped to `prompt`')
        ];
    }
}
