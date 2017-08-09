<?php

namespace LearnosityQti\Utils\General;

class UrlHelper
{
    /**
     * Parses URLs from a given string
     * Same logic used by assets extractor for offline package
     *
     * @param  string $content
     * @return array
     */
    public static function parseUrls($content)
    {
        // Check for URLs surrounded by double quotes
        preg_match_all(
            '#"((http://|https://|//)[-a-zA-Z0-9@:%_\+.~\#?&=]{2,256}\.[a-z]{2,4}[^"\n\r\']*)"#si',
            $content,
            $singleQuotesMatches
        );
        // Check for URLs surrounded by single quotes
        preg_match_all(
            '#\'((http://|https://|//)[-a-zA-Z0-9@:%_\+.~\#?&=]{2,256}\.[a-z]{2,4}[^"\n\r\']*)\'#si',
            $content,
            $doubleQuotesMatches
        );
        // Merge the result
        $uris = array_unique(array_merge($singleQuotesMatches[1], $doubleQuotesMatches[1]));
        // Remove trailing '\' character if exist upon returning
        return array_map(function ($uri) {
            return rtrim($uri, "\\");
        }, $uris);
    }
}
