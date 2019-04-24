<?php

namespace LearnosityQti\Tests\Integration\Processors\QtiV2\Out\QuestionTypes;

use LearnosityQti\Utils\QtiMarshallerUtil;
use qtism\data\content\interactions\ExtendedTextInteraction;
use qtism\data\content\interactions\TextFormat;

class LongtextMapperTest extends AbstractQuestionTypeTest
{
    public function testVerySimpleCase()
    {
        $data = json_decode($this->getFixtureFileContents('learnosityjsons/longtext.json'), true);
        $assessmentItemArray = $this->convertToAssessmentItem($data);

        foreach($assessmentItemArray as $assessmentItem){
            
            // Longtext shall have no <responseDeclaration> and <responseProcessing>
            $this->assertEquals(1, $assessmentItem->getResponseDeclarations()->count());
            $this->assertNotNull($assessmentItem->getResponseProcessing());

            // Has <extendedTextInteraction> as the first and only interaction
            /** @var ExtendedTextInteraction $interaction */
            $interaction = $assessmentItem->getComponentsByClassName('extendedTextInteraction', true)->getArrayCopy()[0];
            $this->assertTrue($interaction instanceof ExtendedTextInteraction);

            // And its prompt is mapped correctly
            $promptString = QtiMarshallerUtil::marshallCollection($interaction->getPrompt()->getComponents());
            $this->assertEquals('<p>[This is the stem.]</p>', trim($promptString));

            // And it is a HTML text by default
            $this->assertEquals(TextFormat::XHTML, $interaction->getFormat());
        }
    }
}
