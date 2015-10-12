<?php

namespace Learnosity\Tests\Unit\Processors\QtiV2\In\Fixtures;


use qtism\data\content\FlowStaticCollection;
use qtism\data\content\interactions\MatchInteraction;
use qtism\data\content\interactions\SimpleAssociableChoice;
use qtism\data\content\interactions\SimpleAssociableChoiceCollection;
use qtism\data\content\interactions\SimpleMatchSet;
use qtism\data\content\interactions\SimpleMatchSetCollection;
use qtism\data\content\TextRun;

class MatchInteractionBuilder
{
    public static function buildMatchInteraction($identifier, array $matchSets, $isMultipleResponses = false)
    {
        assert(count($matchSets) === 2);
        $simpleMatchSetCollection = new SimpleMatchSetCollection();

        // Build source choices (`stems`)
        $simpleMatchSetCollection->attach(self::buildSimpleMatchSet($matchSets[0], 1));
        // Build target choices (`options`)
        $targetMatchMax = $isMultipleResponses ? count($matchSets[1]) : 1;
        $simpleMatchSetCollection->attach(self::buildSimpleMatchSet($matchSets[1], $targetMatchMax));

        return new MatchInteraction($identifier, $simpleMatchSetCollection);
    }

    private static function buildSimpleMatchSet(array $matchSets, $matchMax)
    {
        $matchChoiceAssociation = new SimpleAssociableChoiceCollection();
        foreach ($matchSets as $identifier => $value) {
            $choice = new SimpleAssociableChoice($identifier, $matchMax);
            $contentCollection = new FlowStaticCollection();
            $contentCollection->attach(new TextRun($value));
            $choice->setContent($contentCollection);
            $matchChoiceAssociation->attach($choice);
        }
        return new SimpleMatchSet($matchChoiceAssociation);
    }
}
