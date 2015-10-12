<?php

namespace LearnosityQti\Processors\QtiV2\Out\Documentation\QuestionTypes;

use LearnosityQti\Processors\QtiV2\Out\Documentation\LearnosityDoc;
use LearnosityQti\Processors\QtiV2\Out\Documentation\QuestionTypeDocumentationInterface;

class PlaintextDocumentation implements QuestionTypeDocumentationInterface
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
            "This question type is mapped to <extendedTextEntryInteraction> with `format` attribute as `plain`. " .
            "Both the interaction attribute `minString` and `maxString` is mapped to 1." .
            "Validation is not supported thus <responseProcessing> and <responseDeclaration> won't be populated.";
    }
}
