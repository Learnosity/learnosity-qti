<?php

namespace Learnosity\Processors\QtiV2\Out\Documentation;

class LearnosityDoc
{
    public static function none($description = '')
    {
        return LearnosityDoc::row(SupportStatus::NO, $description);
    }

    public static function support($description = '')
    {
        return LearnosityDoc::row(SupportStatus::YES, $description);
    }

    public static function partial($description = '')
    {
        return LearnosityDoc::row(SupportStatus::PARTIAL, $description);
    }

    private static function row($supportStatus, $description = '')
    {
        return [
            'supportStatus' => $supportStatus,
            'description' => $description,
        ];
    }
}
