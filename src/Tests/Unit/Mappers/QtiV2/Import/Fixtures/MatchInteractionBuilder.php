<?php

namespace Learnosity\Tests\Unit\Mappers\QtiV2\Import\Fixtures;


use qtism\data\content\FlowStaticCollection;
use qtism\data\content\interactions\MatchInteraction;
use qtism\data\content\interactions\SimpleAssociableChoice;
use qtism\data\content\interactions\SimpleAssociableChoiceCollection;
use qtism\data\content\interactions\SimpleMatchSet;
use qtism\data\content\interactions\SimpleMatchSetCollection;
use qtism\data\content\TextRun;

class MatchInteractionBuilder
{
    public static function buildMatchInteraction($identifier, array $matchSets)
    {
        assert(count($matchSets) === 2);
        $simpleMatchSetCollection = new SimpleMatchSetCollection();
        for ($i = 0; $i < count($matchSets); $i++) {

            $matchChoiceAssociation = new SimpleAssociableChoiceCollection();

            $matchMax = $i === 0 ? 1 : count($matchSets[$i]);

            foreach ($matchSets[$i] as $identifier => $value) {
                $choice = new SimpleAssociableChoice($identifier, $matchMax);
                $contentCollection = new FlowStaticCollection();
                $contentCollection->attach(new TextRun($value));
                $choice->setContent($contentCollection);
                $matchChoiceAssociation->attach($choice);
            }

            $simpleMatchSet = new SimpleMatchSet($matchChoiceAssociation);
            $simpleMatchSetCollection->attach($simpleMatchSet);

        }
        return new MatchInteraction($identifier, $simpleMatchSetCollection);
    }

}