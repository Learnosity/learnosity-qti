<?php

namespace LearnosityQti\Services;

class LearnosityToQtiPostProcessingService
{
    public function __construct()
    {}

    public function processXml($content)
    {
        $doc = new \DOMDocument('1.0', 'UTF-8');

        // Suppress warnings for malformed HTML
        libxml_use_internal_errors(true);

        // Load the wrapped HTML
        $doc->loadHTML($content, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);

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

        /***************** End processing the HTML ****************/

        return $content;
    }
}
