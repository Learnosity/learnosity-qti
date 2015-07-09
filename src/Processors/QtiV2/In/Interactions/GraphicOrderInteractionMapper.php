<?php

namespace Learnosity\Processors\QtiV2\In\Interactions;

use Learnosity\Processors\QtiV2\In\Utils\QtiComponentUtil;
use qtism\data\content\interactions\GraphicOrderInteraction;
use qtism\data\content\interactions\HotspotChoice;
use qtism\data\content\interactions\HotspotChoiceCollection;
use qtism\data\content\xhtml\Object;

class GraphicOrderInteractionMapper extends AbstractInteractionMapper
{
    public function getQuestionType()
    {
        /** @var GraphicOrderInteraction $interaction */
        $interaction = $this->interaction;
        $hotspotChoices = $interaction->getHotspotChoices();


        /** @var Object $imageObject */
        $imageObject = $interaction->getObject();
        $imageCoords = [$imageObject->getWidth(), $imageObject->getHeight()];
        $possibeResponseMapping = $this->buildPossibleResponseMapping($imageCoords, $hotspotChoices);
        
    }


    protected function buildPossibleResponseMapping(array $imageCoords, HotspotChoiceCollection $hotspotChoices)
    {
        $possibleResponseMapping = [];
        /** @var HotspotChoice $hotspotChoice */
        foreach ($hotspotChoices as $hotspotChoice) {
            $possibleResponseMapping[$hotspotChoice->getIdentifier()] =
                QtiComponentUtil::convertQtiCoordsToPercentage(
                    $imageCoords,
                    explode(',', $hotspotChoice->getCoords()),
                    $hotspotChoice->getShape()
                );
        }

        return $possibleResponseMapping;
    }
}
