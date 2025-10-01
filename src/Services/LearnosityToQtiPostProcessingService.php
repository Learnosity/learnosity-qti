<?php

namespace LearnosityQti\Services;

use LearnosityQti\Processors\QtiV2\Out\Constants as LearnosityExportConstant;
use LearnosityQti\Services\LogService;

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

    /**
     * For some reason, the modalFeedback elements are encoded as textRuns.
     * This function will decode the HTML tags that are contained within the modalFeedback elements.
     */
    function decodeModalFeedbackElements($xmlString)
    {
        if (strpos($xmlString, '<modalFeedback') === false) {
            return $xmlString;
        }

        // Very large payloads break things...
        if (strlen($xmlString) > 100000) {
            LogService::log('<modalFeedback> XML string is too large to process. Returning XML encoded string.');
            return $xmlString;
        }

        $doc = new \DOMDocument('1.0', 'UTF-8');

        // SECURITY: avoid LIBXML_NOENT (entity expansion) unless you absolutely need it.
        // Keep NONET to block external fetches.
        $flags = LIBXML_NONET | LIBXML_NOCDATA | LIBXML_NOBLANKS;
        if (!@$doc->loadXML($xmlString, $flags)) {
            // If the outer XML is malformed, return original to be safe
            return $xmlString;
        }

        $doc->preserveWhiteSpace = true;
        $doc->formatOutput = true;

        $xpath = new \DOMXPath($doc);
        // Namespace handling (qti:…)
        $ns = $doc->documentElement->namespaceURI;
        if ($ns) {
            $xpath->registerNamespace('qti', $ns);
        }

        // Find all <modalFeedback> elements
        $feedbackNodes = $xpath->query('//qti:modalFeedback');
        if (!$feedbackNodes || $feedbackNodes->length === 0) {
            return $doc->saveXML();
        }

        foreach ($feedbackNodes as $feedback) {
            // Decode ONLY &lt; and &gt; to avoid breaking attribute entities (&amp;, &quot;).
            $encoded = $feedback->nodeValue ?? '';
            if ($encoded === '') {
                // Nothing to do for empty feedback
                continue;
            }
            $decodedText = str_replace(['&lt;', '&gt;'], ['<', '>'], $encoded);

            $this->replaceInnerWithFragmentSafely($doc, $feedback, $decodedText);
        }

        return $doc->saveXML();
    }

    /**
     * Is $frag safe to feed to \DOMDocumentFragment::appendXML() ?
     * Returns true only if it parses as well-formed XML when wrapped.
     */
    function isWellFormedXmlFragment(string $frag): bool
    {
        // Obvious HTML/MathJax tells us to avoid appendXML()
        if (preg_match('/<\s*mjx-|&nbsp;|<img\b(?![^>]*\/>)/i', $frag)) {
            return false;
        }

        $tmp = new \DOMDocument('1.0', 'UTF-8');
        // No network, suppress warnings; we only care about success/failure.
        $flags = LIBXML_NONET | LIBXML_NOERROR | LIBXML_NOWARNING;
        $ok = @$tmp->loadXML('<wrap>'.$frag.'</wrap>', $flags);
        if (!$ok || !$tmp->documentElement) {
            return false;
        }

        // Reject undeclared namespace prefixes
        $xp = new \DOMXPath($tmp);
        foreach ($xp->query('//*[contains(name(), ":") or @*[contains(name(), ":")]]') as $n) {
            $parts = explode(':', $n->nodeName, 2);
            if (count($parts) === 2 && !$n->lookupNamespaceURI($parts[0])) {
                return false;
            }
            if ($n->hasAttributes()) {
                foreach ($n->attributes as $attr) {
                    if (strpos($attr->nodeName, ':') !== false) {
                        [$pfx] = explode(':', $attr->nodeName, 2);
                        if (!$n->lookupNamespaceURI($pfx)) {
                            return false;
                        }
                    }
                }
            }
        }
        return true;
    }

    /**
     * HTML → XML import:
     *  - Parses as HTML (tolerant to &nbsp;, bare <img>, etc.)
     *  - Replaces MathJax <mjx-*> blocks with contained MathML <math>, or drops them
     *  - Strips CKEditor widget scaffolding / drag handles
     *  - Imports cleaned nodes into $target (which belongs to $doc)
     */
    function appendHtmlFragmentAsXml(\DOMDocument $doc, \DOMNode $target, string $html): void
    {
        $tmp = new \DOMDocument('1.0', 'UTF-8');
        $flags = LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD | LIBXML_NONET | LIBXML_NOERROR | LIBXML_NOWARNING;
        @$tmp->loadHTML('<!doctype html><html><body>'.$html.'</body></html>', $flags);

        $xp = new \DOMXPath($tmp);

        // Collapse any mjx subtree to its MathML <math> payload (if present)
        foreach ($xp->query('//*[starts-with(local-name(), "mjx-")]') as $mjx) {
            $math = $mjx->getElementsByTagNameNS('http://www.w3.org/1998/Math/MathML', 'math')->item(0);
            if ($math instanceof \DOMElement) {
                $mjx->parentNode->replaceChild($math->cloneNode(true), $mjx);
            } else {
                $mjx->parentNode->removeChild($mjx);
            }
        }

        // Remove CKEditor widget wrappers & drag handles
        foreach ($xp->query('//*[@data-widget] | //span[contains(@style, "plugins/widget/images/handle.png")]') as $w) {
            while ($w->firstChild) {
                $w->parentNode->insertBefore($w->firstChild, $w);
            }
            $w->parentNode->removeChild($w);
        }

        // Strip editing attributes that shouldn’t be serialized
        foreach ($xp->query('//*[@contenteditable or @tabindex]') as $n) {
            $n->removeAttribute('contenteditable');
            $n->removeAttribute('tabindex');
        }

        // Import cleaned children from <body> into $target
        $body = $tmp->getElementsByTagName('body')->item(0);
        if ($body) {
            foreach (iterator_to_array($body->childNodes) as $child) {
                $target->appendChild($doc->importNode($child, true));
            }
        }
    }

    /**
     * Replace all children of $feedback with a safe fragment built from $text.
     * Uses appendXML only when proven safe; otherwise HTML fallback.
     */
    function replaceInnerWithFragmentSafely(\DOMDocument $doc, \DOMElement $feedback, string $text): void
    {
        // Try to use appendXML only for verified-safe fragments
        if ($this->isWellFormedXmlFragment($text)) {
            $frag = $doc->createDocumentFragment();
            if (@$frag->appendXML($text)) {
                while ($feedback->firstChild) {
                    $feedback->removeChild($feedback->firstChild);
                }
                $feedback->appendChild($frag);
                return;
            }
            // Fall through to HTML path if appendXML returns false
        }

        // HTML fallback (covers MathJax/HTML5/etc.)
        while ($feedback->firstChild) {
            $feedback->removeChild($feedback->firstChild);
        }
        $this->appendHtmlFragmentAsXml($doc, $feedback, $text);
    }
}
