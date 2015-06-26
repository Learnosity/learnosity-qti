<?php

namespace Learnosity\Mappers\QtiV2\Import;

use Learnosity\Mappers\QtiV2\Import\ItemBuilders\MergedItemBuilder;
use Learnosity\Mappers\QtiV2\Import\ItemBuilders\RegularItemBuilder;
use qtism\data\content\interactions\Interaction;
use qtism\data\QtiComponentCollection;

class ItemBuilderFactory
{
    public function getItemBuilder(QtiComponentCollection $interactionComponents)
    {
        $possibleMergedInteractionTypes = ['textEntryInteraction', 'inlineChoiceInteraction'];
        $interactionTypes = self::getUniqueInteractionTypes($interactionComponents);
        if (count($interactionTypes) === 1 && in_array($interactionTypes[0], $possibleMergedInteractionTypes)) {
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
