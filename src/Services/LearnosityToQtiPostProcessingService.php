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

        // Convert NodeList to array to prevent skipping elements
        $divNodes = $xpath->query('//div[@class="lrn-replace-blockquote"]');
        $divs = iterator_to_array($divNodes);
        foreach ($divs as $div) {
            $blockquote = $doc->createElement('blockquote');
            while ($div->hasChildNodes()) {
                $blockquote->appendChild($div->firstChild);
            }
            $div->parentNode->replaceChild($blockquote, $div);
        }

        $qti = $doc->saveXML();

        // Remove unnecessary whitespace and newlines between `<object>` and `</object>`
        $qti = preg_replace('/>\s*<\/object>/', '></object>', $qti);

        $qti = str_replace(LearnosityExportConstant::DIRPATH_ASSETS, LearnosityExportConstant::DIRNAME_IMAGES . '/', $qti);
        $qti = str_replace('xmlns:default="http://www.w3.org/1998/Math/MathML"', '', $qti);
        //TODO: Change this to only select MathML elements?
        $qti = str_replace('<default:', '<', $qti);
        $qti = str_replace('</default:', '</', $qti);

        // Hack #34678675. <modalFeedback> elements are encoded because they are a textRun.
        // We need to decode the HTML <p> tags that might be contained.
        $qti = $this->decodeModalFeedbackElements($qti);

        /***************** End processing the HTML ****************/

        return $qti;
    }

    function decodeModalFeedbackElements($xmlString) {
        if (strpos($xmlString, '<modalFeedback') === false) {
            return $xmlString;
        }

        $doc = new \DOMDocument();
        $doc->loadXML($xmlString, LIBXML_NOENT | LIBXML_NOCDATA | LIBXML_NOBLANKS);
        $doc->preserveWhiteSpace = true;
        $doc->formatOutput = true;

        $xpath = new \DOMXPath($doc);

        // Get the namespace from the root element (if exists)
        $namespaceURI = $doc->documentElement->namespaceURI;
        if ($namespaceURI) {
            $xpath->registerNamespace('qti', $namespaceURI);
        }

        // Find all <modalFeedback> elements
        $feedbackNodes = $xpath->query('//qti:modalFeedback');

        foreach ($feedbackNodes as $feedback) {
            $decodedText = preg_replace_callback(
                '/&lt;(\/?)([a-zA-Z0-9]+)([^&]*)&gt;/',
                function ($matches) {
                    return '<' . $matches[1] . $matches[2] . $matches[3] . '>';
                },
                $feedback->nodeValue
            );

            // Convert the decoded text into a new DOMDocument fragment
            $fragment = $doc->createDocumentFragment();
            if ($fragment->appendXML($decodedText)) {
                // Replace old encoded content with new fragment
                while ($feedback->hasChildNodes()) {
                    $feedback->removeChild($feedback->firstChild);
                }
                $feedback->appendChild($fragment);
            }
        }

        return $doc->saveXML();
    }
}
