<?php

namespace Learnosity\Processors\QtiV2\In\Utils;

use DOMDocument;
use Learnosity\Processors\QtiV2\In\Marshallers\LearnosityMarshallerFactory;
use qtism\common\datatypes\Shape;
use qtism\data\QtiComponent;
use qtism\data\QtiComponentCollection;

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
        $marshallerFactory = new LearnosityMarshallerFactory();
        $marshaller = $marshallerFactory->createMarshaller($component);
        $element = $marshaller->marshall($component);

        $dom = new \DOMDocument();
        $dom->preserveWhiteSpace = false;
        $dom->formatOutput = false;
        $node = $dom->importNode($element, true);

        return $dom->saveXML($node);
    }

    /**
     * @param array $areaCoords
     * @param array $objectCoords
     * @param $qtiShape
     * @return array
     */
    public static function convertQtiCoordsToPercentage(array $areaCoords, array $objectCoords, $qtiShape)
    {
        switch ($qtiShape) {
            case Shape::RECT:
                return [
                    'x' => round($objectCoords[0] / $areaCoords[0] * 100, 4),
                    'y' => round($objectCoords[1] / $areaCoords[1] * 100, 4),
                    'width' => $objectCoords[2] - $objectCoords[0],
                    'height' => $objectCoords[3] - $objectCoords[1]
                ];
            default:
                return null;
        }

    }
}
