<?php

namespace LearnosityQti\Utils;

/**
 * This class is used to extract html content from html file.
 *
 */
class HtmlExtractorUtil
{

    /**
     * Get html body contents from a given html file
     * 
     * @param string $file filepath to read html
     * @return string
     */
    public static function getHtmlData($file)
    {

        $html = '';
        if (!empty($file) && file_exists($file)) {
            $d = new \DOMDocument;
            $d->loadHTMLFile($file);
            $body = $d->getElementsByTagName('body')->item(0);
            if (!empty($body)) {
                foreach ($body->childNodes as $childNode) {
                    $html .= str_replace(PHP_EOL, "", $d->saveHTML($childNode));
                }
            }
        } else {
            echo 'File not found: ' . $file . PHP_EOL;
        }
        return $html;
    }
}
