<?php

namespace Learnosity\Tests\Integration\Processors\QtiV2\Out;

use Learnosity\Tests\Integration\Processors\QtiV2\Out\QuestionTypes\AbstractQuestionTypeTest;

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
    }

    public function testMappingItemWithEmbeddedFeatures()
    {
        $data = json_decode($this->getFixtureFileContents('learnosityjsons/item_longtext_audioplayer.json'), true);
        $assessmentItem = $this->convertToAssessmentItem($data);
    }
}
