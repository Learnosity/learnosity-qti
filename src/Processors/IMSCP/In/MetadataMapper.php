<?php

namespace LearnosityQti\Processors\IMSCP\In;

use DOMElement;
use LearnosityQti\Processors\IMSCP\Entities\Metadata;

class MetadataMapper
{
    /**
     * Parse an object of DOMElement to a nested array of array in a simple tree like structure
     */
    public function map(DOMElement $metadataElement)
    {
        $metadatas = $this->xml_to_array($metadataElement);
        return new Metadata($metadatas);
    }

    /**
     * http://stackoverflow.com/questions/14553547/what-is-the-best-php-dom-2-array-function
     */
    private function xml_to_array(DOMElement $root)
    {
        $result = [];

        if ($root->hasAttributes()) {
            $attrs = $root->attributes;
            foreach ($attrs as $attr) {
                $result['@attributes'][$attr->name] = $attr->value;
            }
        }

        if ($root->hasChildNodes()) {
            $children = $root->childNodes;
            if ($children->length == 1) {
                $child = $children->item(0);
                if ($child->nodeType == XML_TEXT_NODE) {
                    $result['_value'] = $child->nodeValue;
                    return count($result) == 1
                        ? $result['_value']
                        : $result;
                }
            }
            $groups = [];
            foreach ($children as $child) {
                if (!isset($result[$child->localName])) {
                    $result[$child->localName] = $this->xml_to_array($child);
                } else {
                    if (!isset($groups[$child->localName])) {
                        $result[$child->localName] = [$result[$child->localName]];
                        $groups[$child->localName] = 1;
                    }
                    $result[$child->localName][] = $this->xml_to_array($child);
                }
            }
        }

        return $result;
    }
}
