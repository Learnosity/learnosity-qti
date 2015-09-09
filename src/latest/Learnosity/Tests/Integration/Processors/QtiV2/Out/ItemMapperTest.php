<?php

namespace Learnosity\Tests\Integration\Processors\QtiV2\Out;

use Learnosity\Tests\Integration\Processors\QtiV2\Out\QuestionTypes\AbstractQuestionTypeTest;
use qtism\data\content\interactions\ChoiceInteraction;
use qtism\data\content\interactions\ExtendedTextInteraction;

class ItemMapperTest extends AbstractQuestionTypeTest
{
    public function testMappingItemWithMultipleQuestions()
    {
        $this->markTestIncomplete();

        $data = json_decode($this->getFixtureFileContents('learnosityjsons/item_shorttext_orderlist.json'), true);
        $assessmentItem = $this->convertToAssessmentItem($data);
    }

    public function testMappingItemWithInlineFeatures()
    {
        $data = json_decode($this->getFixtureFileContents('learnosityjsons/mcq_with_inlineaudio.json'), true);
        $assessmentItem = $this->convertToAssessmentItem($data);

        /** @var ChoiceInteraction $interaction */
        $interaction = $assessmentItem->getItemBody()->getComponentsByClassName('choiceInteraction', true)->getArrayCopy()[0];
        /** @var Object $object */
        $object = $interaction->getPrompt()->getComponentsByClassName('object', true)->getArrayCopy()[0];

        $this->assertEquals('http://www.kozco.com/tech/LRMonoPhase4.wav', $object->getData());
        $this->assertEquals('audio/x-wav', $object->getType());
    }

    public function testMappingItemWithRegularFeatures()
    {
        $data = json_decode($this->getFixtureFileContents('learnosityjsons/item_longtext_audioplayer.json'), true);
        $assessmentItem = $this->convertToAssessmentItem($data);
        $itemBody = $assessmentItem->getItemBody();

        /** @var ExtendedTextInteraction $interaction */
        $interaction = $itemBody->getComponentsByClassName('extendedTextInteraction', true)->getArrayCopy()[0];
        $this->assertTrue($interaction instanceof ExtendedTextInteraction);

        /** @var Object $object */
        $object = $interaction->getPrompt()->getComponentsByClassName('object', true)->getArrayCopy()[0];

        $this->assertEquals('https://s3.amazonaws.com/assets.learnosity.com/demos/docs/audiofeaturedemo.mp3', $object->getData());
        $this->assertEquals('audio/x-wav', $object->getType());
    }
}
