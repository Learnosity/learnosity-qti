<?php

namespace LearnosityQti\Tests\Unit\Processors\QtiV2\Out\QuestionTypes;

use LearnosityQti\Entities\QuestionTypes\clozetext;
use LearnosityQti\Entities\QuestionTypes\clozetext_metadata;
use LearnosityQti\Entities\QuestionTypes\clozetext_validation;
use LearnosityQti\Entities\QuestionTypes\clozetext_validation_valid_response;
use LearnosityQti\Processors\QtiV2\Out\QuestionTypes\ClozetextMapper;
use LearnosityQti\Utils\QtiMarshallerUtil;
use PHPUnit_Framework_TestCase;
use qtism\data\content\interactions\TextEntryInteraction;
use qtism\data\rules\ResponseElse;
use qtism\data\rules\ResponseIf;
use qtism\data\rules\SetOutcomeValue;
use qtism\data\state\ResponseDeclaration;

class ClozetextMapperTest extends PHPUnit_Framework_TestCase
{

    public function testSimpleCase()
    {
        
        $stimulus = '<strong>This is a clozetext question</strong>';
        $ques_template = 'B comes after {{response}} and C comes after {{response}}';
        $question = new clozetext('clozetext', $ques_template);
        $question->set_stimulus($stimulus);
        
        $response_value = ['response1','response2'];
        $validation = $this->addValidresponse($response_value);
        $question->set_validation($validation);
        
        $clozetext = new ClozetextMapper();
        /** @var textEntryInteraction $interaction */
        list($interaction, $responseDeclaration, $responseProcessing) = $clozetext->convert($question, 'testIdentifier', 'testIdentifierLabel');

        $interactions = $interaction->getComponentsByClassName('textEntryInteraction', true)->getArrayCopy();
        /** @var TextEntryInteraction $interactionOne */
        $interactionOne = $interactions[0];
        /** @var TextEntryInteraction $interactionTwo */
        $interactionTwo = $interactions[1];
        $this->assertTrue($interactionOne instanceof TextEntryInteraction);
        $this->assertTrue($interactionTwo instanceof TextEntryInteraction);
        $this->assertEquals(15, $interactionOne->getExpectedLength());
        $this->assertEquals(15, $interactionTwo->getExpectedLength());
        
        // Assert response declarations
        /** @var ResponseDeclaration $responseDeclarationOne */
        $responseDeclarationOne = $responseDeclaration[$interactionOne->getResponseIdentifier()];
        /** @var ResponseDeclaration $responseDeclarationTwo */
        $responseDeclarationTwo = $responseDeclaration[$interactionTwo->getResponseIdentifier()];
        
        // Check has the correct identifiers
        $this->assertEquals($responseDeclarationOne->getIdentifier(), $interactionOne->getResponseIdentifier());
        $this->assertEquals($responseDeclarationTwo->getIdentifier(), $interactionTwo->getResponseIdentifier());
        
        // Also correct 'correctResponse' values
        $this->assertEquals('response1', $responseDeclarationOne->getCorrectResponse()->getValues()->getArrayCopy()[0]->getValue());
        $this->assertEquals('response2', $responseDeclarationTwo->getCorrectResponse()->getValues()->getArrayCopy()[0]->getValue());
         
        // Also correct 'mapping' entries
        $this->assertEquals('response1', $responseDeclarationOne->getMapping()->getMapEntries()->getArrayCopy()[0]->getMapKey());
        $this->assertEquals(2.0, $responseDeclarationOne->getMapping()->getMapEntries()->getArrayCopy()[0]->getMappedValue());
            
        $this->assertEquals('response2', $responseDeclarationTwo->getMapping()->getMapEntries()->getArrayCopy()[0]->getMapKey());
        $this->assertEquals(2.0, $responseDeclarationTwo->getMapping()->getMapEntries()->getArrayCopy()[0]->getMappedValue());
            
        $this->assertCount(1, $responseProcessing->getComponents());
    }
 
    
    public function testWithOneGap()
    {
        
        $stimulus = '<strong>This is a clozetext question</strong>';
        $ques_template = 'B comes after {{response}}';
        $question = new clozetext('clozetext', $ques_template);
        $question->set_stimulus($stimulus);
        
        // add question validation
        $response_value = ['response1'];
        $validation = $this->addValidresponse($response_value);
        $question->set_validation($validation);
        
        // add metadata
        $metadata = $this->addMetadata();
        $question->set_metadata($metadata);
        
        $clozetext = new ClozetextMapper();
        /** @var textEntryInteraction $interaction */
        list($interaction, $responseDeclaration, $responseProcessing) = $clozetext->convert($question, 'testIdentifier', 'testIdentifierLabel');
        
        $interactions = $interaction->getComponentsByClassName('textEntryInteraction', true)->getArrayCopy();
        /** @var TextEntryInteraction $interactionOne */
        $interactionOne = $interactions[0];
        $this->assertTrue($interactionOne instanceof TextEntryInteraction);
        $this->assertEquals(15, $interactionOne->getExpectedLength());
        
        // Assert response declarations
        /** @var ResponseDeclaration $responseDeclarationOne */
        $responseDeclarationOne = $responseDeclaration[$interactionOne->getResponseIdentifier()];
        
        // Check has the correct identifiers
        $this->assertEquals($responseDeclarationOne->getIdentifier(), $interactionOne->getResponseIdentifier());
        
        // Also correct 'correctResponse' values
        $this->assertEquals('response1', $responseDeclarationOne->getCorrectResponse()->getValues()->getArrayCopy()[0]->getValue());
            
        // Also correct 'mapping' entries
        $this->assertEquals('response1', $responseDeclarationOne->getMapping()->getMapEntries()->getArrayCopy()[0]->getMapKey());
        $this->assertEquals(2.0, $responseDeclarationOne->getMapping()->getMapEntries()->getArrayCopy()[0]->getMappedValue());
        $this->assertCount(2, $responseProcessing->getComponents());
    }

