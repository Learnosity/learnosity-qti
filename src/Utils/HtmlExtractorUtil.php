<?php

namespace LearnosityQti\Utils;

use Exception;
use LearnosityQti\Services\LogService;

/**
 * This class is used to extract html content from html file.
 *
 */
class HtmlExtractorUtil
{

    /**
     * Get html contents from a given html file
     * @param string $file filepath to read html
     * @return string
     */
    public static function getHtmlData($file)
    {
        if (file_exists($file)) {
            $d = new \DOMDocument;
            $d->loadHTMLFile($file);
            $body = $d->getElementsByTagName('body')->item(0);
            if (!empty($body)) {
                $html = self::extractHtmlFromBody($body, $d);
                return $html;
            }
        } else {
            LogService::log("File not found: ".$file);
            throw new Exception('File not found: ' . $file);
        }
    }
    
    /**
     * Get html contents from a given body element
     * @param type $body
     * @return type
     */
    public static function extractHtmlFromBody($body, $d)
    {
        $htmlData = '';
        foreach ($body->childNodes as $childNode) {
            $htmlData .= str_replace(PHP_EOL, "", $d->saveHTML($childNode));
        }
        return $htmlData;
    }
}
