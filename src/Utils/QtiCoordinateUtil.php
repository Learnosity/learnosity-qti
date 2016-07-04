<?php

namespace LearnosityQti\Utils;

use qtism\common\datatypes\QtiCoords;
use qtism\common\datatypes\QtiShape;

class QtiCoordinateUtil
{
    public static function convertQtiCoordsToPercentage(array $areaCoords, array $objectCoords, $qtiShape)
    {
        switch ($qtiShape) {
            case QtiShape::RECT:
                return [
                    'x' => round($objectCoords[0] / $areaCoords[0] * 100, 2),
                    'y' => round($objectCoords[1] / $areaCoords[1] * 100, 2),
                    'width' => $objectCoords[2] - $objectCoords[0],
                    'height' => $objectCoords[3] - $objectCoords[1]
                ];
            case QtiShape::CIRCLE:
                return [
                    'x' => round($objectCoords[0] / $areaCoords[0] * 100, 2),
                    'y' => round($objectCoords[1] / $areaCoords[1] * 100, 2)
                ];
            default:
                return null;
        }
    }

    public static function convertPercentageToQtiCoords(array $responsePosition, $imageWidth, $imageHeight, $rectangleWidth, $rectangleHeight)
    {
        $leftX = round($responsePosition['x'] / 100 * $imageWidth, 0);
        $topY = round($responsePosition['y'] / 100 * $imageHeight, 0);
        $rightX = round($leftX + $rectangleWidth, 0);
        $bottomY = round($topY + $rectangleHeight, 0);

        return new QtiCoords(QtiShape::RECT, [intval($leftX), intval($topY), intval($rightX), intval($bottomY)]);
    }
}
