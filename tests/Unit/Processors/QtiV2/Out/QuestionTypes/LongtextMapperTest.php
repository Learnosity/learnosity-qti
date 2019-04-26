<?php

namespace LearnosityQti\Tests\Unit\Processors\QtiV2\Out\QuestionTypes;

use LearnosityQti\Entities\QuestionTypes\longtext;
use LearnosityQti\Entities\QuestionTypes\longtext_metadata;
use LearnosityQti\Entities\QuestionTypes\longtext_validation;
use LearnosityQti\Processors\QtiV2\Out\QuestionTypes\LongtextMapper;
use LearnosityQti\Utils\QtiMarshallerUtil;
use qtism\data\content\interactions\ExtendedTextInteraction;
use qtism\data\content\interactions\TextFormat;
use qtism\common\enums\BaseType;
use qtism\common\enums\Cardinality;
use qtism\data\rules\ResponseIf;
use qtism\data\rules\ResponseElse;

class LongtextMapperTest extends \PHPUnit_Framework_TestCase
{
    public function testMappingSimpleQuestionWithNoValidation()
    {
        $placeholder = 'placeholdertest';
        $stimulus = '<strong>stimulushere</strong>';
        $questionReference = 'questionReferenceOne';

        $question = new longtext('longtext');
        $question->set_placeholder($placeholder);
        $question->set_stimulus($stimulus);
        
        $mapper = new LongtextMapper();
        /** @var ExtendedTextInteraction $interaction */
        list($interaction, $responseDeclaration, $responseProcessing) = $mapper->convert(
            $question,
            $questionReference,
            $questionReference
        );

        // No validation shall be mapped for longtext
        $this->assertNull($responseDeclaration);
        $this->assertNull($responseProcessing);

        // Assert question mapped correctly to ExtendedTextInteraction
        $this->assertTrue($interaction instanceof ExtendedTextInteraction);
        $this->assertEquals($questionReference, $interaction->getResponseIdentifier());
        $this->assertEquals($questionReference, $interaction->getLabel());
        $this->assertEquals($stimulus, QtiMarshallerUtil::marshallCollection($interaction->getPrompt()->getComponents()));
        $this->assertEquals($placeholder, $interaction->getPlaceholderText());

        // Assert question mapped correctly with default values
        $this->assertEquals(TextFormat::XHTML, $interaction->getFormat());
        $this->assertEquals(1, $interaction->getMinStrings());
        $this->assertEquals(1, $interaction->getMaxStrings());
    }
    
    public function testMappingSimpleQuestionWithDistratorRationale()
    {
        $placeholder = 'placeholdertest';
        $stimulus = '<strong>stimulushere</strong>';
        $questionReference = 'questionReferenceOne';

        $question = new longtext('longtext');
        $question->set_placeholder($placeholder);
        $question->set_stimulus($stimulus);
        $question->set_metadata($this->addDistractorRationale());
        
        $mapper = new LongtextMapper();
        /** @var ExtendedTextInteraction $interaction */
        list($interaction, $responseDeclaration, $responseProcessing) = $mapper->convert(
            $question,
            $questionReference,
            $questionReference
        );
        
        // responseProcessing shall be mapped for longtext if it has validation or distractor_rationale
        $this->assertNotNull($responseDeclaration);
        $this->assertNotNull($responseProcessing);
        
        $this->assertCount(1,$responseProcessing->getComponents());
        
        $responseIf = $responseProcessing->getComponentsByClassName('responseIf', true)->getArrayCopy()[0];
        $this->assertTrue($responseIf instanceof ResponseIf);
        $promptIfString = QtiMarshallerUtil::marshallCollection($responseIf->getComponents());
        $this->assertEquals('<isNull><variable identifier="RESPONSE"/></isNull><setOutcomeValue identifier="SCORE"><baseValue baseType="float">0</baseValue></setOutcomeValue><setOutcomeValue identifier="FEEDBACK_GENERAL"><baseValue baseType="identifier">correctOrIncorrect</baseValue></setOutcomeValue>', $promptIfString);
        
        $responseElse = $responseProcessing->getComponentsByClassName('responseElse', true)->getArrayCopy()[0];
        $this->assertTrue($responseElse instanceof ResponseElse);
        $promptElseString = QtiMarshallerUtil::marshallCollection($responseElse->getComponents());
        $this->assertEquals('<responseCondition><responseIf><match><variable identifier="RESPONSE"/><correct identifier="RESPONSE"/></match><setOutcomeValue identifier="SCORE"><baseValue baseType="float">0</baseValue></setOutcomeValue><setOutcomeValue identifier="FEEDBACK_GENERAL"><baseValue baseType="identifier">correctOrIncorrect</baseValue></setOutcomeValue></responseIf><responseElse><setOutcomeValue identifier="SCORE"><baseValue baseType="float">0</baseValue></setOutcomeValue><setOutcomeValue identifier="FEEDBACK_GENERAL"><baseValue baseType="identifier">correctOrIncorrect</baseValue></setOutcomeValue></responseElse></responseCondition>', $promptElseString);
        
        // Check on the responseDeclaration and responseProcessing objects to be correctly generated
        $this->assertEquals('', $responseProcessing->getTemplate());
        $this->assertEquals(Cardinality::SINGLE, $responseDeclaration->getCardinality());
        $this->assertEquals(BaseType::STRING, $responseDeclaration->getBaseType());
        
        // Assert question mapped correctly to ExtendedTextInteraction
        $this->assertTrue($interaction instanceof ExtendedTextInteraction);
        $this->assertEquals($questionReference, $interaction->getResponseIdentifier());
        $this->assertEquals($questionReference, $interaction->getLabel());
        $this->assertEquals($stimulus, QtiMarshallerUtil::marshallCollection($interaction->getPrompt()->getComponents()));
        $this->assertEquals($placeholder, $interaction->getPlaceholderText());

        // Assert question mapped correctly with default values
        $this->assertEquals(TextFormat::XHTML, $interaction->getFormat());
        $this->assertEquals(1, $interaction->getMinStrings());
        $this->assertEquals(1, $interaction->getMaxStrings());
    }
    
