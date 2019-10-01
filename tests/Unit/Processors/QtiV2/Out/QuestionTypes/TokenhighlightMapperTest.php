<?php

namespace LearnosityQti\Tests\Unit\Processors\QtiV2\Out\QuestionTypes;

use LearnosityQti\Entities\QuestionTypes\tokenhighlight;
use LearnosityQti\Entities\QuestionTypes\tokenhighlight_metadata;
use LearnosityQti\Entities\QuestionTypes\tokenhighlight_validation;
use LearnosityQti\Entities\QuestionTypes\tokenhighlight_validation_valid_response;
use LearnosityQti\Processors\QtiV2\Out\QuestionTypes\TokenhighlightMapper;
use LearnosityQti\Utils\QtiMarshallerUtil;
use qtism\data\content\interactions\Hottext;
use qtism\data\content\interactions\HottextInteraction;
use qtism\data\rules\ResponseIf;
use qtism\data\rules\ResponseElse;
use qtism\data\rules\SetOutcomeValue;

class TokenhighlightMapperTest extends \PHPUnit_Framework_TestCase
{
    public function testWithNoValidation()
    {
        $template =
            '<p><span class="lrn_token">Hello</span></p>' .
            '<p><span class="lrn_token">Welcome to my world!</span></p>' .
            '<p><span class="lrn_token">Brown sugar</span></p>';
        $question = new tokenhighlight('tokenhighlight', $template, 'custom');

        $mapper = new TokenhighlightMapper();
        /** @var HottextInteraction $interaction */
        list($interaction, $responseDeclaration, $responseProcessing) = $mapper->convert($question, 'testIdentifier', 'testIdentifierLabel');
        $this->assertTrue($interaction instanceof HottextInteraction);
        $this->assertNull($responseDeclaration);
        $this->assertNull($responseProcessing);

        // Assert those hottext
        /** @var Hottext[] $hottexts */
        $hottexts = $interaction->getComponentsByClassName('hottext', true)->getArrayCopy(true);
        $this->assertEquals(3, $interaction->getComponentsByClassName('hottext', true)->count());
        $this->assertEquals('TOKEN_0', $hottexts[0]->getIdentifier());
        $this->assertEquals('Hello', QtiMarshallerUtil::marshallCollection($hottexts[0]->getComponents()));
        $this->assertEquals('TOKEN_1', $hottexts[1]->getIdentifier());
        $this->assertEquals('Welcome to my world!', QtiMarshallerUtil::marshallCollection($hottexts[1]->getComponents()));
        $this->assertEquals('TOKEN_2', $hottexts[2]->getIdentifier());
        $this->assertEquals('Brown sugar', QtiMarshallerUtil::marshallCollection($hottexts[2]->getComponents()));
    }
    
    public function testWithValidation()
    {
        $template =
            '<p><span class="lrn_token">Hello</span></p>' .
            '<p><span class="lrn_token">Welcome to my world!</span></p>' .
            '<p><span class="lrn_token">Brown sugar</span></p>';
        $question = new tokenhighlight('tokenhighlight', $template, 'custom');
        
        $validation = $this->addValidation();
        $question->set_validation($validation);
        $mapper = new TokenhighlightMapper();
        /** @var HottextInteraction $interaction */
        list($interaction, $responseDeclaration, $responseProcessing) = $mapper->convert($question, 'testIdentifier', 'testIdentifierLabel');
        
        $this->assertTrue($interaction instanceof HottextInteraction);
        $this->assertNotEmpty($responseDeclaration);
        $this->assertNotEmpty($responseProcessing);

        // Assert those hottext
        /** @var Hottext[] $hottexts */
        $hottexts = $interaction->getComponentsByClassName('hottext', true)->getArrayCopy(true);
        $this->assertEquals(3, $interaction->getComponentsByClassName('hottext', true)->count());
        $this->assertEquals('TOKEN_0', $hottexts[0]->getIdentifier());
        $this->assertEquals('Hello', QtiMarshallerUtil::marshallCollection($hottexts[0]->getComponents()));
        $this->assertEquals('TOKEN_1', $hottexts[1]->getIdentifier());
        $this->assertEquals('Welcome to my world!', QtiMarshallerUtil::marshallCollection($hottexts[1]->getComponents()));
        $this->assertEquals('TOKEN_2', $hottexts[2]->getIdentifier());
        $this->assertEquals('Brown sugar', QtiMarshallerUtil::marshallCollection($hottexts[2]->getComponents()));
    }

    public function testWithDistractorRationale()
    {
        $template =
            '<p><span class="lrn_token">Hello</span></p>' .
            '<p><span class="lrn_token">Welcome to my world!</span></p>' .
            '<p><span class="lrn_token">Brown sugar</span></p>';
        $question = new tokenhighlight('tokenhighlight', $template, 'custom');
        
        $metadata = new tokenhighlight_metadata();
        $metadata->set_distractor_rationale("General Feedback");
        $question->set_metadata($metadata);
        $validation = $this->addValidation();
        $question->set_validation($validation);

        $mapper = new TokenhighlightMapper();
        /** @var HottextInteraction $interaction */
        list($interaction, $responseDeclaration, $responseProcessing) = $mapper->convert($question, 'testIdentifier', 'testIdentifierLabel');
        
        $this->assertTrue($interaction instanceof HottextInteraction);
        $this->assertNotEmpty($responseDeclaration);
        $this->assertNotEmpty($responseProcessing);

        $this->assertCount(2, $responseProcessing->getComponents());
        $responseIf = $responseProcessing->getComponentsByClassName('responseIf', true)->getArrayCopy()[0];
        
        $this->assertTrue($responseIf instanceof ResponseIf);
        $promptIfString = QtiMarshallerUtil::marshallCollection($responseIf->getComponents());
        $this->assertEquals('<isNull><variable identifier="RESPONSE"/></isNull><setOutcomeValue identifier="SCORE"><baseValue baseType="float">0</baseValue></setOutcomeValue>', $promptIfString);
        
        $responseElse = $responseProcessing->getComponentsByClassName('responseElse', true)->getArrayCopy()[0];
        $this->assertTrue($responseElse instanceof ResponseElse);
        $promptElseString = QtiMarshallerUtil::marshallCollection($responseElse->getComponents());
        $this->assertEquals('<responseCondition><responseIf><match><variable identifier="RESPONSE"/><correct identifier="RESPONSE"/></match><setOutcomeValue identifier="SCORE"><baseValue baseType="float"></baseValue></setOutcomeValue></responseIf><responseElse><setOutcomeValue identifier="SCORE"><baseValue baseType="float">0</baseValue></setOutcomeValue></responseElse></responseCondition>', $promptElseString);

        $setoutcome = $responseProcessing->getComponentsByClassName('setOutcomeValue', true)->getArrayCopy()[3];
        $this->assertTrue($setoutcome instanceof SetOutcomeValue);
        
        $identifier = $setoutcome->getIdentifier();
        $this->assertEquals('FEEDBACK_GENERAL', $identifier);
    }

    public function addValidation()
    {
        $validation = new tokenhighlight_validation();
        $valid_response = new tokenhighlight_validation_valid_response();
        $valid_response->set_value([0, 1, 2]);
        $validation->set_valid_response($valid_response);
        return $validation;
    }
}
