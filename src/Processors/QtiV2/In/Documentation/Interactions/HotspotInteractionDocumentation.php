<?php

namespace LearnosityQti\Processors\QtiV2\In\Documentation\Interactions;

use LearnosityQti\Processors\QtiV2\In\Documentation\InteractionDocumentationInterface;
use LearnosityQti\Processors\QtiV2\In\Documentation\QtiDoc;

class HotspotInteractionDocumentation implements InteractionDocumentationInterface
{
    public static function getInteractionDocumentation()
    {
        $documentation['@attributes'] = QtiDoc::defaultCommonInteractionAttributeRow();
        $documentation = array_replace_recursive($documentation, [
            '@notes' => "
                The element 'hotspotInteraction' map to our 'hotspot' question.
                Read the documentation: <a href='http://docs.learnosity.com/assessment/questions/questiontypes#hotspot'>
                http://docs.learnosity.com/assessment/questions/questiontypes#hotspot</a>
                <br /><br />
                Currently only support `exactMatch` validation.
            ",
            '@attributes' => [
                'maxChoices'         => QtiDoc::partial('Learnosity does not support specifying the count of choices. If this value
                                            is more than one, then mcq `multiple_responses` is set to true.'),
                'minChoices'         => QtiDoc::none('By default this would be zero since we allow no response.'),
            ],
            'prompt' => QtiDoc::support('We map this to our question `stimulus`.'),
            'object' => QtiDoc::support('We map this to the background image. MIME type should be set correctly. Say "image/png"'),
            'hotspotChoice' => [
                '@notes' => 'Attributes are ignored and coordinates are converted to percentage based so they can be mapped to
                    `x`(s) and `y`(s) in `areas`',
                '@attributes' => [
                    'id' => QtiDoc::none(),
                    'class' => QtiDoc::none(),
                    'xmllang' => QtiDoc::none(),
                    'label' => QtiDoc::none(),
                    'identifier' => QtiDoc::none(),
                    'fixed' => QtiDoc::none('We do not support partial shuffle.'),
                    'templateIdentifier' => QtiDoc::none(),
                    'showHide' => QtiDoc::none(),
                    'shape' => QtiDoc::partial('Rectangle, circle, and poly shape support only'),
                    'coords' => QtiDoc::partial('See attribute shape'),
                    'hotspotLabel' => QtiDoc::none(),
                ]
            ]
        ]);
        return $documentation;
    }
}
