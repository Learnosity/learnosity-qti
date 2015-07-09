<?php

namespace Learnosity\Tests\Unit\Mappers\QtiV2\In\Fixtures;

use qtism\common\datatypes\Coords;
use qtism\common\datatypes\Shape;
use qtism\data\content\interactions\GraphicOrderInteraction;
use qtism\data\content\interactions\HotspotChoice;
use qtism\data\content\interactions\HotspotChoiceCollection;

class GraphicOrderInteractionBuilder
{
    public static function build($identifier, $bgObject, $hotspotChoices)
    {
        $hotSpotChoiceCollection = new HotspotChoiceCollection();
        foreach ($hotspotChoices as $identifier => $data) {
            $coords = new Coords(Shape::CIRCLE, $data);
            $hotspotChoice = new HotspotChoice($identifier, Shape::CIRCLE, $coords);
            $hotSpotChoiceCollection->attach($hotspotChoice);
        }
        return new GraphicOrderInteraction($identifier, $bgObject, $hotSpotChoiceCollection);
    }
}
