<?php

namespace LearnosityQti\Tests\Integration\Processors\QtiV2\In\Interactions;

use LearnosityQti\AppContainer;
use LearnosityQti\Entities\QuestionTypes\hotspot;
use LearnosityQti\Tests\AbstractTest;

class HotspotInteractionTest extends AbstractTest
{
    public function testSimpleExampleWithCircularsShape()
    {
        $mapper = AppContainer::getApplicationContainer()->get('qtiv2_item_mapper');
        list($item, $questions, $exceptions) = $mapper->parse($this->getFixtureFileContents('interactions/hotspot.xml'));

        $this->assertInstanceOf('LearnosityQti\Entities\Item\item', $item);
        $this->assertEquals('hotspot', $item->get_reference());
        $this->assertContains('<span class="learnosity-response question-hotspot_RESPONSE"></span>', $item->get_content());
        $this->assertEquals('published', $item->get_status());
        $this->assertCount(1, $item->get_questionReferences());
        $this->assertContains('hotspot_RESPONSE', $item->get_questionReferences());

        /** @var hotspot $question */
        $question = $questions[0];
        $this->assertInstanceOf('LearnosityQti\Entities\Question', $question);
        $this->assertInstanceOf('LearnosityQti\Entities\QuestionTypes\hotspot', $question->get_data());
        $this->assertEquals('hotspot', $question->get_type());

        $validation = $question->get_data()->get_validation();
        $this->assertNotNull($validation);
        $this->assertEquals('exactMatch', $validation->get_scoring_type());
        $this->assertNotNull($validation->get_valid_response());
    }
}
