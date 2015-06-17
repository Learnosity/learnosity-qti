<?php

namespace Learnosity\Mappers\QtiV2\Import\Documentation\Interactions;

use Learnosity\Mappers\QtiV2\Import\Documentation\InteractionDocumentationInterface;
use Learnosity\Mappers\QtiV2\Import\Documentation\QtiDoc;

class InlineChoiceInteractionDocumentation implements InteractionDocumentationInterface
{
    public static function getInteractionDocumentation()
    {
        $documentation['@attributes'] = QtiDoc::defaultCommonInteractionAttributeRow();
        $documentation = array_replace_recursive($documentation, [
            '@notes' => "
                The element 'inlineChoiceInteraction' map to our 'clozedropdown' question.
                Read the documentation: <a href='http://docs.learnosity.com/assessment/questions/questiontypes#clozedropdown'>
                http://docs.learnosity.com/assessment/questions/questiontypes#clozedropdown</a>
            ",
            '@validation' => 'Currently only support simple `exactMatch` validation.',
            '@attributes' => [
                'shuffle'  => QtiDoc::none('Unfortunately `clozedropdown` does not support shuffling possible responses.'),
                'required' => QtiDoc::none('`clozedropdown` does not enforce a choice to be selected')
            ],
            'inlineChoice' => [
                '@notes' => 'Attributes are ignored and elements are simply marshalled as HTML content and mapped to
                    value in `possible_responses`',
                '@attributes' => [
                    'id' => QtiDoc::none(),
                    'class' => QtiDoc::none(),
                    'xmllang' => QtiDoc::none(),
                    'label' => QtiDoc::none(),
                    'identifier' => QtiDoc::none(),
                    'fixed' => QtiDoc::none('We do not support partial shuffle.'),
                    'templateIdentifier' => QtiDoc::none(),
                    'showHide' => QtiDoc::none(),
                ],
                'printedVariable' => QtiDoc::none()
            ]
        ]);
        return $documentation;
    }
}
