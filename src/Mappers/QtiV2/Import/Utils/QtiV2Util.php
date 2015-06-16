<?php

namespace Learnosity\Mappers\QtiV2\Import\Utils;

use Learnosity\Utils\StringUtil;

class QtiV2Util
{
    public static function getQuestionReference()
    {
        return StringUtil::generateRandomString(10);
    }

    public static function getItemReference()
    {
        return StringUtil::generateRandomString(10);
    }
} 
