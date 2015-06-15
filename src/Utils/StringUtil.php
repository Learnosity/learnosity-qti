<?php

namespace Learnosity\Utils;

class StringUtil
{
    public static function snakeToCamelCase($value)
    {
        $value = str_replace(' ', '', ucwords(str_replace('_', ' ', $value)));
        $value = strtolower(substr($value, 0, 1)) . substr($value, 1);
        return $value;
    }
} 
