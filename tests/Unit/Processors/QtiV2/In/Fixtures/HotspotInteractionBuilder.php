<?php

namespace LearnosityQti\Tests\Unit\Processors\QtiV2\In\Fixtures;

use qtism\common\datatypes\QtiCoords;
use qtism\common\datatypes\QtiShape;
use qtism\data\content\interactions\HotspotChoice;
use qtism\data\content\interactions\HotspotChoiceCollection;
use qtism\data\content\interactions\HotspotInteraction;
use qtism\data\content\xhtml\Object;

class HotspotInteractionBuilder
{
    public static function buildWithRectShapesChoices($responseIdentifier)
    {
        $imageObject = new Object('http://anyurl.com', 'image/png');
        $imageObject->setWidth(131);
        $imageObject->setHeight(37);
        $collection = self::buildRectShapesChoices();
        return new HotspotInteraction($responseIdentifier, $imageObject, $collection);
    }

    public static function buildWithCircleShapesChoices($responseIdentifier)
    {
        $imageObject = new Object('http://anyurl.com', 'image/png');
        $imageObject->setWidth(206);
        $imageObject->setHeight(280);
        
        $collection = new HotspotChoiceCollection();
        $collection->attach(new HotspotChoice('A', QtiShape::CIRCLE, new QtiCoords(QtiShape::CIRCLE, [77, 115, 8])));
        $collection->attach(new HotspotChoice('B', QtiShape::CIRCLE, new QtiCoords(QtiShape::CIRCLE, [118, 184, 8])));
        $collection->attach(new HotspotChoice('C', QtiShape::CIRCLE, new QtiCoords(QtiShape::CIRCLE, [150, 235, 8])));
        $collection->attach(new HotspotChoice('D', QtiShape::CIRCLE, new QtiCoords(QtiShape::CIRCLE, [96, 114, 8])));
        return new HotspotInteraction($responseIdentifier, $imageObject, $collection);
    }

    public static function buildWithPolyShapesChoices($responseIdentifier)
    {
        $imageObject = new Object('http://anyurl.com', 'image/png');
        $imageObject->setWidth(131);
        $imageObject->setHeight(37);
        $collection = new HotspotChoiceCollection();
        $collection->attach(new HotspotChoice('A', QtiShape::POLY, new QtiCoords(QtiShape::POLY, [7, 12, 64, 12, 64, 28, 7, 28])));
        $collection->attach(new HotspotChoice('B', QtiShape::POLY, new QtiCoords(QtiShape::POLY, [66, 11, 125, 11, 125, 30, 66, 30])));
        return new HotspotInteraction($responseIdentifier, $imageObject, 0, $collection);
    }

    public static function buildRectShapesChoices()
    {
        $collection = new HotspotChoiceCollection();
        $collection->attach(new HotspotChoice('A', QtiShape::RECT, new QtiCoords(QtiShape::RECT, [7, 12, 64, 28])));
        $collection->attach(new HotspotChoice('B', QtiShape::RECT, new QtiCoords(QtiShape::RECT, [66, 11, 125, 30])));
        return $collection;
    }
}
