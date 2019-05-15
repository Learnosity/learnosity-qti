<?php

namespace LearnosityQti\Tests\Integration\Processors\QtiV2\Out\QuestionTypes;

use LearnosityQti\Processors\QtiV2\Out\Constants;
use LearnosityQti\Utils\QtiMarshallerUtil;
use qtism\data\AssessmentItem;
use qtism\data\content\interactions\TextEntryInteraction;
use qtism\data\state\ResponseDeclaration;

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
            
            $this->assertCount(3, $assessmentItem->getResponseProcessing()->getComponents()); 
            // Assert response processing template
            //$this->assertEquals(Constants::RESPONSE_PROCESSING_TEMPLATE_MAP_RESPONSE, $assessmentItem->getResponseProcessing()->getTemplate());
    
            
        }
    }
}
