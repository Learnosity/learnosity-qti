<?php

namespace Learnosity\Mappers\QtiV2\Import\Documentation\Interactions;

use Learnosity\Mappers\QtiV2\Import\Documentation\InteractionDocumentationInterface;
use Learnosity\Mappers\QtiV2\Import\Documentation\QtiDoc;

class GapMatchInteractionDocumentation implements InteractionDocumentationInterface
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
                'shuffle'         => QtiDoc::none('No support for `shuffle` on `clozeassociation`'),
            ],
            'prompt' => QtiDoc::support('We map this to our question `stimulus`.'),
            'gapText' => [
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
                ],
                'printedVariable' => QtiDoc::none(),
                'textRun' => QtiDoc::support()
            ],
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
            ]
        ]);
        $documentation = array_merge($documentation, QtiDoc::defaultBlockStaticRow());
        return $documentation;
    }
}
