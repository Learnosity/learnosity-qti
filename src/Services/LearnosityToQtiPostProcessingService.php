<?php

namespace LearnosityQti\Services;

use LearnosityQti\Processors\QtiV2\Out\Constants as LearnosityExportConstant;

class LearnosityToQtiPostProcessingService
{
    public function __construct()
    {}

    public function processXml($content)
    {
        $map = [0x80, 0x10FFFF, 0, 0xFFFF]; // UTF-8 character range
        $content = mb_encode_numericentity($content, $map, 'UTF-8');

        $doc = new \DOMDocument('1.0', 'UTF-8');

        // Suppress warnings for malformed HTML
        libxml_use_internal_errors(true);

        // Load the wrapped HTML
        $doc->loadXML($content, LIBXML_NOENT | LIBXML_NOCDATA | LIBXML_NOBLANKS);
        $doc->preserveWhiteSpace = true;
        $doc->formatOutput = true;

        // Clear any parsing errors
        libxml_clear_errors();

        /***************** Start processing the HTML ****************/

        $xpath = new \DOMXPath($doc);
        $divNodes = $xpath->query('//div[@class="lrn-replace-blockquote"]');

        // Convert NodeList to array to prevent skipping elements
        $divs = iterator_to_array($divNodes);
        foreach ($divs as $div) {
            $blockquote = $doc->createElement('blockquote');
            while ($div->hasChildNodes()) {
                $blockquote->appendChild($div->firstChild);
            }
            $div->parentNode->replaceChild($blockquote, $div);
        }

        $qti = $doc->saveXML();

        $qti = str_replace(LearnosityExportConstant::DIRPATH_ASSETS, LearnosityExportConstant::DIRNAME_IMAGES . '/', $qti);
        $qti = str_replace('xmlns:default="http://www.w3.org/1998/Math/MathML"', '', $qti);
        //TODO: Change this to only select MathML elements?
        $qti = str_replace('<default:', '<', $qti);
        $qti = str_replace('</default:', '</', $qti);

        /***************** End processing the HTML ****************/

        return $qti;
    }
}
