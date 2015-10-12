<?php

namespace LearnosityQti\Processors\QtiV2\In\Documentation\Interactions;


use LearnosityQti\Processors\QtiV2\In\Documentation\InteractionDocumentationInterface;
use LearnosityQti\Processors\QtiV2\In\Documentation\QtiDoc;

class OrderInteractionDocumentation implements InteractionDocumentationInterface
{
    public static function getInteractionDocumentation()
    {
        $documentation['@attributes'] = QtiDoc::defaultCommonInteractionAttributeRow();
        $documentation = array_replace_recursive($documentation, [
            '@notes' => "
                The element 'orderInteraction' map to our 'orderlist' question.
                Read the documentation: <a href='http://docs.learnosity.com/assessment/questions/questiontypes#orderlist'>
                http://docs.learnosity.com/assessment/questions/questiontypes#orderlist</a>.
                <br/><br/>
                We currently only support validation template `match_correct` for this interaction.
            ",
            '@attributes' => [
                'shuffle' => QtiDoc::none('This feature is not supported'),
                'minChoices' => QtiDoc::none(),
                'maxChoices' => QtiDoc::none(),
                'orientation' => QtiDoc::none()
            ],
            'prompt' => QtiDoc::support(),
            'simpleChoice' => [
                '@attributes' => [
                    'identifier' => QtiDoc::support(),
                    'fixed' => QtiDoc::none(),
                    'showHide' => QtiDoc::none(),
                    'templateIdentifier' => QtiDoc::none()
                ]

            ]
        ]);
        $documentation = array_merge($documentation, QtiDoc::defaultBlockStaticRow());
        return $documentation;
    }
}
