<?php

namespace LearnosityQti\Processors\QtiV2\In\Documentation\Interactions;


use LearnosityQti\Processors\QtiV2\In\Documentation\InteractionDocumentationInterface;
use LearnosityQti\Processors\QtiV2\In\Documentation\QtiDoc;

class MatchInteractionDocumentation implements InteractionDocumentationInterface
{
    public static function getInteractionDocumentation()
    {
        $documentation['@attributes'] = QtiDoc::defaultCommonInteractionAttributeRow();
        $documentation = array_replace_recursive($documentation, [
            '@notes' => "
                The element 'matchInteraction' map to our 'choicematrix' question.
                Read the documentation: <a href='http://docs.learnosity.com/assessment/questions/questiontypes#choicematrix'>
                http://docs.learnosity.com/assessment/questions/questiontypes#choicematrix</a>.
                <br/><br/>
                We currently only support validation template `map_response` and `match_correct` for this interaction.
            ",
            '@attributes' => [
                'shuffle' => QtiDoc::none('This feature is not supported'),
                'maxAssociations' => QtiDoc::none('We do not rely on this attribute.'),
                'minAssociations' => QtiDoc::none('We do not rely on this attribute.')
            ],
            'prompt' => QtiDoc::support(),
            'simpleMatchSet' => [
                'simpleAssociableChoice' => [
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
                        'matchMax' => QtiDoc::none('We do not rely on this attribute.'),
                        'matchMin' => QtiDoc::none('We do not rely on this attribute.')
                    ]
                ]
            ],
        ]);
        $documentation['simpleMatchSet']['simpleAssociableChoice'] = array_merge(
            $documentation['simpleMatchSet']['simpleAssociableChoice'],
            QtiDoc::defaultFlowStaticRow()
        );
        return $documentation;
    }
}
