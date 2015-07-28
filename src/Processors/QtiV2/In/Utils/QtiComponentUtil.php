<?php

namespace Learnosity\Processors\QtiV2\In\Utils;

use DOMDocument;
use Learnosity\Exceptions\MappingException;
use Learnosity\Processors\QtiV2\Marshallers\LearnosityMarshallerFactory;
use qtism\common\datatypes\Shape;
use qtism\data\content\TextRun;
use qtism\data\QtiComponent;
use qtism\data\QtiComponentCollection;

class QtiComponentUtil
{
    public static function unmarshallElement($string)
    {
        try {
            $dom = new DOMDocument('1.0', 'UTF-8');
            $dom->formatOutput = true;
            $dom->loadXML("<body>$string</body>", LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);

            // TODO: Can only unmarshall nice stuff, doesnt work with dodgy or invalid HTML
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
            throw new MappingException('[Unable to transforming to QTI] ' . $e->getMessage(), MappingException::CRITICAL);
        }
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
        $node = $dom->importNode($element, true);

        return $dom->saveXML($node);
    }

    public static function convertQtiCoordsToPercentage(array $areaCoords, array $objectCoords, $qtiShape)
    {
        switch ($qtiShape) {
            case Shape::RECT:
                return [
                    'x' => round($objectCoords[0] / $areaCoords[0] * 100, 2),
                    'y' => round($objectCoords[1] / $areaCoords[1] * 100, 2),
                    'width' => $objectCoords[2] - $objectCoords[0],
                    'height' => $objectCoords[3] - $objectCoords[1]
                ];
            case Shape::CIRCLE:
                return [
                    'x' => round($objectCoords[0] / $areaCoords[0] * 100, 2),
                    'y' => round($objectCoords[1] / $areaCoords[1] * 100, 2)
                ];
            default:
                return null;
        }
    }
}
