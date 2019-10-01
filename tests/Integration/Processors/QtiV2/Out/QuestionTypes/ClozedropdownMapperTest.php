<?php

namespace LearnosityQti\Tests\Integration\Processors\QtiV2\Out\QuestionTypes;

use LearnosityQti\Processors\QtiV2\Out\Constants;
use LearnosityQti\Utils\QtiMarshallerUtil;
use qtism\data\AssessmentItem;
use qtism\data\content\interactions\InlineChoiceInteraction;
use qtism\data\rules\ResponseCondition;
use qtism\data\rules\SetOutcomeValue;
use qtism\data\state\ResponseDeclaration;
use ReflectionProperty;

class ClozedropdownMapperTest extends AbstractQuestionTypeTest
{
    public function testSimpleCase()
    {
        /** @var AssessmentItem $assessmentItem */
        $question = json_decode($this->getFixtureFileContents('learnosityjsons/data_clozedropdown.json'), true);
        $assessmentItemArray = $this->convertToAssessmentItem($question);
        foreach ($assessmentItemArray as $assessmentItem) {
            
            $interactions = $assessmentItem->getComponentsByClassName('inlineChoiceInteraction', true)->getArrayCopy();
            /** @var InlineChoiceInteraction $interactionOne */
            $interactionOne = $interactions[0];
            
            $this->assertTrue($interactionOne instanceof InlineChoiceInteraction);
            
            $content = QtiMarshallerUtil::marshallCollection($assessmentItem->getItemBody()->getContent());
            $this->assertNotEmpty($content);

            // Assert response processing template
            $this->assertEquals(Constants::RESPONSE_PROCESSING_TEMPLATE_MATCH_CORRECT, $assessmentItem->getResponseProcessing()->getTemplate());
            
            // Assert response declarations
            $responseDeclarations = $assessmentItem->getResponseDeclarations()->getArrayCopy();
            /** @var ResponseDeclaration $responseDeclarationOne */
            $responseDeclarationOne = $responseDeclarations[0];
            
            // Check has the correct identifiers, also correct `correctResponse` values
            $this->assertEquals($responseDeclarationOne->getIdentifier(), $interactionOne->getResponseIdentifier());
            $this->assertNull($responseDeclarationOne->getMapping());
            $this->assertEquals('INLINECHOICE_0', $responseDeclarationOne->getCorrectResponse()->getValues()->getArrayCopy()[0]->getValue());
            $this->assertEquals('Vegetable', QtiMarshallerUtil::marshallCollection($interactionOne->getComponentByIdentifier('INLINECHOICE_0')->getComponents()));

        }
    }
	
	public function testWithDistractorRationale()
    {
        /** @var AssessmentItem $assessmentItem */
        $question = json_decode($this->getFixtureFileContents('learnosityjsons/cloze_dropdown.json'), true);
        $assessmentItemArray = $this->convertToAssessmentItem($question);
        
		foreach ($assessmentItemArray as $assessmentItem) {
            
            $interactions = $assessmentItem->getComponentsByClassName('inlineChoiceInteraction', true)->getArrayCopy();
            /** @var InlineChoiceInteraction $interactionOne */
            $interactionOne = $interactions[0];
            /** @var InlineChoiceInteraction $interactionTwo */
            $interactionTwo = $interactions[1];
            /** @var InlineChoiceInteraction $interactionTwo */
            $interactionThree = $interactions[2];
            $this->assertTrue($interactionOne instanceof InlineChoiceInteraction);
            $this->assertTrue($interactionTwo instanceof InlineChoiceInteraction);
            $this->assertTrue($interactionThree instanceof InlineChoiceInteraction);

            $content = QtiMarshallerUtil::marshallCollection($assessmentItem->getItemBody()->getContent());
            $this->assertNotEmpty($content);

            // Assert response processing template
            $this->assertCount(2, $assessmentItem->getResponseProcessing()->getComponents());

            // Assert response declarations
            $responseDeclarations = $assessmentItem->getResponseDeclarations()->getArrayCopy();
            /** @var ResponseDeclaration $responseDeclarationOne */
            $responseDeclarationOne = $responseDeclarations[0];
            /** @var ResponseDeclaration $responseDeclarationTwo */
            $responseDeclarationTwo = $responseDeclarations[1];
            /** @var ResponseDeclaration $responseDeclarationTwo */
            $responseDeclarationThree = $responseDeclarations[2];

            // Check has the correct identifiers, also correct `correctResponse` values
            $this->assertEquals($responseDeclarationOne->getIdentifier(), $interactionOne->getResponseIdentifier());
            $this->assertNull($responseDeclarationOne->getMapping());
            $this->assertEquals('INLINECHOICE_0', $responseDeclarationOne->getCorrectResponse()->getValues()->getArrayCopy()[0]->getValue());
            $this->assertEquals('Vegetable', QtiMarshallerUtil::marshallCollection($interactionOne->getComponentByIdentifier('INLINECHOICE_0')->getComponents()));

            $this->assertEquals($responseDeclarationTwo->getIdentifier(), $interactionTwo->getResponseIdentifier());
            $this->assertNull($responseDeclarationTwo->getMapping());
            $this->assertEquals('INLINECHOICE_0', $responseDeclarationTwo->getCorrectResponse()->getValues()->getArrayCopy()[0]->getValue());
            $this->assertEquals('Fruit', QtiMarshallerUtil::marshallCollection($interactionTwo->getComponentByIdentifier('INLINECHOICE_0')->getComponents()));
            
            $this->assertEquals($responseDeclarationThree->getIdentifier(), $interactionThree->getResponseIdentifier());
            $this->assertNull($responseDeclarationThree->getMapping());
            $this->assertEquals('INLINECHOICE_0', $responseDeclarationThree->getCorrectResponse()->getValues()->getArrayCopy()[0]->getValue());
            $this->assertEquals('Color', QtiMarshallerUtil::marshallCollection($interactionThree->getComponentByIdentifier('INLINECHOICE_0')->getComponents()));
    
            $this->assertCount(2, $assessmentItem->getResponseProcessing()->getComponents()); 
            
            // check count of responseProcessing object
            $responseRules = $assessmentItem->getResponseProcessing()->getComponents();
            $responseRuleOne = $responseRules[0];
            $responseRuleTwo = $responseRules[1];
            
            // check assert for responseProcessing 
            $this->assertTrue($responseRuleOne instanceof ResponseCondition);
            $this->assertTrue($responseRuleTwo instanceof SetOutcomeValue);
        }
    }
}
