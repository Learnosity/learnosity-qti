<?php

namespace Learnosity\Mappers\QtiV2\Import\Documentation;

class AssessmentItemDocumentation
{
    public static function getInteractionDocumentation()
    {
        return [
            '@notes'                => "QTI assessmentItem is mapped to Learnosity Item.",
            '@attributes'           => [
                'identifier'    => QtiDoc::support('This attribute is mapped Item `reference`.'),
                'title'         => QtiDoc::support('This attribute is mapped to Item `description`.'),
                'label'         => QtiDoc::none(),
                'xmllang'       => QtiDoc::none(),
                'toolName'      => QtiDoc::none(),
                'toolVersion'   => QtiDoc::none(),
                'adaptive'      => QtiDoc::none(),
                'timeDependent' => QtiDoc::none()
            ],
            'responseDeclaration'   => QtiDoc::partial('Support only format defined for QTI response processing template `match_correct`.'),
            'outcomeDeclaration'    => QtiDoc::none(),
            'templateDeclaration'   => QtiDoc::none('Having this element set will throw a critical exception and stop conversion process.'),
            'templateProcessing'    => QtiDoc::none('Having this element set will throw a critical exception and stop conversion process.'),
            'stylesheet'            => QtiDoc::none(),
            'itemBody'              => [
                '@attributes'       => [
                    'id'      => QtiDoc::none(),
                    'class'   => QtiDoc::none(),
                    'xmllang' => QtiDoc::none(),
                    'label'   => QtiDoc::none()
                ],
                'rubricBlock'         => QtiDoc::none(),
                'positionObjectStage' => QtiDoc::none(),
                'feedbackBlock'       => QtiDoc::none(),
                'templateBlock'       => QtiDoc::none(),
                'infoControl'         => QtiDoc::none(),
                'm:math'              => QtiDoc::none(),
                'x:include'           => QtiDoc::none(),
                'xhtml*'              => QtiDoc::support('Support only block XHTML elements as per QTI specs.'),
                '*Interaction' => QtiDoc::partial('Interactions will be parsed as Questions. See more documentation on Interactions below.')
            ],
            'responseProcessing'    => QtiDoc::partial('We process QTI `match_corect` defined response processing template
                                            on some of our interactions.'),
            'modalFeedback'         => QtiDoc::none(),
            'apip:apiAccessibility' => QtiDoc::none(),
        ];
    }
} 
