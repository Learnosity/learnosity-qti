<?php

namespace Learnosity\Mappers\QtiV2\Import\Documentation;

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
            'responseIdentifier' => QtiDoc::none('At the moment we are not mapping this to anything. However eventually,
                                            we want to use this to map to our question `reference`.')
        ];
    }

    public static function defaultFlowStaticRow()
    {
        return [
            'printedVariable' => QtiDoc::row(SupportStatus::NO, ''),
            'feedbackBlock' => QtiDoc::row(SupportStatus::NO, ''),
            'feedbackInline' => QtiDoc::row(SupportStatus::NO, ''),
            'templateInline' => QtiDoc::row(SupportStatus::NO, ''),
            'm:math' => QtiDoc::row(SupportStatus::NO, 'This is to be implemented later on.'),
            'x:include' => QtiDoc::row(SupportStatus::NO, ''),
            '(xhtml)*' => QtiDoc::row(SupportStatus::YES, '')
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
