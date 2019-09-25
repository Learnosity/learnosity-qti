<?php

namespace LearnosityQti\Utils\General;

use LearnosityQti\Exceptions\MappingException;
use LearnosityQti\Utils\Xml\EntityUtil as XmlEntityUtil;

class DOMHelper
{

    public static function getDomForXml($xml)
    {
        $dom = new \DOMDocument();

        $dom->preserveWhiteSpace = false;
        $dom->formatOutput = false;
        $dom->substituteEntities = false;

        $isValid = $dom->loadXML($xml);

        if (!$isValid) {
            throw new MappingException('Invalid XML; Failed to parse DOM for sharedpassage content');
        }

        return $dom;
    }
    
    public static function sanitizeXml($xml)
    {
        $xml = trim($xml);

        $dom = new \DOMDocument('1.0', 'UTF-8');
        $previousLibXmlSetting = libxml_use_internal_errors(true);

        // HACK: Pass the version and encoding to prevent libxml from decoding HTML entities (esp. &amp; which libxml borks at)
        // Only do this if it hasnt already been passed along in the xml string. Sometimes, we read from a file, and sometimes
        // we read from a block inside another file.
        if (strpos($xml, '<?xml ') !== false) {
            $xml = substr($xml, strpos($xml, '>') + 1);
        }
        $xml = '<?xml version="1.0" encoding="UTF-8">' . "<div>$xml</div>";

        $dom->loadHTML($xml, LIBXML_HTML_NODEFDTD | LIBXML_HTML_NOIMPLIED);
        $xml = $dom->saveXML(static::getFragmentWrapperDocumentElementForDom($dom));

        // HACK: Handle the fact that XML can't handle named entities (and HTML5 has no DTD for it)
        $xml = XmlEntityUtil::convertNamedEntitiesToHexInString($xml);

        libxml_clear_errors();
        libxml_use_internal_errors($previousLibXmlSetting);

        return $xml;
    }

    public static function getInnerXmlFragmentFromDom(\DOMDocument $dom)
    {
        $fragment = $dom->createDocumentFragment();
        $childNodes = $dom->documentElement->childNodes;
        while (($node = $childNodes->item(0))) {
            $node->parentNode->removeChild($node);
            $fragment->appendChild($node);
        }

        return $fragment;
    }

    private static function getFragmentWrapperDocumentElementForDom(\DOMDocument $dom)
    {
        /** @var DOMDocument $dom */
        $fragmentWrapper = $dom->createDocumentFragment();

        while ($dom->childNodes->length > 0) {
            /** @var DOMNode $childNode */
            $fragmentWrapper->appendChild($dom->childNodes->item(0));
        }

        $dom->replaceChild($fragmentWrapper, $dom);

        return $fragmentWrapper;
    }
}
