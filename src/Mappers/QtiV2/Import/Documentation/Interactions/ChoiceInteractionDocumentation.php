<?php

namespace Learnosity\Mappers\QtiV2\Import\Documentation\Interactions;

use Learnosity\Mappers\QtiV2\Import\Documentation\QtiDoc;

class ChoiceInteractionDocumentation
{
    public static function getDocumentation()
    {
        $documentation = [
            '@notes' => "
                The element 'choiceInteraction' map to our 'mcq' question.
                We currently only support simple 'exactMatch' validation.
            ",
            '@attributes' => [
                'xmlbase' => QtiDoc::none(),
                'id'                 => QtiDoc::none(),
                'class'              => QtiDoc::none(),
                'xmllang'            => QtiDoc::none(),
                'label'              => QtiDoc::none(),
                'responseIdentifier' => QtiDoc::none('At the moment we are not mapping this to anything. However eventually,
                                            we want to use this to map to our question `reference`.'),
                'shuffle'            => QtiDoc::support('Learnosity does not support partial shuffle,
                                            thus ignoring simpleChoice @fixed attribute'),
                'maxChoices'         => QtiDoc::partial('Learnosity does not support specifying the count of choices. If this value
                                            is more than one, then mcq `multiple_responses` is set to true'),
                'minChoices'         => QtiDoc::none('By default this would be one'),
                'orientation'        => QtiDoc::none()
            ],
            'prompt' => QtiDoc::support('We map this to our question `stimulus`.'),
            'simpleChoice' => [
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
        ];
        $documentation['simpleChoice'] = array_merge($documentation['simpleChoice'], QtiDoc::defaultFlowStaticRow());
        return $documentation;
    }
} 
