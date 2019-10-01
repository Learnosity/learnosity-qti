<?php

namespace LearnosityQti\Tests\Unit\Processors\QtiV2\Out\QuestionTypes;

use LearnosityQti\Entities\QuestionTypes\clozedropdown;
use LearnosityQti\Entities\QuestionTypes\clozedropdown_metadata;
use LearnosityQti\Entities\QuestionTypes\clozedropdown_validation;
use LearnosityQti\Entities\QuestionTypes\clozedropdown_validation_valid_response;
use LearnosityQti\Processors\QtiV2\Out\QuestionTypes\ClozedropdownMapper;
use LearnosityQti\Utils\QtiMarshallerUtil;
use qtism\data\content\interactions\InlineChoiceInteraction;
use qtism\data\rules\ResponseCondition;
use qtism\data\state\ResponseDeclaration;
use qtism\data\rules\SetOutcomeValue;

class ClozedropdownMapperTest extends \PHPUnit_Framework_TestCase
{

    public function testSimpleCase()
    {
        
        $stimulus = '<strong>This is a clozetext dropdown question</strong>';
        $ques_template = '<p>Potato is a {{response}}, Guava is a {{response}} and red is a {{response}}</p>';
        $possible_responses = [
                    ["Vegetable","Color","Snacks","Fruit"],
                    ["Fruit","Vegetable","Color"],
                    ["Color","Fruit"]
                ];
        $question = new clozedropdown('clozedropdown', $ques_template, $possible_responses);
        $question->set_stimulus($stimulus);
        
        // add valid_responses
        $validation = $this->addValidResponse();
        $question->set_validation($validation);
        
        $clozedropdown  = new ClozedropdownMapper();
        /** @var InlineChoiceInteraction $interaction */
        list($interaction, $responseDeclaration, $responseProcessing) = $clozedropdown->convert($question, 'testIdentifier', 'testIdentifierLabel');
        
        $interactions = $interaction->getComponentsByClassName('inlineChoiceInteraction', true)->getArrayCopy();
        
        /** @var InlineChoiceInteraction $interactionOne */
        $interactionOne = $interactions[0];
        /** @var InlineChoiceInteraction $interactionTwo */
        $interactionTwo = $interactions[1];
        /** @var InlineChoiceInteraction $interactionThree */
        $interactionThree = $interactions[2];
        $this->assertTrue($interactionOne instanceof InlineChoiceInteraction);
        $this->assertTrue($interactionTwo instanceof InlineChoiceInteraction);
        $this->assertTrue($interactionThree instanceof InlineChoiceInteraction);
       
        // Assert response declarations
        /** @var ResponseDeclaration $responseDeclarationOne */
        $responseDeclarationOne = $responseDeclaration[$interactionOne->getResponseIdentifier()];
        /** @var ResponseDeclaration $responseDeclarationTwo */
        $responseDeclarationTwo = $responseDeclaration[$interactionTwo->getResponseIdentifier()];
        /** @var ResponseDeclaration $responseDeclarationTwo */
        $responseDeclarationThree = $responseDeclaration[$interactionThree->getResponseIdentifier()];

        // Check has the correct identifiers, also correct 'correctResponse' values
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
        $this->assertCount(1, $responseProcessing->getComponents());
        
        $responseRules = $responseProcessing->getComponents();
        $responseRuleOne = $responseRules[0];
        
        $this->assertTrue($responseRuleOne instanceof ResponseCondition);
        
    }
    
    public function testWithDistractorRationale()
    {
        
        $stimulus = '<strong>This is a clozetext dropdown question</strong>';
        $ques_template = '<p>Potato is a {{response}}, Guava is a {{response}} and red is a {{response}}</p>';
        $possible_responses = [
                    ["Vegetable","Color","Snacks","Fruit"],
                    ["Fruit","Vegetable","Color"],
                    ["Color","Fruit"]
                ];
        $question = new clozedropdown('clozedropdown', $ques_template, $possible_responses);
        $question->set_stimulus($stimulus);
        
        // Set valid_response
        $validation = $this->addValidResponse();
        $question->set_validation($validation);
        
        // Set metadata
        $metadata = $this->addQuestionMetadata();
        $question->set_metadata($metadata);
        
        $clozedropdown  = new ClozedropdownMapper();
        /** @var textEntryInteraction $interaction */
        list($interaction, $responseDeclaration, $responseProcessing) = $clozedropdown->convert($question, 'testIdentifier', 'testIdentifierLabel');
        
        $interactions = $interaction->getComponentsByClassName('inlineChoiceInteraction', true)->getArrayCopy();
        
        /** @var InlineChoiceInteraction $interactionOne */
        $interactionOne = $interactions[0];
        /** @var InlineChoiceInteraction $interactionTwo */
        $interactionTwo = $interactions[1];
        /** @var InlineChoiceInteraction $interactionThree */
        $interactionThree = $interactions[2];
        $this->assertTrue($interactionOne instanceof InlineChoiceInteraction);
        $this->assertTrue($interactionTwo instanceof InlineChoiceInteraction);
        $this->assertTrue($interactionThree instanceof InlineChoiceInteraction);
       
        // Assert response declarations
        /** @var ResponseDeclaration $responseDeclarationOne */
        $responseDeclarationOne = $responseDeclaration[$interactionOne->getResponseIdentifier()];
        /** @var ResponseDeclaration $responseDeclarationTwo */
        $responseDeclarationTwo = $responseDeclaration[$interactionTwo->getResponseIdentifier()];
        /** @var ResponseDeclaration $responseDeclarationTwo */
        $responseDeclarationThree = $responseDeclaration[$interactionThree->getResponseIdentifier()];

        // Check has the correct identifiers, also correct 'correctResponse' values
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
        
        $this->assertCount(2, $responseProcessing->getComponents());
  
        $responseRules = $responseProcessing->getComponents();
        $responseRuleOne = $responseRules[0];
        $responseRuleTwo = $responseRules[1];
        
		$this->assertTrue($responseRuleOne instanceof ResponseCondition);
        $this->assertTrue($responseRuleTwo instanceof SetOutcomeValue);
        
        $identifier = $responseRuleTwo->getIdentifier();
        $this->assertEquals('FEEDBACK_GENERAL', $identifier);
    }
    
    public function addQuestionMetadata()
    {
        
        // set distractor_rationale and distractor_rationale_response_level
        $metadata = new clozedropdown_metadata();
        $metadata->set_distractor_rationale('This is a general feedback');
        return $metadata;
    }
    
    public function addValidResponse()
    {
        
        // set valid response
        $valid_response = new clozedropdown_validation_valid_response();
        $valid_response->set_score(1);
        $valid_response->set_value(['Vegetable','Fruit','Color']);
        $validation = new clozedropdown_validation();
        $validation->set_valid_response($valid_response);
        return $validation;
    }
}
