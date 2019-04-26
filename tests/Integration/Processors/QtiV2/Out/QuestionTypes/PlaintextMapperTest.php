<?php

namespace LearnosityQti\Tests\Integration\Processors\QtiV2\Out\QuestionTypes;

use LearnosityQti\Utils\QtiMarshallerUtil;
use qtism\data\content\interactions\ExtendedTextInteraction;
use qtism\data\content\interactions\TextFormat;
use qtism\data\rules\ResponseElse;
use qtism\data\rules\ResponseIf;

class PlaintextMapperTest extends AbstractQuestionTypeTest
{
    public function testSimpleWithNoValidation()
    {
        $data = json_decode($this->getFixtureFileContents('learnosityjsons/item_plaintext.json'), true);
        $assessmentItemArray = $this->convertToAssessmentItem($data);


        foreach($assessmentItemArray as $assessmentItem) {
            $this->assertEquals(0, $assessmentItem->getResponseDeclarations()->count());
            $this->assertNull($assessmentItem->getResponseProcessing());


            // Has <extendedTextInteraction> as the first and only interaction
            /** @var ExtendedTextInteraction $interaction */
            $interaction = $assessmentItem->getComponentsByClassName('extendedTextInteraction', true)->getArrayCopy()[0];
            $this->assertTrue($interaction instanceof ExtendedTextInteraction);

            // And its prompt is mapped correctly
            $promptString = QtiMarshallerUtil::marshallCollection($interaction->getPrompt()->getComponents());
            $this->assertEquals('<p>Write an essay</p>', trim($promptString));

            // And it is a HTML text by default
            $this->assertEquals(TextFormat::PLAIN, $interaction->getFormat());
        }
    }
    
    public function testSimpleWithValidation()
    {
        $data = json_decode($this->getFixtureFileContents('learnosityjsons/item_plaintext_withvalidation.json'), true);
        $assessmentItemArray = $this->convertToAssessmentItem($data);


        foreach($assessmentItemArray as $assessmentItem) {
            $this->assertEquals(1, $assessmentItem->getResponseDeclarations()->count());
            $this->assertNotNull($assessmentItem->getResponseProcessing());
            
            // Has <extendedTextInteraction> as the first and only interaction
            /** @var ExtendedTextInteraction $interaction */
            $interaction = $assessmentItem->getComponentsByClassName('extendedTextInteraction', true)->getArrayCopy()[0];
            $this->assertTrue($interaction instanceof ExtendedTextInteraction);
            
            // And its prompt is mapped correctly
            $promptString = QtiMarshallerUtil::marshallCollection($interaction->getPrompt()->getComponents());
            $this->assertEquals('Write an short essay on India -', trim($promptString));
            $this->assertEquals('Please write somethig', $interaction->getPlaceholderText());
            $this->assertEquals(1000, $interaction->getExpectedLength());
            $this->assertEquals(1000, $interaction->getMaxStrings());
            // And it is a HTML text by default
            $this->assertEquals(TextFormat::PLAIN, $interaction->getFormat());
        }
    }
}
