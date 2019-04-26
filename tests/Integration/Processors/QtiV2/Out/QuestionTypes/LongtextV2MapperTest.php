<?php

namespace LearnosityQti\Tests\Integration\Processors\QtiV2\Out\QuestionTypes;

use LearnosityQti\Utils\QtiMarshallerUtil;
use qtism\data\content\interactions\ExtendedTextInteraction;
use qtism\data\content\interactions\TextFormat;
use qtism\data\rules\ResponseElse;
use qtism\data\rules\ResponseIf;

class LongtextV2MapperTest extends AbstractQuestionTypeTest
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
    
    public function testWithValidationAndDistractorRationale()
    {
        $data = json_decode($this->getFixtureFileContents('learnosityjsons/longtext_validation.json'), true);
        $assessmentItemArray = $this->convertToAssessmentItem($data);

        foreach($assessmentItemArray as $assessmentItem){
            
            // Longtext shall have no <responseDeclaration> and <responseProcessing>
            $this->assertEquals(1, $assessmentItem->getResponseDeclarations()->count());
            $this->assertNotNull($assessmentItem->getResponseProcessing());
            
            $this->assertCount(1,$assessmentItem->getResponseProcessing()->getComponents());
            
            $responseIf = $assessmentItem->getResponseProcessing()->getComponentsByClassName('responseIf', true)->getArrayCopy()[0];
            $this->assertTrue($responseIf instanceof ResponseIf);
            $promptIfString = QtiMarshallerUtil::marshallCollection($responseIf->getComponents());
            $this->assertEquals('<isNull><variable identifier="RESPONSE"/></isNull><setOutcomeValue identifier="SCORE"><baseValue baseType="float">0</baseValue></setOutcomeValue><setOutcomeValue identifier="FEEDBACK_GENERAL"><baseValue baseType="identifier">correctOrIncorrect</baseValue></setOutcomeValue>', $promptIfString);

            $responseElse = $assessmentItem->getResponseProcessing()->getComponentsByClassName('responseElse', true)->getArrayCopy()[0];
            $this->assertTrue($responseElse instanceof ResponseElse);
            $promptElseString = QtiMarshallerUtil::marshallCollection($responseElse->getComponents());
            $this->assertEquals('<responseCondition><responseIf><match><variable identifier="RESPONSE"/><correct identifier="RESPONSE"/></match><setOutcomeValue identifier="SCORE"><variable identifier="MAXSCORE"/></setOutcomeValue><setOutcomeValue identifier="FEEDBACK_GENERAL"><baseValue baseType="identifier">correctOrIncorrect</baseValue></setOutcomeValue></responseIf><responseElse><setOutcomeValue identifier="SCORE"><baseValue baseType="float">0</baseValue></setOutcomeValue><setOutcomeValue identifier="FEEDBACK_GENERAL"><baseValue baseType="identifier">correctOrIncorrect</baseValue></setOutcomeValue></responseElse></responseCondition>', $promptElseString);
        
            // Has <extendedTextInteraction> as the first and only interaction
            /** @var ExtendedTextInteraction $interaction */
            $interaction = $assessmentItem->getComponentsByClassName('extendedTextInteraction', true)->getArrayCopy()[0];
            $this->assertTrue($interaction instanceof ExtendedTextInteraction);

            // And its prompt is mapped correctly
            $promptString = QtiMarshallerUtil::marshallCollection($interaction->getPrompt()->getComponents());
            $this->assertEquals('[This is the stem.]', trim($promptString));

            // And it is a HTML text by default
            $this->assertEquals(TextFormat::XHTML, $interaction->getFormat());
        }
    }
}


