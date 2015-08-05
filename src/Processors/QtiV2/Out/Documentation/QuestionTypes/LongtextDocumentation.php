<?php

namespace Learnosity\Processors\QtiV2\Out\Documentation\QuestionTypes;

use Learnosity\Processors\QtiV2\Out\Documentation\LearnosityDoc;
use Learnosity\Processors\QtiV2\Out\Documentation\QuestionTypeDocumentationInterface;

class LongtextDocumentation implements QuestionTypeDocumentationInterface
{
    public static function getDocumentation()
    {
        return [
            'stimulus' => LearnosityDoc::support('This is mapped to `prompt`'),
            'placeholder' => LearnosityDoc::support('This is mapped to the interaction `placeholder` attribute')
        ];
    }

    public static function getIntroductionNotes()
    {
        return
            "This question type is mapped to <extendedTextEntryInteraction> with `<strong>format</strong>` attribute as `xhtml`. " .
            "Both the interaction attribute `<strong>minString</strong>` and `<strong>maxString</strong>` is mapped to 1." .
            "Validation is not supported thus <responseProcessing> and <responseDeclaration> won't be populated.";
    }
}
