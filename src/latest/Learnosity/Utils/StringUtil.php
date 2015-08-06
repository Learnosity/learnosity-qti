<?php

namespace Learnosity\Utils;

class StringUtil
{
    public static function generateRandomString($length)
    {
        if (!$length || $length % 2 !== 0) {
            throw new \Exception('Length must be even number');
        }
        $bytes = $length / 2;
        return bin2hex(openssl_random_pseudo_bytes($bytes));
    }

    public static function contains($haystack, $needle)
    {
        return strpos($haystack, $needle) !== false;
    }
}
