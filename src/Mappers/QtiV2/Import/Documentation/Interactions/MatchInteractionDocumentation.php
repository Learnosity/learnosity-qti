<?php

namespace Learnosity\Mappers\QtiV2\Import\Documentation\Interactions;


use Learnosity\Mappers\QtiV2\Import\Documentation\InteractionDocumentationInterface;
use Learnosity\Mappers\QtiV2\Import\Documentation\QtiDoc;

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
                We currently only support validation template map_response, cc2_map_response and match_correct for this interaction.
            ",
            '@attributes' => [
                'shuffle' => QtiDoc::none('This feature is not supported'),
                'maxAssociations' => QtiDoc::partial('We do not rely on this attribute'),
                'minAssociations' => QtiDoc::partial('We do not rely on this attribute')
            ],
            'prompt' => QtiDoc::support(),
            'simpleMatchSet' => [
                '@notes' => 'There must have 2 simpleAssociableChoice tags to make the interaction valid',
                'simpleAssociableChoice' => [
                    '@attributes' => [
                        'identifier' => QtiDoc::support(),
                        'matchMax' => QtiDoc::partial('We do not rely on this attribute'),
                        'matchMin' => QtiDoc::partial('We do not rely on this attribute'),
                        'fixed' => QtiDoc::none(),
                        'templateIdentifier' => QtiDoc::none(),
                        'showHide' => QtiDoc::none(),
                        'matchGroup' => QtiDoc::none()
                    ]
                ]
            ],
        ]);
        return $documentation;
    }
}