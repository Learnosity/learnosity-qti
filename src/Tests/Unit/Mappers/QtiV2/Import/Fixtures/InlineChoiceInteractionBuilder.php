<?php

namespace Learnosity\Tests\Unit\Mappers\QtiV2\Import\Fixtures;

use qtism\data\content\interactions\InlineChoice;
use qtism\data\content\interactions\InlineChoiceCollection;
use qtism\data\content\interactions\InlineChoiceInteraction;
use qtism\data\content\TextOrVariableCollection;
use qtism\data\content\TextRun;

class InlineChoiceInteractionBuilder
{
    public static function buildSimple($responseIdentifier, array $identifierTextRunContentMap)
    {
        $collection = new InlineChoiceCollection();
        foreach ($identifierTextRunContentMap as $identifier => $textRunContent) {
            $inlineChoice = new InlineChoice($identifier);
            $textOrVariableCollection = new TextOrVariableCollection();
            $textOrVariableCollection->attach(new TextRun($textRunContent));
            $inlineChoice->setContent($textOrVariableCollection);
            $collection->attach($inlineChoice);
        }
        return new InlineChoiceInteraction($responseIdentifier, $collection);
    }
} 
