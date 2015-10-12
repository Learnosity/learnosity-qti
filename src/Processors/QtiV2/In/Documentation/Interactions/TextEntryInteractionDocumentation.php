<?php

namespace Learnosity\Processors\QtiV2\In\Documentation\Interactions;

use Learnosity\Processors\QtiV2\In\Documentation\InteractionDocumentationInterface;
use Learnosity\Processors\QtiV2\In\Documentation\QtiDoc;

class TextEntryInteractionDocumentation implements InteractionDocumentationInterface
{
    public static function getInteractionDocumentation()
    {
        $documentation['@attributes'] = QtiDoc::defaultCommonInteractionAttributeRow();
        $documentation = array_replace_recursive($documentation, [
            '@notes' => "
                The element 'textEntryInteraction' map to our 'clozetext' question.
                Read the documentation: <a href='http://docs.learnosity.com/assessment/questions/questiontypes#clozetext'>
                http://docs.learnosity.com/assessment/questions/questiontypes#clozetext</a>.
                <br/><br/>
                Currently support `exactMatch` and `map_response` validation.
            ",
            '@attributes' => [
                'base'               => QtiDoc::none('We always assume base to be 10.'),
                'stringIdentifier'   => QtiDoc::none(),
                'expectedLength'     => QtiDoc::none('We ignore this value for now. We do support `max_length` as a
                                            validity constraint.'),
                'patternMask'        => QtiDoc::none(),
                'placeholderText'    => QtiDoc::none('Our `clozetext` question type does not support placeholder text.')
            ]
        ]);
        return $documentation;
    }
}
