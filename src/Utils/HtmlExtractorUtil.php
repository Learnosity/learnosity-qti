<?php

namespace LearnosityQti\Utils;

class HtmlExtractorUtil {

    /**
     * Get html body contents from a given html file
     * 
     * @param string $file filepath to read html
     * @return string
     */
    public static function getHtmlData($file) {
        $file = getenv('GET_INPUT_PATH') . '/' . $file;
        $html = '';
        if (file_exists($file)) {
            $d = new \DOMDocument;
            $d->loadHTMLFile($file);
            $body = $d->getElementsByTagName('body')->item(0);
            if (!empty($body)) {
                foreach ($body->childNodes as $childNode) {
                    $html .= str_replace("\n", "", $d->saveHTML($childNode));
                }
            }
        } else {
            echo 'File not found: ' . $file . "\n";
        }
        return $html;
    }

}
