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
            $domDocument = new \DOMDocument;
            $domDocument->loadHTMLFile($file);
            $body = $domDocument->getElementsByTagName('body')->item(0);
            if (!empty($body)) {
                $html = self::extractHtmlFromBody($body, $domDocument);
                return $html;
            }
        } else {
            LogService::log("File not found: ".$file);
            throw new Exception('File not found: ' . $file);
        }
    }
    
    /**
     * Extract content from body element of a html file
     * @param type $body
     * @param type $domDocument
     * @return type
     */
    public static function extractHtmlFromBody($body, \DOMDocument $domDocument)
    {
        $htmlData = '';
        foreach ($body->childNodes as $childNode) {
            $htmlData .= str_replace(PHP_EOL, "", $domDocument->saveHTML($childNode));
        }
        return $htmlData;
    }
}
