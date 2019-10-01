<?php

namespace LearnosityQti\Tests\Integration\Processors\QtiV2\Out\QuestionTypes;

use LearnosityQti\Entities\QuestionTypes\clozeassociation;
use LearnosityQti\Entities\QuestionTypes\clozeassociation_metadata;
use LearnosityQti\Entities\QuestionTypes\clozeassociation_validation;
use LearnosityQti\Entities\QuestionTypes\clozeassociation_validation_valid_response;
use LearnosityQti\Processors\QtiV2\Out\QuestionTypes\ClozeassociationMapper;
use LearnosityQti\Utils\QtiMarshallerUtil;
use qtism\common\datatypes\QtiDirectedPair;
use qtism\common\enums\BaseType;
use qtism\common\enums\Cardinality;
use qtism\data\content\interactions\GapMatchInteraction;
use qtism\data\content\interactions\GapText;
use qtism\data\rules\ResponseCondition;
use qtism\data\state\ResponseDeclaration;
use qtism\data\rules\SetOutcomeValue;


class ClozeassociationMapperTest extends AbstractQuestionTypeTest
{
    public function testSimpleCommonCase()
    {
        
        $stimulus = '<strong>This is a clozeassociation question</strong>';
		$ques_template = "<p>Risus {{response}}, et tincidunt turpis facilisis. Curabitur eu nulla justo. Curabitur vulputate ut nisl et bibendum. Nunc diam enim, porta sed eros vitae. {{response}} dignissim, et tincidunt turpis facilisis. Curabitur eu nulla justo. Curabitur vulputate ut nisl et bibendum.</p>";
		$possible_responses = ["Choice A", "Choice B"];
		$question = new clozeassociation('clozeassociation', $ques_template, $possible_responses);
        $question->set_stimulus($stimulus);
		
		// add valid_responses
        $validation = $this->addValidResponse();
        $question->set_validation($validation);

		$clozeassociation = new ClozeassociationMapper();
		/** @var GapMatchInteraction $interaction */
        list($interaction, $responseDeclaration, $responseProcessing) = $clozeassociation->convert($question, 'testIdentifier', 'testIdentifierLabel');
		
		$this->assertTrue($interaction instanceof GapMatchInteraction);
		
		// And its prompt is mapped correctly
        $promptString = QtiMarshallerUtil::marshallCollection($interaction->getPrompt()->getComponents());
        $this->assertEquals('<strong>This is a clozeassociation question</strong>', trim($promptString));
		
		// And its choices mapped well
        /** @var GapText[] $gapChoices */
        $gapChoices = $interaction->getGapChoices()->getArrayCopy();
        $this->assertEquals(2, count($gapChoices));
        $this->assertEquals('CHOICE_0', $gapChoices[0]->getIdentifier());
        $this->assertEquals('Choice A', QtiMarshallerUtil::marshallCollection($gapChoices[0]->getContent()));
        $this->assertEquals('CHOICE_1', $gapChoices[1]->getIdentifier());
        $this->assertEquals('Choice B', QtiMarshallerUtil::marshallCollection($gapChoices[1]->getContent()));

        // And its gaps
        $content = QtiMarshallerUtil::marshallCollection($interaction->getContent());
        $expectedContent = '<p>Risus <gap identifier="GAP_0"/>, et tincidunt turpis facilisis. Curabitur eu nulla justo. Curabitur vulputate ut nisl et bibendum. Nunc diam enim, porta sed eros vitae. <gap identifier="GAP_1"/> dignissim, et tincidunt turpis facilisis. Curabitur eu nulla justo. Curabitur vulputate ut nisl et bibendum.</p>';
        $this->assertEquals($expectedContent, $content);
		
		/** @var ResponseDeclaration $responseDeclaration */
        $this->assertEquals(Cardinality::MULTIPLE, $responseDeclaration->getCardinality());
        $this->assertEquals(BaseType::DIRECTED_PAIR, $responseDeclaration->getBaseType());

        /** @var Value[] $values */
        $values = $responseDeclaration->getCorrectResponse()->getValues()->getArrayCopy(true);
        $this->assertDirectPair($values[0]->getValue(), 'CHOICE_0', 'GAP_0');
        $this->assertDirectPair($values[1]->getValue(), 'CHOICE_1', 'GAP_1');

        // And, we don't have mapping because we simply won't
        $this->assertEquals(null, $responseDeclaration->getMapping());   
    }

    public function testWithValidationAndDistractorRationale()
    {
        $stimulus = '<strong>This is a clozeassociation question</strong>';
		$ques_template = "<p>Risus {{response}}, et tincidunt turpis facilisis. Curabitur eu nulla justo. Curabitur vulputate ut nisl et bibendum. Nunc diam enim, porta sed eros vitae. {{response}} dignissim, et tincidunt turpis facilisis. Curabitur eu nulla justo. Curabitur vulputate ut nisl et bibendum.</p>";
		$possible_responses = ["Choice A", "Choice B"];
		$question = new clozeassociation('clozeassociation', $ques_template, $possible_responses);
        $question->set_stimulus($stimulus);
		
		// add valid_responses
        $validation = $this->addValidResponse();
        $question->set_validation($validation);
		
		// Set metadata
        $metadata = $this->addQuestionMetadata();
        $question->set_metadata($metadata);
		
		$clozeassociation = new ClozeassociationMapper();
		/** @var GapMatchInteraction $interaction */
        list($interaction, $responseDeclaration, $responseProcessing) = $clozeassociation->convert($question, 'testIdentifier', 'testIdentifierLabel');
		
		$this->assertTrue($interaction instanceof GapMatchInteraction);
		$this->assertEquals(1, $responseDeclaration->getComponents()->count());
        $this->assertNotNull($responseProcessing);
            
        $this->assertCount(2, $responseProcessing->getComponents());
    
        $responseRules = $responseProcessing->getComponents();
        $responseRuleOne = $responseRules[0];
        $responseRuleTwo = $responseRules[1];
       
		$this->assertTrue($responseRuleOne instanceof ResponseCondition);
        $this->assertTrue($responseRuleTwo instanceof SetOutcomeValue);
        
        $identifier = $responseRuleTwo->getIdentifier();
        $this->assertEquals('FEEDBACK_GENERAL', $identifier);
    }

    private function assertDirectPair(QtiDirectedPair $pair, $expectedFirstValue, $expectedSecondValue)
    {
        $this->assertEquals($expectedFirstValue, $pair->getFirst());
        $this->assertEquals($expectedSecondValue, $pair->getSecond());
    }
	
	public function addQuestionMetadata()
    {
        // set distractor_rationale and distractor_rationale_response_level
        $metadata = new clozeassociation_metadata();
        $metadata->set_distractor_rationale('This is a general feedback');
        return $metadata;
    }
	
	private function addValidResponse()
    {
        // set valid response
        $valid_response = new clozeassociation_validation_valid_response();
        $valid_response->set_score(1);
        $valid_response->set_value(["Choice A", "Choice B"]);
        $validation = new clozeassociation_validation();
        $validation->set_valid_response($valid_response);
        return $validation;
    }
}


