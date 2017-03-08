<?php

namespace LearnosityQti\Utils;

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

    public static function startsWith($haystack, $needle)
    {
        // Search backwards starting from haystack length characters from the end
        return $needle === "" || strrpos($haystack, $needle, -strlen($haystack)) !== false;
    }

    public static function endsWith($haystack, $needle)
    {
        // Search forward starting from end minus needle length characters
        return $needle === "" || (($temp = strlen($haystack) - strlen($needle)) >= 0 && strpos($haystack, $needle, $temp) !== false);
    }

    /**
     * http://stackoverflow.com/questions/5622085/match-string-with-asterisk
     */
    public static function matchString($pattern, $str)
    {
        $pattern = preg_replace_callback('/[^*]/', function ($matches) {
            return preg_quote($matches[0], '/');
        }, $pattern);

        $pattern = str_replace('*', '(.*)', $pattern);
        $matched = preg_match_all('/^' . $pattern . '$/i', $str, $matches);
        return [(bool) $matched, $matches];
    }
}
