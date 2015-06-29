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

    public static function generateRandomString($length)
    {
        if (!$length || $length % 2 !== 0) {
            throw new \Exception('Length must be even number');
        }
        $bytes = $length / 2;
        return bin2hex(openssl_random_pseudo_bytes($bytes));
    }
}
