<?php

namespace Learnosity\Mappers\QtiV2\Import\Documentation\Interactions;

use Learnosity\Mappers\QtiV2\Import\Documentation\QtiDoc;

class TextEntryInteractionDocumentation
{
    public static function getDocumentation()
    {
        $documentation = [
            '@notes' => "
                The element 'textEntryInteraction' map to our '' question.
                Read the documentation: <a href='http://docs.learnosity.com/assessment/questions/questiontypes#clozetext'>
                http://docs.learnosity.com/assessment/questions/questiontypes#clozetext</a>
                We currently does not support validation for this item.
            ",
            '@attributes' => [
                'xmlbase'            => QtiDoc::none(),
                'id'                 => QtiDoc::none(),
                'class'              => QtiDoc::none(),
                'xmllang'            => QtiDoc::none(),
                'label'              => QtiDoc::none(),
                'responseIdentifier' => QtiDoc::none('At the moment we are not mapping this to anything. However eventually,
                                            we want to use this to map to our question `reference`'),
                'base'               => QtiDoc::none('We always assume base to be 10'),
                'stringIdentifier'   => QtiDoc::none(),
                'expectedLength'     => QtiDoc::none('We ignore this value for now. We does support `max_length` as a
                                            validity constraint'),
                'patternMask'        => QtiDoc::none(),
                'placeholderText'    => QtiDoc::none('Our `clozetext` question type does not support placeholder text')
            ]
        ];
        return $documentation;
    }
}
