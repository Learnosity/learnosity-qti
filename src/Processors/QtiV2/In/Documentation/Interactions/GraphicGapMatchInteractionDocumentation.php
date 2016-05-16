<?php

namespace LearnosityQti\Processors\QtiV2\In\Documentation\Interactions;

use LearnosityQti\Processors\QtiV2\In\Documentation\InteractionDocumentationInterface;
use LearnosityQti\Processors\QtiV2\In\Documentation\QtiDoc;

class GraphicGapMatchInteractionDocumentation implements InteractionDocumentationInterface
{
    public static function getInteractionDocumentation()
    {
        $documentation['@attributes'] = QtiDoc::defaultCommonInteractionAttributeRow();
        $documentation = array_replace_recursive($documentation, [
            '@notes' => "
                The element 'graphicGapMatchInteraction' map to our 'imageclozeassociation' question.
                Read the documentation: <a href='http://docs.learnosity.com/assessment/questions/questiontypes#imageclozeassociation'>
                http://docs.learnosity.com/assessment/questions/questiontypes#imageclozeassociation</a>
                <br /><br />
                Currently support `exactMatch` and `map_response` validation.
            ",
            '@attributes' => [
                'responseIdentifier' => QtiDoc::support(),
            ],
            'prompt' => QtiDoc::support('We map this to our question `stimulus`.'),
            'object' => QtiDoc::support('We map this to the background image. MIME type should be set correctly. Say "image/png"'),
            'gapImg' => [
                '@attributes' => [
                    'id' => QtiDoc::none(),
                    'class' => QtiDoc::none(),
                    'xmllang' => QtiDoc::none(),
                    'label' => QtiDoc::none(),
                    'identifier' => QtiDoc::support(),
                    'fixed' => QtiDoc::none(),
                    'templateIdentifier' => QtiDoc::none(),
                    'showHide' => QtiDoc::none(),
                    'matchGroup' => QtiDoc::none(),
                    'matchMax' => QtiDoc::none('This is ignored, `clozeassociation` can only match to a maximum of 0 response'),
                    'matchMin' => QtiDoc::none('This is ignored, `clozeassociation` allow zero responses'),
                    'objectLabel' => QtiDoc::none()
                ],
                'object' => QtiDoc::support()
            ],
            'associableHotspot' => [
                '@attributes' => [
                    'identifier' => QtiDoc::support(),
                    'fixed' => QtiDoc::none(),
                    'templateIdentifier' => QtiDoc::none(),
                    'showHide' => QtiDoc::none(),
                    'matchGroup' => QtiDoc::none(),
                    'shape' => QtiDoc::partial('Rectangle shape support only'),
                    'coords' => QtiDoc::partial('See attribute shape'),
                    'hotspotLabel' => QtiDoc::none(),
                    'matchMax' => QtiDoc::none('We calculate this value based on responseDeclaration data'),
                    'matchMin' => QtiDoc::none('We calculate this value based on responseDeclaration data'),
                ],
            ]
        ]);
        return $documentation;
    }
}
