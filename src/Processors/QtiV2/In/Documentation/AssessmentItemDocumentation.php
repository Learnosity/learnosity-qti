<?php

namespace Learnosity\Processors\QtiV2\In\Documentation;

class AssessmentItemDocumentation
{
    public static function getInteractionDocumentation()
    {
        return [
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
            'responseDeclaration'   => QtiDoc::partial('Support only format defined for standard QTI response processing template,
                                            ie. `match_correct`, `map_response`, `cc2_map_response`'),
            'outcomeDeclaration'    => QtiDoc::none(),
            'templateDeclaration'   => QtiDoc::none('Having this element set will throw a critical exception and stop conversion process.'),
            'templateProcessing'    => QtiDoc::none('Having this element set will throw a critical exception and stop conversion process.'),
            'stylesheet'            => QtiDoc::none(),
            'responseProcessing'    => QtiDoc::partial('Support only format defined for standard QTI response processing template,
                                            ie. `match_correct`, `map_response`, `cc2_map_response`.'),
            'modalFeedback'         => QtiDoc::none(),
            'apip:apiAccessibility' => QtiDoc::none(),
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
                'm:math'              => QtiDoc::support('Having <math> element in content will set `is_math` on the corresponding
                                                questions to be set to true, thus allowed it to be rendered correctly'),
                'x:include'           => QtiDoc::none(),
                'xhtml*'              => QtiDoc::support('Support only block XHTML elements as per QTI specs.'),
                '*Interaction' => QtiDoc::partial('Interactions will be parsed as Questions. See more documentation on Interactions below.')
            ],
        ];
    }
}
