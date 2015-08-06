<?php

namespace Learnosity\Processors\QtiV2\In\Documentation\Interactions;

use Learnosity\Processors\QtiV2\In\Documentation\InteractionDocumentationInterface;
use Learnosity\Processors\QtiV2\In\Documentation\QtiDoc;

class ExtendedTextInteractionDocumentation implements InteractionDocumentationInterface
{
    public static function getInteractionDocumentation()
    {
        $documentation['@attributes'] = QtiDoc::defaultCommonInteractionAttributeRow();
        $documentation = array_replace_recursive($documentation, [
            '@notes' => "
                The element 'extendedTextInteraction' map to our 'longtext' question.
                Read the documentation: <a href='http://docs.learnosity.com/assessment/questions/questiontypes#longtext'>
                http://docs.learnosity.com/assessment/questions/questiontypes#longtext</a>.
            ",
            '@attributes' => [
                'base'               => QtiDoc::none('We always assume base to be 10.'),
                'stringIdentifier'   => QtiDoc::none(),
                'expectedLength'     => QtiDoc::support('The number defined is used to determine `max_length` with the assumption of
                                            5 characters per sentence and set `submit_over_limit` to
                                            `true` since it is not a validity constraint.'),
                'patternMask'        => QtiDoc::none(),
                'placeholderText'    => QtiDoc::support(),
                'maxStrings'         => QtiDoc::none('Always assumed to be 1. `longtext` question type does not support multiple
                                            separate strings.'),
                'minStrings'         => QtiDoc::none('Always assumed to be 1.'),
                'expectedLines'      => QtiDoc::none(),
                'format'             => QtiDoc::none('We capture response as plain text.')
            ],
            'prompt' => QtiDoc::support('We map this to our question `stimulus`.')
        ]);
        return $documentation;
    }
}
