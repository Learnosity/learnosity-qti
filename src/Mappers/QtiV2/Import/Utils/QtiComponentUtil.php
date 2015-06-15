<?php

namespace Learnosity\Mappers\QtiV2\Import\Utils;

use qtism\data\QtiComponent;
use qtism\data\QtiComponentCollection;
use qtism\data\storage\xml\marshalling\MarshallerFactory;

class QtiComponentUtil
{
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
        $marshallerFactory = new MarshallerFactory();
        $marshaller = $marshallerFactory->createMarshaller($component);
        $element = $marshaller->marshall($component);

        $dom = new \DOMDocument('1.0', 'UTF-8');
        $node = $dom->importNode($element, true);
        return $dom->saveXML($node);
    }
} 