    public function testMappingSimpleQuestionWithValidation()
    {
        $placeholder = 'placeholdertest';
        $stimulus = '<strong>stimulushere</strong>';
        $questionReference = 'questionReferenceOne';

        $question = new longtext('longtext');
        $question->set_placeholder($placeholder);
        $question->set_stimulus($stimulus);
        $question->set_max_length(1000);
        $question->set_metadata($this->addDistractorRationale());
        
        $validation = new longtext_validation();
        $validation->set_max_score(5);
        $validation->set_min_score_if_attempted(1);
        $question->set_validation($validation);
        
        $mapper = new LongtextMapper();
        /** @var ExtendedTextInteraction $interaction */
        list($interaction, $responseDeclaration, $responseProcessing) = $mapper->convert(
            $question,
            $questionReference,
            $questionReference
        );
        
        // responseProcessing shall be mapped for longtext if it has validation or distractor_rationale
        $this->assertNotNull($responseDeclaration);
        $this->assertNotNull($responseProcessing);
        
        $this->assertCount(1,$responseProcessing->getComponents());
        
        $responseIf = $responseProcessing->getComponentsByClassName('responseIf', true)->getArrayCopy()[0];
        $this->assertTrue($responseIf instanceof ResponseIf);
        $promptIfString = QtiMarshallerUtil::marshallCollection($responseIf->getComponents());
        $this->assertEquals('<isNull><variable identifier="RESPONSE"/></isNull><setOutcomeValue identifier="SCORE"><baseValue baseType="float">0</baseValue></setOutcomeValue><setOutcomeValue identifier="FEEDBACK_GENERAL"><baseValue baseType="identifier">correctOrIncorrect</baseValue></setOutcomeValue>', $promptIfString);
        
        $responseElse = $responseProcessing->getComponentsByClassName('responseElse', true)->getArrayCopy()[0];
        $this->assertTrue($responseElse instanceof ResponseElse);
        $promptElseString = QtiMarshallerUtil::marshallCollection($responseElse->getComponents());
        $this->assertEquals('<responseCondition><responseIf><match><variable identifier="RESPONSE"/><correct identifier="RESPONSE"/></match><setOutcomeValue identifier="SCORE"><variable identifier="MAXSCORE"/></setOutcomeValue><setOutcomeValue identifier="FEEDBACK_GENERAL"><baseValue baseType="identifier">correctOrIncorrect</baseValue></setOutcomeValue></responseIf><responseElse><setOutcomeValue identifier="SCORE"><baseValue baseType="float">0</baseValue></setOutcomeValue><setOutcomeValue identifier="FEEDBACK_GENERAL"><baseValue baseType="identifier">correctOrIncorrect</baseValue></setOutcomeValue></responseElse></responseCondition>', $promptElseString);
        
        // Check on the responseDeclaration and responseProcessing objects to be correctly generated
        $this->assertEquals('', $responseProcessing->getTemplate());
        $this->assertEquals(Cardinality::SINGLE, $responseDeclaration->getCardinality());
        $this->assertEquals(BaseType::STRING, $responseDeclaration->getBaseType());
        
        // Assert question mapped correctly to ExtendedTextInteraction
        $this->assertTrue($interaction instanceof ExtendedTextInteraction);
        $this->assertEquals($questionReference, $interaction->getResponseIdentifier());
        $this->assertEquals($questionReference, $interaction->getLabel());
        $this->assertEquals($stimulus, QtiMarshallerUtil::marshallCollection($interaction->getPrompt()->getComponents()));
        $this->assertEquals($placeholder, $interaction->getPlaceholderText());

        // Assert question mapped correctly with default values
        $this->assertEquals(TextFormat::XHTML, $interaction->getFormat());
        $this->assertEquals(1, $interaction->getMinStrings());
        $this->assertEquals(1, $interaction->getMaxStrings());
        $this->assertEquals(1000, $interaction->getExpectedLength());
    }
    
    private function addDistractorRationale() {
        $metaData = new longtext_metadata();
        $metaData->set_distractor_rationale("This is genral feedback");
        return $metaData;
    }
}
