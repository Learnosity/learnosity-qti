<?php

namespace LearnosityQti\Utils\General;

use LearnosityQti\Utils\SimpleHtmlDom\SimpleHtmlDom;
use LearnosityQti\Utils\Log\Logger;

class HtmlHelper
{
    /**
     * Given a string of HTML, let the callback iterate each found tags. Example use case would
     * be to iterate all 'img' tags and append 'https://assets.learnosity.com.au/...' to its 'src' with
     * callback:
     *
     * $callback = function($tag) {
     *      $tag->src = 'https://assets.learnosity.com.au/...' . $tag->src;
     * }
     *
     * @param string $htmlString ie. '<div><p>Hello World</p><img ... /></div>'
     * @param string $tagName ie. 'img'
     * @param callable $callback
     * @return string $html
     */
    public static function eachTags($htmlString, $tagName, callable $callback)
    {
        $html = new SimpleHtmlDom();
        if (!$html->load($htmlString, false)) {
            Logger::warning('Invalid HTML: ' . $htmlString);
            return $htmlString;
        }
        foreach ($html->find($tagName) as $tag) {
            if (is_callable($callback)) {
                call_user_func_array($callback, [&$tag]);
            }
        }
        return (string) $html;
    }

    public static function fixImages($htmlString, callable $callback)
    {
        return self::eachTags($htmlString, 'img', $callback);
    }

    public static function fixAudios($htmlString, callable $callback)
    {
        return self::eachTags($htmlString, 'audio', $callback);
    }

    public static function hasElement($htmlString, $tagName)
    {
        $html = new SimpleHtmlDom();
        $hasElement = false;
        if (!$html->load($htmlString, false)) {
            Logger::warning('Invalid HTML: ' . $htmlString);
            return $htmlString;
        }
        foreach ($html->find($tagName) as $tag) {
            $hasElement = true;
            break;
        }
        return $hasElement;
    }

    public function hasElementWithAttribute($htmlString, $tagName, $attributeName, $attributeValue = null)
    {
        $html = new SimpleHtmlDom();
        $hasElement = false;
        if (!$html->load($htmlString, false)) {
            Logger::warning('Invalid HTML: ' . $htmlString);
            return $htmlString;
        }
        foreach ($html->find($tagName) as $tag) {
            if (isset($tag->attr[$attributeName]) &&
                (!isset($attributeValue) || ($tag->attr[$attributeName] === $attributeValue))
            ) {
                $hasElement = true;
                break;
            }
        }
        return $hasElement;
    }
}
