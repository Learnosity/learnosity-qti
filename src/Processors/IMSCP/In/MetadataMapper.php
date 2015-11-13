<?php

namespace LearnosityQti\Processors\IMSCP\In;

use DOMElement;
use qtism\data\storage\xml\marshalling\Marshaller;

class MetadataMapper
{
    /**
     * Parse an object of DOMElement to a `tags` PHP array since MetadataXML has no meaning to Learnosity
     *
     * @param DOMElement $metadataElement
     *
     * @return array
     */
    public function map(DOMElement $metadataElement)
    {
        $flattenedMetadata = [];

        // Mapping <schema>
        $schemaElement = Marshaller::getChildElementsByTagName($metadataElement, 'schema');
        if (!empty($schemaElement) && is_array($schemaElement) && count($schemaElement) > 0) {
            $flattenedMetadata[$schemaElement[0]->nodeName] = [$schemaElement[0]->nodeValue];
        }
        // Mapping <schemaversion>
        $schemaversionElement = Marshaller::getChildElementsByTagName($metadataElement, 'schemaversion');
        if (!empty($schemaversionElement) && is_array($schemaversionElement) && count($schemaversionElement) > 0) {
            $flattenedMetadata[$schemaversionElement[0]->nodeName] = [$schemaversionElement[0]->nodeValue];
        }

        // Mapping all those <lom> (s)
        $lomElement = Marshaller::getChildElementsByTagName($metadataElement, 'lom');
        if (!empty($lomElement) && is_array($lomElement) && count($lomElement) > 0) {
            foreach ($lomElement[0]->childNodes as $child) {
                $tagType = $child->localName; // No namespace
                $tagLines = $this->recursively_find_text_nodes($child);
                $flattenedMetadata[$tagType] = $tagLines;
            }
        }

        return $flattenedMetadata;
    }

    private function recursively_find_text_nodes($dom_element, $depth = 1, $carry = '')
    {
        $return = [];
        foreach ($dom_element->childNodes as $dom_child) {
            switch ($dom_child->nodeType) {
                case XML_TEXT_NODE:
                    if (trim($dom_child->nodeValue) !== '') {
                        $return[] = $carry . $dom_child->nodeValue;
                    }
                    break;
                case XML_ELEMENT_NODE:
                    $carry .= $dom_child->localName . ':';
                    $return = array_merge($return, $this->recursively_find_text_nodes($dom_child, $depth + 1, $carry));
                    break;
            }
        }
        return $return;
    }
}
