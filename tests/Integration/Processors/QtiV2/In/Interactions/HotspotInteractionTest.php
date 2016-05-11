<?php

namespace LearnosityQti\Tests\Integration\Mappers\QtiV2\In\Interactions;

use LearnosityQti\AppContainer;
use LearnosityQti\Tests\AbstractTest;

class HotspotInteractionTest extends AbstractTest
{
    public function testSimpleExample()
    {
        $mapper = AppContainer::getApplicationContainer()->get('qtiv2_item_mapper');
        list($item, $questions, $exceptions) = $mapper->parse($this->getFixtureFileContents('interactions/hotspot.xml'));

        $this->assertInstanceOf('LearnosityQti\Entities\Item\item', $item);
        $this->assertEquals('RESPONSE', $item->get_reference());
        $this->assertContains('<span class="learnosity-response question-RESPONSE"></span>', $item->get_content());
        $this->assertEquals('published', $item->get_status());
        $this->assertCount(1, $item->get_questionReferences());
        $this->assertContains('RESPONSE', $item->get_questionReferences());
    }

    public function testSimpleExample2()
    {
        $mapper = AppContainer::getApplicationContainer()->get('qtiv2_item_mapper');
        list($item, $questions, $exceptions)
            = $mapper->parse($this->getFixtureFileContents('interactions/hotspot2.xml'));

        
        
        
        $this->assertInstanceOf('LearnosityQti\Entities\Item\item', $item);
        $this->assertEquals('RESPONSE', $item->get_reference());
        $this->assertContains('<span class="learnosity-response question-RESPONSE"></span>', $item->get_content());
        $this->assertEquals('published', $item->get_status());
        $this->assertCount(1, $item->get_questionReferences());
        $this->assertContains('RESPONSE', $item->get_questionReferences());
    }
}
