<?php

namespace LearnosityQti\Utils;

use DOMDocument;
use LearnosityQti\Exceptions\MappingException;
use LearnosityQti\Processors\QtiV2\Marshallers\LearnosityMarshallerFactory;
use qtism\data\content\TextRun;
use qtism\data\QtiComponent;
use qtism\data\QtiComponentCollection;
use qtism\data\storage\xml\marshalling\Qti21MarshallerFactory;

class QtiMarshallerUtil
{
    public static function unmarshallElement($string)
    {
        try {
            libxml_use_internal_errors(true);

            $dom = new DOMDocument('1.0', 'UTF-8');
            $dom->formatOutput = true;

            // TODO: Can only unmarshall nice stuff, doesnt work with dodgy or invalid HTML
            if (!$dom->loadXML("<body>$string</body>", LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD)) {
                $errors = libxml_get_errors();
                throw new \Exception('Something wrong with XML format');
            }

            $marshallerFactory = new LearnosityMarshallerFactory();
            $components = new QtiComponentCollection();
            foreach ($dom->documentElement->childNodes as $element) {
                if ($element instanceof \DOMText) {
                    $component = new TextRun($element->nodeValue);
                } else {
                    $marshaller = $marshallerFactory->createMarshaller($element);
                    $component = $marshaller->unmarshall($element);
                }
                $components->attach($component);
            }
            return $components;
        } catch (\Exception $e) {
            throw new MappingException('[Unable to transform to QTI] ' . $e->getMessage());
        }
    }

    public static function marshallValidCollection(QtiComponentCollection $collection)
    {
        $results = [];
        foreach ($collection as $component) {
            $results[] = self::marshallValidQti($component);
        }
        return implode('', $results);
    }

    public static function marshallCollection(QtiComponentCollection $collection)
    {
        $results = [];
        foreach ($collection as $component) {
            $results[] = self::marshall($component);
        }
        return implode('', $results);
    }

    public static function marshall(QtiComponent $component)
    {
        $marshallerFactory = new LearnosityMarshallerFactory();
        $marshaller = $marshallerFactory->createMarshaller($component);
        $element = $marshaller->marshall($component);

        $dom = new \DOMDocument();
        $dom->preserveWhiteSpace = false;
        $dom->formatOutput = false;
        $dom->substituteEntities = false;
        $node = $dom->importNode($element, true);

        $string = $dom->saveXML($node);

        // TODO: Decode html entitfy back here, this is a hack until I can figure out
        // TODO: how to not do that with DomDocument.saveXML();
        return html_entity_decode($string);
    }

    public static function marshallValidQti(QtiComponent $component)
    {
        $marshallerFactory = new Qti21MarshallerFactory();
        $marshaller = $marshallerFactory->createMarshaller($component);
        $element    = $marshaller->marshall($component);

        $dom = new \DOMDocument();
        $dom->preserveWhiteSpace = false;
        $dom->formatOutput = false;
        $node = $dom->importNode($element, true);

        return $dom->saveXML($node);
    }
}
