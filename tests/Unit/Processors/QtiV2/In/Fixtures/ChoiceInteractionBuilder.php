<?php

namespace LearnosityQti\Tests\Unit\Processors\QtiV2\In\Fixtures;

use qtism\data\content\FlowStaticCollection;
use qtism\data\content\interactions\ChoiceInteraction;
use qtism\data\content\interactions\SimpleChoice;
use qtism\data\content\interactions\SimpleChoiceCollection;
use qtism\data\content\TextRun;

class ChoiceInteractionBuilder
{
    public static function buildSimple($responseIdentifier, array $identifierLabelMap)
    {
        $collection = new SimpleChoiceCollection();
        foreach ($identifierLabelMap as $identifier => $label) {
            $choice = new SimpleChoice($identifier);
            $content = new FlowStaticCollection();
            $content->attach(new TextRun($label));
            $choice->setContent($content);
            $collection->attach($choice);
        }
        return new ChoiceInteraction($responseIdentifier, $collection);
    }
}
