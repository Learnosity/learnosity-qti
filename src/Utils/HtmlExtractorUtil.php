<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace LearnosityQti\Utils;

/**
 * Description of HtmlExtractorUtil
 *
 * @author qainfotech
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
                    $html .= str_replace("\n", "", $d->saveHTML($childNode));
                }
            }
        } else {
            echo 'File not found: ' . $file . "\n";
        }
        return $html;
    }
}
