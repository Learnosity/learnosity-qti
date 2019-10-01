<?php

namespace LearnosityQti\Tests\Integration\Processors\QtiV2\Out\QuestionTypes;

use LearnosityQti\Processors\QtiV2\Out\Constants;
use LearnosityQti\Utils\QtiMarshallerUtil;
use qtism\data\content\interactions\TextEntryInteraction;
use qtism\data\content\ModalFeedback;
use qtism\data\state\MapEntry;
use qtism\data\state\ResponseDeclaration;
use qtism\data\state\Value;

class ShorttextMapperTest extends AbstractQuestionTypeTest
{
    public function testShorttextQuestionWithSimpleValidation()
    {
        $data = json_decode($this->getFixtureFileContents('learnosityjsons/shorttext.json'), true);
        $assessmentItemArray = $this->convertToAssessmentItem($data);

        // Has <textEntryInteraction> as the first and only interaction
        /** @var TextEntryInteraction $interaction */
        foreach ($assessmentItemArray as $assessmentItem) {
            $interaction = $assessmentItem->getComponentsByClassName('textEntryInteraction', true)->getArrayCopy()[0];

            // Test basic attributes
            $this->assertTrue($interaction instanceof TextEntryInteraction);
            $this->assertEquals('Please answer the question', $interaction->getPlaceholderText());
            $this->assertEquals(15, $interaction->getExpectedLength());

            // Shorttext shall have one simple `map_response` <responseDeclaration> and <responseProcessing>
            /** @var ResponseDeclaration $responseDeclaration */
            $responseDeclaration = $assessmentItem->getResponseDeclarations()->getArrayCopy()[0];
            
            /** @var Value[] $values */
            $values = $responseDeclaration->getCorrectResponse()->getValues()->getArrayCopy(true);
            $this->assertEquals('Canbera', $values[0]->getValue());
            //$this->assertEquals('anotherhello', $values[1]->getValue());

            /** @var MapEntry[] $mapEntries */
            $mapEntries = $responseDeclaration->getMapping()->getMapEntries()->getArrayCopy(true);
            $this->assertEquals('Canbera', $mapEntries[0]->getMapKey());
            $this->assertEquals(10, $mapEntries[0]->getMappedValue());
            
            // Check itembody is correct that the stimulus is appended before
            $itemBodyContent = QtiMarshallerUtil::marshallCollection($assessmentItem->getItemBody()->getComponents());
            $expectedString = '<div class="row">
      <div class="col-xs-12">
        <div>
          <div>What is the capital of Australia</div>
          <textEntryInteraction responseIdentifier="RESPONSE" expectedLength="15" placeholderText="Please answer the question" label="1c72f3e5-4445-4af9-bf06-4e8b26c0d1fe"/>
        </div>
      </div>
    </div>';
            $this->assertEquals($expectedString, $itemBodyContent);
            
        }
    }
    
    public function testShorttextQuestionWithSimpleValidationAndFeedback()
    {
        $data = json_decode($this->getFixtureFileContents('learnosityjsons/shorttext_feedback.json'), true);
        $assessmentItemArray = $this->convertToAssessmentItem($data);

        // Has <textEntryInteraction> as the first and only interaction
        /** @var TextEntryInteraction $interaction */
        foreach ($assessmentItemArray as $assessmentItem) {
            $interaction = $assessmentItem->getComponentsByClassName('textEntryInteraction', true)->getArrayCopy()[0];

            // Test basic attributes
            $this->assertTrue($interaction instanceof TextEntryInteraction);
            $this->assertEquals('Please answer the question', $interaction->getPlaceholderText());
            $this->assertEquals(15, $interaction->getExpectedLength());

            // Shorttext shall have one simple `map_response` <responseDeclaration> and <responseProcessing>
            /** @var ResponseDeclaration $responseDeclaration */
            $responseDeclaration = $assessmentItem->getResponseDeclarations()->getArrayCopy()[0];
            
            $modalFeedBack = $assessmentItem->getComponentsByClassName('modalFeedback', true)->getArrayCopy()[0];
            $this->assertTrue($modalFeedBack instanceof ModalFeedback);
            $this->assertEquals('correctOrIncorrect', $modalFeedBack->getIdentifier());
            $this->assertEquals('This is general feedback', $modalFeedBack->getContent()[0]->getContent());
            $this->assertEquals(0, $modalFeedBack->getShowHide());
            $this->assertEquals('FEEDBACK_GENERAL', $modalFeedBack->getOutcomeIdentifier());
            
            /** @var Value[] $values */
            $values = $responseDeclaration->getCorrectResponse()->getValues()->getArrayCopy(true);
            $this->assertEquals('Canbera', $values[0]->getValue());
            //$this->assertEquals('anotherhello', $values[1]->getValue());

            /** @var MapEntry[] $mapEntries */
            $mapEntries = $responseDeclaration->getMapping()->getMapEntries()->getArrayCopy(true);
            $this->assertEquals('Canbera', $mapEntries[0]->getMapKey());
            $this->assertEquals(10, $mapEntries[0]->getMappedValue());
        }
    }
}
