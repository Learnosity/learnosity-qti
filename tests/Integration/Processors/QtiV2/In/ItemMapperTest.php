<?php

namespace LearnosityQti\Tests\Integration\Processors\QtiV2\In;

use LearnosityQti\AppContainer;
use LearnosityQti\Entities\Item\item;
use LearnosityQti\Entities\QuestionTypes\mcq;
use LearnosityQti\Tests\AbstractTest;

class ItemMapperTest extends AbstractTest
{
    public function testParsingWithAudioObject()
    {
        $xml = $this->getFixtureFileContents('interactions/audio.xml');
        $itemMapper = AppContainer::getApplicationContainer()->get('qtiv2_item_mapper');
        list($item, $questions) = $itemMapper->parse($xml);

        /** @var item $item */
        $this->assertTrue($item instanceof item);
        $this->assertCount(1, $questions);

        /** @var mcq $question */
        $question = $questions[0]->get_data();
        $this->assertTrue($question instanceof mcq);

        // Has feature on HTML
        $this->assertContains('<span class="learnosity-feature"', $question->get_stimulus());
        $this->assertContains('data-type="audioplayer"', $question->get_stimulus());
    }

    public function testParsingWithVideoObject()
    {
        $xml = $this->getFixtureFileContents('interactions/video.xml');
        $itemMapper = AppContainer::getApplicationContainer()->get('qtiv2_item_mapper');
        list($item, $questions) = $itemMapper->parse($xml);

        /** @var item $item */
        $this->assertTrue($item instanceof item);
        $this->assertCount(1, $questions);

        /** @var mcq $question */
        $question = $questions[0]->get_data();
        $this->assertTrue($question instanceof mcq);

        // Has feature on HTML
        $this->assertContains('<span class="learnosity-feature"', $question->get_stimulus());
        $this->assertContains('data-type="videoplayer"', $question->get_stimulus());
    }

    public function testParsingWithMathML()
    {
        $xml = $this->getFixtureFileContents('interactions/math.xml');
        $itemMapper = AppContainer::getApplicationContainer()->get('qtiv2_item_mapper');
        list($item, $questions) = $itemMapper->parse($xml);

        $this->assertTrue($item instanceof item);
        $this->assertCount(1, $questions);

        /** @var mcq $question */
        $question = $questions[0]->get_data();
        $this->assertTrue($question instanceof mcq);

        $this->assertTrue($question->get_is_math());
        $this->assertContains('<math>', $question->get_stimulus());
    }
}