    public function testWithDistractorRationale()
    {
        
        $stimulus = '<strong>This is a clozetext question</strong>';
        $ques_template = 'B comes after {{response}}';
        $question = new clozetext('clozetext', $ques_template);
        $question->set_stimulus($stimulus);
        
        // add question validation
        $response_value = ['response1'];
        $validation = $this->addValidresponse($response_value);
        $question->set_validation($validation);
        
        // add metadata
        $metadata = $this->addMetadata();
        $question->set_metadata($metadata);
        
        $clozetext = new ClozetextMapper();
        /** @var textEntryInteraction $interaction */
        list($interaction, $responseDeclaration, $responseProcessing) = $clozetext->convert($question, 'testIdentifier', 'testIdentifierLabel');
        
        $interactions = $interaction->getComponentsByClassName('textEntryInteraction', true)->getArrayCopy();
        /** @var TextEntryInteraction $interactionOne */
        $interactionOne = $interactions[0];
        $this->assertTrue($interactionOne instanceof TextEntryInteraction);
        $this->assertEquals(15, $interactionOne->getExpectedLength());
        
        // Assert response declarations
        /** @var ResponseDeclaration $responseDeclarationOne */
        $responseDeclarationOne = $responseDeclaration[$interactionOne->getResponseIdentifier()];
        
        // Check has the correct identifiers
        $this->assertEquals($responseDeclarationOne->getIdentifier(), $interactionOne->getResponseIdentifier());
        
        // Also correct 'correctResponse' values
        $this->assertEquals('response1', $responseDeclarationOne->getCorrectResponse()->getValues()->getArrayCopy()[0]->getValue());
            
        // Also correct 'mapping' entries
        $this->assertEquals('response1', $responseDeclarationOne->getMapping()->getMapEntries()->getArrayCopy()[0]->getMapKey());
        $this->assertEquals(2.0, $responseDeclarationOne->getMapping()->getMapEntries()->getArrayCopy()[0]->getMappedValue());
        $this->assertCount(2, $responseProcessing->getComponents());

        $responseIf = $responseProcessing->getComponentsByClassName('responseIf', true)->getArrayCopy()[0];
        $this->assertTrue($responseIf instanceof ResponseIf);
        $promptIfString = QtiMarshallerUtil::marshallCollection($responseIf->getComponents());
        $this->assertEquals('<isNull><variable identifier="RESPONSE"/></isNull><setOutcomeValue identifier="SCORE"><baseValue baseType="float">0</baseValue></setOutcomeValue>', $promptIfString);
        
        $responseElse = $responseProcessing->getComponentsByClassName('responseElse', true)->getArrayCopy()[0];
        $this->assertTrue($responseElse instanceof ResponseElse);
        $promptElseString = QtiMarshallerUtil::marshallCollection($responseElse->getComponents());
        $this->assertEquals('<responseCondition><responseIf><match><variable identifier="RESPONSE"/><correct identifier="RESPONSE"/></match><setOutcomeValue identifier="SCORE"><baseValue baseType="float">2</baseValue></setOutcomeValue></responseIf><responseElse><setOutcomeValue identifier="SCORE"><baseValue baseType="float">-1</baseValue></setOutcomeValue></responseElse></responseCondition>', $promptElseString);

        $setoutcome = $responseProcessing->getComponentsByClassName('setOutcomeValue', true)->getArrayCopy()[3];
        $this->assertTrue($setoutcome instanceof SetOutcomeValue);
        
        $identifier = $setoutcome->getIdentifier();
        $this->assertEquals('FEEDBACK_GENERAL', $identifier);
            
    }
    
    public function addValidresponse($response_value)
    {
        
        // set validation
        $valid_response = new clozetext_validation_valid_response();
        $valid_response->set_score(2);
        $valid_response->set_value($response_value);
        $validation = new clozetext_validation();
        $validation->set_penalty(1);
        $validation->set_valid_response($valid_response);
        return $validation;
    }
    
    public function addMetadata()
    {
        
        // set distractor_rationale and distractor_rationale_response_level
        $metadata = new clozetext_metadata();
        $metadata->set_distractor_rationale('It is a general feedback');
        return $metadata;
    }
}
