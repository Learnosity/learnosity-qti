<?php

namespace LearnosityQti\Processors\QtiV2\In\Documentation;

class QtiDoc
{
    public static function undefined()
    {
        return QtiDoc::row(SupportStatus::UNKNOWN, 'No documentation provided yet. Please contact support.');
    }

    public static function none($description = '')
    {
        return QtiDoc::row(SupportStatus::NO, $description);
    }

    public static function support($description = '')
    {
        return QtiDoc::row(SupportStatus::YES, $description);
    }

    public static function partial($description = '')
    {
        return QtiDoc::row(SupportStatus::PARTIAL, $description);
    }

    public static function defaultCommonInteractionAttributeRow()
    {
        return [
            'xmlbase' => QtiDoc::none(),
            'id'                 => QtiDoc::none(),
            'class'              => QtiDoc::none(),
            'xmllang'            => QtiDoc::none(),
            'label'              => QtiDoc::none(),
            'responseIdentifier' => QtiDoc::none('The value prepended by the assessementItem identifier is used to be
                                        mapped to Question `reference`.')
        ];
    }

    public static function defaultFlowStaticRow()
    {
        return [
            'printedVariable' => QtiDoc::row(SupportStatus::NO, ''),
            'feedbackBlock' => QtiDoc::row(SupportStatus::NO, ''),
            'feedbackInline' => QtiDoc::row(SupportStatus::NO, ''),
            'templateInline' => QtiDoc::row(SupportStatus::NO, ''),
            'm:math' => QtiDoc::support('Having <math> element in content will set `is_math` on the corresponding
                                                questions to be set to true, thus allowed it to be rendered correctly'),
            'x:include' => QtiDoc::row(SupportStatus::NO, ''),
            '(xhtml)*' => QtiDoc::row(SupportStatus::YES, 'Other common XHTML elements as defined
                <a href="http://www.imsglobal.org/question/qtiv2p1/imsqti_infov2p1.html#element10124">http://www.imsglobal.org/question/qtiv2p1/imsqti_infov2p1.html#element10124</a>')
        ];
    }

    public static function defaultBlockStaticRow()
    {
        return [
            'feedbackBlock' => QtiDoc::row(SupportStatus::NO, ''),
            'templateBlock' => QtiDoc::row(SupportStatus::NO, ''),
            'm:math' => QtiDoc::support('Having <math> element in content will set `is_math` on the corresponding
                                                questions to be set to true, thus allowed it to be rendered correctly'),
            'x:include' => QtiDoc::row(SupportStatus::NO, ''),
            '(xhtml)*' => QtiDoc::row(SupportStatus::YES, 'Other common XHTML elements as defined
                <a href="http://www.imsglobal.org/question/qtiv2p1/imsqti_infov2p1.html#element10123">http://www.imsglobal.org/question/qtiv2p1/imsqti_infov2p1.html#element10123</a>')
        ];
    }

    private static function row($supportStatus, $description = '')
    {
        // TODO: Check support status should be defined
        return [
            'supportStatus' => $supportStatus,
            'description' => $description,
            'type' => 'row'
        ];
    }
}
