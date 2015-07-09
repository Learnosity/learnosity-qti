<?php

namespace Learnosity\Processors\QtiV2\In\Utils;

use DOMDocument;
use Learnosity\Processors\QtiV2\In\Marshallers\LearnosityMarshallerFactory;
use qtism\common\datatypes\Shape;
use qtism\data\content\interactions\HotspotChoice;
use qtism\data\content\interactions\HotspotChoiceCollection;
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

    public static function convertHotspotChoiceCollectionToResponsePositionMapping($imageWidth, $imageHeight, HotspotChoiceCollection $hotspotChoices)
    {
        $responsePositionsMapping = [];
        /** @var HotspotChoice $hotspotChoice */
        foreach ($hotspotChoices as $hotspotChoice) {
            $percentage = QtiComponentUtil::convertQtiCoordsToPercentage(
                [$imageWidth, $imageHeight],
                explode(',', $hotspotChoice->getCoords()),
                $hotspotChoice->getShape()
            );
            $responsePositionsMapping[$hotspotChoice->getIdentifier()] = $percentage;
        }
        return $responsePositionsMapping;
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
