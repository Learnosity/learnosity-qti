<?php

namespace LearnosityQti\Utils\General;

class StringHelper
{
    public static function compare($string1, $string2, $caseSensitive, $removeWhitespace)
    {
        if ($caseSensitive && $removeWhitespace) {
            return (self::stripWhitespace($string2) === self::stripWhitespace($string1));
        } elseif ($caseSensitive) {
            return ($string2 === $string1);
        } elseif ($removeWhitespace) {
            return (self::stripWhitespace(mb_strtolower($string2, 'UTF-8')) === self::stripWhitespace(mb_strtolower($string1, 'UTF-8')));
        }
        return (mb_strtolower($string2, 'UTF-8') === mb_strtolower($string1, 'UTF-8'));
    }

    public static function stripWhitespace($string)
    {
        return preg_replace('/\s/', '', $string);
    }

    public static function startsWith($haystack, $needle)
    {
        return strpos($haystack, $needle, 0) === 0;
    }

    public static function endsWith($haystack, $needle)
    {
        $expectedPosition = strlen($haystack) - strlen($needle);
        return strrpos($haystack, $needle, 0) === $expectedPosition;
    }

    public static function underscoreToCamelCase($string, $isFirstCharCaps = false)
    {
        if ($isFirstCharCaps) {
            $string[0] = strtoupper($string[0]);
        }
        $func = create_function('$c', 'return strtoupper($c[1]);');
        return preg_replace_callback('/_([a-z])/', $func, $string);
    }

    public static function contains($haystack, $needle)
    {
        return strpos($haystack, $needle) !== false;
    }

    /**
     * @see http://stackoverflow.com/questions/2510434/format-bytes-to-kilobytes-megabytes-gigabytes
     */
    public static function displayFriendlyBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= (1 << (10 * $pow));

        return round($bytes, $precision) . ' ' . $units[$pow];
    }

    public static function generateRandomString($length)
    {
        if (!$length || $length % 2 !== 0) {
            throw new \Exception('Length must be even number');
        }
        $bytes = $length / 2;
        return bin2hex(openssl_random_pseudo_bytes($bytes));
    }

    public static function findStringPositionRecursive($haystack, $needle, $offset = 0, &$results = array()) {                
       $offset = strpos($haystack, $needle, $offset);
        if ($offset === false) {
            return $results;
        } else {
            $results[] = $offset;
            return self::findStringPositionRecursive($haystack, $needle, ($offset + 1), $results);
        }
    }
}
