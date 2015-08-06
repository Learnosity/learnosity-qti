<?php

namespace Learnosity\Processors\QtiV2\In\Documentation\Interactions;

use Learnosity\Processors\QtiV2\In\Documentation\InteractionDocumentationInterface;
use Learnosity\Processors\QtiV2\In\Documentation\QtiDoc;

class HottextInteractionDocumentation implements InteractionDocumentationInterface
{
    public static function getInteractionDocumentation()
    {
        $documentation['@attributes'] = QtiDoc::defaultCommonInteractionAttributeRow();
        $documentation = array_replace_recursive($documentation, [
            '@notes' => "
                The element 'hottextInteraction' map to our 'tokenhighlight' question.
                Read the documentation: <a href='http://docs.learnosity.com/assessment/questions/questiontypes#tokenhighlight'>
                http://docs.learnosity.com/assessment/questions/questiontypes#tokenhighlight</a>
                <br /><br />
                Currently support `exactMatch` and `map_response` validation.
            ",
            '@attributes' => [
                'maxChoices'         => QtiDoc::support('This map to `max_selection`'),
                'minChoices'         => QtiDoc::none('By default this would be zero since we allow no response.')
            ],
            'prompt' => QtiDoc::support('We map this to our question `stimulus`.'),
        ]);
        $documentation = array_merge($documentation, QtiDoc::defaultBlockStaticRow());
        return $documentation;
    }
}
