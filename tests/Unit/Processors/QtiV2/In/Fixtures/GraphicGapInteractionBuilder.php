<?php

namespace LearnosityQti\Tests\Unit\Processors\QtiV2\In\Fixtures;

use qtism\common\datatypes\Coords;
use qtism\common\datatypes\Shape;
use qtism\data\content\interactions\AssociableHotspot;
use qtism\data\content\interactions\AssociableHotspotCollection;
use qtism\data\content\interactions\GapImg;
use qtism\data\content\interactions\GapImgCollection;
use qtism\data\content\interactions\GraphicGapMatchInteraction;
use qtism\data\content\xhtml\Object;

class GraphicGapInteractionBuilder
{
    public static function build($identifier, $bgObject, $gapImgs, $hotspots)
    {
        $gapImgCollection = new GapImgCollection();

        foreach ($gapImgs as $id => $data) {
            $obj = new Object($data, 'image/png');
            $gapImg = new GapImg($id, 1, $obj);
            $gapImgCollection->attach($gapImg);
        }

        $associableHotspotCollection = new AssociableHotspotCollection();
        foreach ($hotspots as $id => $data) {
            $coords = new Coords(Shape::RECT, $data);
            $associableHotspot = new AssociableHotspot($id, 1, Shape::RECT, $coords);
            $associableHotspotCollection->attach($associableHotspot);
        }

        return new GraphicGapMatchInteraction(
            $identifier,
            $bgObject,
            $gapImgCollection,
            $associableHotspotCollection
        );
    }
}
