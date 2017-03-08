<?php

namespace LearnosityQti\Processors\QtiV2\In;

use \LearnosityQti\Processors\QtiV2\In\ItemBuilders\MergedItemBuilder;
use \LearnosityQti\Processors\QtiV2\In\ItemBuilders\RegularItemBuilder;
use \qtism\data\content\interactions\Interaction;
use \qtism\data\QtiComponentCollection;
use \qtism\data\AssessmentItem;

class ItemBuilderFactory
{
    public function getItemBuilder(AssessmentItem $assessmentItem)
    {
        $itemBody = $assessmentItem->getItemBody();
        $interactionComponents = $itemBody->getComponentsByClassName(Constants::$supportedInteractions, true);
        $itemBuilder = $this->createItemBuilderFromQtiInteractions($interactionComponents);

        // set the assessmentItem
        $itemBuilder->setAssessmentItem($assessmentItem);

        return $itemBuilder;
    }

    protected function createItemBuilderFromQtiInteractions(QtiComponentCollection $interactionComponents)
    {
        $interactionTypes = $this->getUniqueInteractionTypes($interactionComponents);
        if (count($interactionTypes) === 1 && in_array($interactionTypes[0], Constants::$needMergeInteractionTypes)) {
            return new MergedItemBuilder();
        }
        return new RegularItemBuilder();
    }

    private function getUniqueInteractionTypes(QtiComponentCollection $interactionComponents)
    {
        // Decide whether we shall merge interaction
        $interactionTypes = array_unique(array_map(function ($component) {
            /* @var $component Interaction */
            return $component->getQtiClassName();
        }, $interactionComponents->getArrayCopy()));

        return $interactionTypes;
    }
}
