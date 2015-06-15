<?php

namespace Learnosity\Mappers\QtiV2\Import\Documentation;

class QtiDoc
{
    public static function undefined()
    {
        return null;
    }

    public static function defaultFlowStaticRow()
    {
        return [
            'printedVariable' => QtiDoc::undefined(),
            'feedbackBlock' => QtiDoc::undefined(),
            'feedbackInline' => QtiDoc::undefined(),
            'templateInline' => QtiDoc::undefined(),
            'm:math' => QtiDoc::undefined(),
            'x:include' => QtiDoc::undefined(),
            '@xhtml' => QtiDoc::undefined()
        ];
    }

    public static function row($supportStatus, $description = '')
    {
        // TODO: Check support status should be defined
        return [
            'supportStatus' => $supportStatus,
            'description' => $description,
            'type' => 'row'
        ];
    }
}
