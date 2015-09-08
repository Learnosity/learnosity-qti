<?php

namespace Learnosity\Tests\Integration\Processors\QtiV2\Out\QuestionTypes;

use qtism\data\AssessmentItem;
use qtism\data\content\interactions\TextEntryInteraction;

class ClozetextMapperTest extends AbstractQuestionTypeTest
{
    public function testSimpleCase()
    {
        /** @var AssessmentItem $assessmentItem */
        $question = json_decode($this->getFixtureFileContents('learnosityjsons/data_clozetext.json'), true);
        $assessmentItem = $this->convertToAssessmentItem($question);

        /** @var TextEntryInteraction $interactionOne */
        /** @var TextEntryInteraction $interactionTwo */
        $interactions = $assessmentItem->getComponentsByClassName('textEntryInteraction', true)->getArrayCopy();
        $interactionOne = $interactions[0];
        $interactionTwo = $interactions[1];
        $this->assertTrue($interactionOne instanceof TextEntryInteraction);
        $this->assertTrue($interactionTwo instanceof TextEntryInteraction);
    }
}
