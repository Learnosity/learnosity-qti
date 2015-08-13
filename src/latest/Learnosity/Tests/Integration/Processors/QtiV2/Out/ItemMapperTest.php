<?php

namespace latest\Learnosity\Tests\Integration\Processors\QtiV2\Out;

use Learnosity\Tests\Integration\Processors\QtiV2\Out\QuestionTypes\AbstractQuestionTypeTest;

class ItemMapperTest extends AbstractQuestionTypeTest
{
    public function testMappingItemWithMultipleQuestions()
    {
        $data = json_decode($this->getFixtureFileContents('learnosityjsons/item_shorttext_orderlist.json'), true);
        $assessmentItem = $this->convertToAssessmentItem($data);

        die;
    }
}
