<?php

namespace Learnosity\Mappers\QtiV2\Import\Documentation\Interactions;

use Learnosity\Mappers\QtiV2\Import\Documentation\InteractionDocumentationInterface;
use Learnosity\Mappers\QtiV2\Import\Documentation\QtiDoc;

class ChoiceInteractionDocumentation implements InteractionDocumentationInterface
{
    // TODO: Write more extensive validation on its response processing process
    public static function getInteractionDocumentation()
    {
        $documentation['@attributes'] = QtiDoc::defaultCommonInteractionAttributeRow();
        $documentation = array_replace_recursive($documentation, [
            '@notes' => "
                The element 'choiceInteraction' map to our 'mcq' question.
                Read the documentation: <a href='http://docs.learnosity.com/assessment/questions/questiontypes#mcq'>
                http://docs.learnosity.com/assessment/questions/questiontypes#mcq</a>
                <br /><br />
                Currently only support simple `exactMatch` validation.
            ",
            '@attributes' => [
                'shuffle'            => QtiDoc::support('Learnosity does not support partial shuffle,
                                            thus ignoring simpleChoice @fixed attribute.'),
                'maxChoices'         => QtiDoc::partial('Learnosity does not support specifying the count of choices. If this value
                                            is more than one, then mcq `multiple_responses` is set to true.'),
                'minChoices'         => QtiDoc::none('By default this would be zero since we allow no response.'),
                'orientation'        => QtiDoc::support('By default mcq would be displayed vertically. We support do `horizontal`
                                            orientation and map to the question `ui_style`, ie. `type` of `horizontal` and `columns`
                                            will be number of the multiple choice options')
            ],
            'prompt' => QtiDoc::support('We map this to our question `stimulus`.'),
            'simpleChoice' => [
                '@notes' => 'Attributes are ignored and elements are simply marshalled as HTML content and mapped to
                    `value` in `options`',
                '@attributes' => [
                    'id' => QtiDoc::none(),
                    'class' => QtiDoc::none(),
                    'xmllang' => QtiDoc::none(),
                    'label' => QtiDoc::none(),
                    'identifier' => QtiDoc::none(),
                    'fixed' => QtiDoc::none('We do not support partial shuffle.'),
                    'templateIdentifier' => QtiDoc::none(),
                    'showHide' => QtiDoc::none(),
                ]
            ]
        ]);
        $documentation['simpleChoice'] = array_merge($documentation['simpleChoice'], QtiDoc::defaultFlowStaticRow());
        return $documentation;
    }
}
