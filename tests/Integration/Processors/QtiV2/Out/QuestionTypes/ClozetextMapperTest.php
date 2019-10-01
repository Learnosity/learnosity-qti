<?php

namespace LearnosityQti\Tests\Integration\Processors\QtiV2\Out\QuestionTypes;

use LearnosityQti\Utils\QtiMarshallerUtil;
use qtism\data\AssessmentItem;
use qtism\data\content\interactions\TextEntryInteraction;
use qtism\data\content\ModalFeedbackCollection;
use qtism\data\content\ModalFeedback;
use qtism\data\rules\ResponseElse;
use qtism\data\rules\ResponseIf;
use qtism\data\state\ResponseDeclaration;
use ReflectionProperty;

class ClozetextMapperTest extends AbstractQuestionTypeTest
{
    public function testSimpleCase()
    {
        /** @var AssessmentItem $assessmentItem */
        $question = json_decode($this->getFixtureFileContents('learnosityjsons/data_clozetext.json'), true);
        $assessmentItemArray = $this->convertToAssessmentItem($question);
        foreach ($assessmentItemArray as $assessmentItem) {
            $interactions = $assessmentItem->getComponentsByClassName('textEntryInteraction', true)->getArrayCopy();
            /** @var TextEntryInteraction $interactionOne */
            $interactionOne = $interactions[0];
            /** @var TextEntryInteraction $interactionTwo */
            $interactionTwo = $interactions[1];
            $this->assertTrue($interactionOne instanceof TextEntryInteraction);
            $this->assertTrue($interactionTwo instanceof TextEntryInteraction);
            $this->assertEquals(15, $interactionOne->getExpectedLength());
            $this->assertEquals(15, $interactionTwo->getExpectedLength());

            $content = QtiMarshallerUtil::marshallCollection($assessmentItem->getItemBody()->getContent());
            $this->assertNotEmpty($content);

            // Assert response declarations
            $responseDeclarations = $assessmentItem->getResponseDeclarations()->getArrayCopy();
            /** @var ResponseDeclaration $responseDeclarationOne */
            $responseDeclarationOne = $responseDeclarations[0];
            /** @var ResponseDeclaration $responseDeclarationTwo */
            $responseDeclarationTwo = $responseDeclarations[1];

            // Check has the correct identifiers
            $this->assertEquals($responseDeclarationOne->getIdentifier(), $interactionOne->getResponseIdentifier());
            $this->assertEquals($responseDeclarationTwo->getIdentifier(), $interactionTwo->getResponseIdentifier());
            // Also correct `correctResponse` values
            $this->assertEquals('response1', $responseDeclarationOne->getCorrectResponse()->getValues()->getArrayCopy()[0]->getValue());
            $this->assertEquals('response2', $responseDeclarationTwo->getCorrectResponse()->getValues()->getArrayCopy()[0]->getValue());
            
            // Also correct `mapping` entries
            $this->assertEquals('response1', $responseDeclarationOne->getMapping()->getMapEntries()->getArrayCopy()[0]->getMapKey());
            $this->assertEquals(2.0, $responseDeclarationOne->getMapping()->getMapEntries()->getArrayCopy()[0]->getMappedValue());
            
            $this->assertEquals('response2', $responseDeclarationTwo->getMapping()->getMapEntries()->getArrayCopy()[0]->getMapKey());
            $this->assertEquals(2.0, $responseDeclarationTwo->getMapping()->getMapEntries()->getArrayCopy()[0]->getMappedValue());
            
            $this->assertCount(1, $assessmentItem->getResponseProcessing()->getComponents()); 
        }
    }
	
