<?php

namespace LearnosityQti\Tests\Unit\Processors\QtiV2\In\Fixtures;

use qtism\common\datatypes\QtiCoords;
use qtism\common\datatypes\QtiShape;
use qtism\data\content\interactions\GraphicOrderInteraction;
use qtism\data\content\interactions\HotspotChoice;
use qtism\data\content\interactions\HotspotChoiceCollection;

class GraphicOrderInteractionBuilder
{
    public static function build($identifier, $bgObject, $hotspotChoices)
    {
        $hotSpotChoiceCollection = new HotspotChoiceCollection();
        foreach ($hotspotChoices as $identifier => $data) {
            $coords = new QtiCoords(QtiShape::CIRCLE, $data);
            $hotspotChoice = new HotspotChoice($identifier, QtiShape::CIRCLE, $coords);
            $hotSpotChoiceCollection->attach($hotspotChoice);
        }
        return new GraphicOrderInteraction($identifier, $bgObject, $hotSpotChoiceCollection);
    }
}
