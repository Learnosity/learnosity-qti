<?php

namespace LearnosityQti\Tests\Unit\Processors\QtiV2\In\Fixtures;


use qtism\data\content\FlowStaticCollection;
use qtism\data\content\interactions\OrderInteraction;
use qtism\data\content\interactions\Prompt;
use qtism\data\content\interactions\SimpleChoice;
use qtism\data\content\interactions\SimpleChoiceCollection;
use qtism\data\content\TextRun;

class OrderInteractionBuilder
{
    public static function buildOrderInteraction($identifier, $choices, $promptText = null)
    {
        $simpleChoiceCollection = new SimpleChoiceCollection();
        foreach ($choices as $identifier => $value) {
            $simpleChoice = new SimpleChoice($identifier);
            $contentCollection = new FlowStaticCollection();
            $contentCollection->attach(new TextRun($value));
            $simpleChoice->setContent($contentCollection);
            $simpleChoiceCollection->attach($simpleChoice);
        }

        $orderInteraction = new OrderInteraction($identifier, $simpleChoiceCollection);
        if ($promptText) {
            $prompt = new Prompt();
            $contentCollection = new FlowStaticCollection();
            $contentCollection->attach(new TextRun($promptText));
            $prompt->setContent($contentCollection);
            $orderInteraction->setPrompt($prompt);
        }
        return $orderInteraction;
    }
}