	public function testWithDistractorRationale()
    {
        /** @var AssessmentItem $assessmentItem */
        $question = json_decode($this->getFixtureFileContents('learnosityjsons/clozetext.json'), true);
        /*$mock = $this->getMock('ConvertToQtiService', array('getFormat'));
            
	    // Replace protected self reference with mock object
        $ref = new ReflectionProperty('LearnosityQti\Services\ConvertToQtiService', 'instance');
	    $ref->setAccessible(true);
	    $ref->setValue(null, $mock);*/
            
        /*$format = $mock->expects($this->once())
				->method('getFormat')
				->will($this->returnValue('qti'));*/
		
		$assessmentItemArray = $this->convertToAssessmentItem($question);
        foreach ($assessmentItemArray as $assessmentItem) {
            $interactions = $assessmentItem->getComponentsByClassName('textEntryInteraction', true)->getArrayCopy();
            /** @var TextEntryInteraction $interactionOne */
            $interactionOne = $interactions[0];
            /** @var TextEntryInteraction $interactionTwo */
            $interactionTwo = $interactions[1];
            $this->assertTrue($interactionOne instanceof TextEntryInteraction);
            $this->assertTrue($interactionTwo instanceof TextEntryInteraction);
            $this->assertEquals(15, $interactionOne->getExpectedLength());
            $this->assertEquals(15, $interactionTwo->getExpectedLength());

            $content = QtiMarshallerUtil::marshallCollection($assessmentItem->getItemBody()->getContent());
            $this->assertNotEmpty($content);

            // Assert response declarations
            $responseDeclarations = $assessmentItem->getResponseDeclarations()->getArrayCopy();
            /** @var ResponseDeclaration $responseDeclarationOne */
            $responseDeclarationOne = $responseDeclarations[0];
            /** @var ResponseDeclaration $responseDeclarationTwo */
            $responseDeclarationTwo = $responseDeclarations[1];

            // Check has the correct identifiers
            $this->assertEquals($responseDeclarationOne->getIdentifier(), $interactionOne->getResponseIdentifier());
            $this->assertEquals($responseDeclarationTwo->getIdentifier(), $interactionTwo->getResponseIdentifier());
            // Also correct `correctResponse` values
            $this->assertEquals('response1', $responseDeclarationOne->getCorrectResponse()->getValues()->getArrayCopy()[0]->getValue());
            $this->assertEquals('response2', $responseDeclarationTwo->getCorrectResponse()->getValues()->getArrayCopy()[0]->getValue());
            
            // Also correct `mapping` entries
            $this->assertEquals('response1', $responseDeclarationOne->getMapping()->getMapEntries()->getArrayCopy()[0]->getMapKey());
            $this->assertEquals(2.0, $responseDeclarationOne->getMapping()->getMapEntries()->getArrayCopy()[0]->getMappedValue());
            
            $this->assertEquals('response2', $responseDeclarationTwo->getMapping()->getMapEntries()->getArrayCopy()[0]->getMapKey());
            $this->assertEquals(2.0, $responseDeclarationTwo->getMapping()->getMapEntries()->getArrayCopy()[0]->getMappedValue());
             
            $this->assertCount(2,$assessmentItem->getResponseProcessing()->getComponents());
            $this->assertCount(1, $assessmentItem->getResponseProcessing()->getComponentsByClassName('responseIf', true));
            $responseIf = $assessmentItem->getResponseProcessing()->getComponentsByClassName('responseIf', true)->getArrayCopy()[0];
            $this->assertTrue($responseIf instanceof ResponseIf);
            $promptIfString = QtiMarshallerUtil::marshallCollection($responseIf->getComponents());
            $this->assertEquals('<and><match><variable identifier="RESPONSE_0"/><correct identifier="RESPONSE_0"/></match><match><variable identifier="RESPONSE_1"/><correct identifier="RESPONSE_1"/></match></and><setOutcomeValue identifier="SCORE"><baseValue baseType="float">2</baseValue></setOutcomeValue>', $promptIfString);
            
            $this->assertCount(1, $assessmentItem->getResponseProcessing()->getComponentsByClassName('responseElse', true));
            $responseElse = $assessmentItem->getResponseProcessing()->getComponentsByClassName('responseElse', true)->getArrayCopy()[0];
            $this->assertTrue($responseElse instanceof ResponseElse);
            $promptElseString = QtiMarshallerUtil::marshallCollection($responseElse->getComponents());
            $this->assertEquals('<setOutcomeValue identifier="SCORE"><baseValue baseType="float">-1</baseValue></setOutcomeValue>', $promptElseString);
            
            $modalFeedBackCollections = $assessmentItem->getModalFeedbacks();
            $this->assertTrue($modalFeedBackCollections instanceof ModalFeedbackCollection);
            foreach($modalFeedBackCollections as $modalFeedback) {
                $this->assertTrue($modalFeedback instanceof ModalFeedback);
                $promptFeedbackString = $modalFeedback->getComponents()[0]->getContent();
                $this->assertEquals('Thanks for attending this test.', $promptFeedbackString);
            }
        }
    }
}
