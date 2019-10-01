<?php

namespace LearnosityQti\Tests\Unit\Processors\QtiV2\Out\QuestionTypes;

use LearnosityQti\Entities\QuestionTypes\plaintext;
use LearnosityQti\Entities\QuestionTypes\plaintext_metadata;
use LearnosityQti\Processors\QtiV2\Out\QuestionTypes\PlaintextMapper;
use LearnosityQti\Entities\QuestionTypes\plaintext_validation;
use LearnosityQti\Utils\QtiMarshallerUtil;
use qtism\data\content\interactions\ExtendedTextInteraction;
use qtism\data\content\interactions\TextFormat;
use qtism\data\rules\ResponseElse;
use qtism\data\rules\ResponseIf;

class PlaintextMapperTest extends \PHPUnit_Framework_TestCase
{
    public function testMappingSimpleQuestionWithNoValidation()
    {
        $placeholder = 'placeholdertest';
        $stimulus = '<strong>stimulushere</strong>';
        $questionReference = 'questionReferenceOne';

        $question = new plaintext('plaintext');
        $question->set_placeholder($placeholder);
        $question->set_stimulus($stimulus);

        $mapper = new PlaintextMapper();
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
        $this->assertEquals(TextFormat::PLAIN, $interaction->getFormat());
        $this->assertEquals(1, $interaction->getMinStrings());
        $this->assertEquals(1, $interaction->getMaxStrings());
    }

    public function testMappingSimpleQuestionWithDistractorRationale()
    {
        $placeholder = 'placeholdertest';
        $stimulus = '<strong>stimulushere</strong>';
        $questionReference = 'questionReferenceOne';

        $question = new plaintext('plaintext');
        $question->set_placeholder($placeholder);

        $question->set_stimulus($stimulus);
        $metadata = new plaintext_metadata();
        $metadata->set_distractor_rationale(array('distractor_rationale'=>'This is general feedback'));
        $question->set_metadata($metadata);

        $mapper = new PlaintextMapper();
        /** @var ExtendedTextInteraction $interaction */
        list($interaction, $responseDeclaration) = $mapper->convert(
            $question,
            $questionReference,
            $questionReference
        );

        // No validation shall be mapped for plaintext
        $this->assertNotNull($responseDeclaration);

        // Assert question mapped correctly to ExtendedTextInteraction
        $this->assertTrue($interaction instanceof ExtendedTextInteraction);
        $this->assertEquals($questionReference, $interaction->getResponseIdentifier());
        $this->assertEquals($questionReference, $interaction->getLabel());
        $this->assertEquals($stimulus, QtiMarshallerUtil::marshallCollection($interaction->getPrompt()->getComponents()));
        $this->assertEquals($placeholder, $interaction->getPlaceholderText());

        // Assert question mapped correctly with default values
        $this->assertEquals(TextFormat::PLAIN, $interaction->getFormat());
        $this->assertEquals(1, $interaction->getMinStrings());
        $this->assertEquals(1, $interaction->getMaxStrings());
    }

    public function testMappingSimpleQuestionWithValidation()
    {
        $placeholder = 'placeholdertest';
        $stimulus = '<strong>stimulushere</strong>';
        $questionReference = 'questionReferenceOne';

        $question = new plaintext('plaintext');
        $question->set_placeholder($placeholder);
        $question->set_max_length(1000);

        $question->set_stimulus($stimulus);
        $metadata = new plaintext_metadata();

        $validation = new plaintext_validation();
        $validation->set_max_score(5);
        $validation->set_min_score_if_attempted(1);
        $question->set_validation($validation);

        $mapper = new PlaintextMapper();
        /** @var ExtendedTextInteraction $interaction */
        list($interaction, $responseDeclaration, $responseProcessing) = $mapper->convert(
            $question,
            $questionReference,
            $questionReference
        );

        // No validation shall be mapped for plaintext
        $this->assertNotNull($responseDeclaration);
        $this->assertNotNull($responseProcessing);

        $this->assertCount(1, $responseProcessing->getComponents());

        $responseIf = $responseProcessing->getComponentsByClassName('responseIf', true)->getArrayCopy()[0];
        $this->assertTrue($responseIf instanceof ResponseIf);
        $promptIfString = QtiMarshallerUtil::marshallCollection($responseIf->getComponents());
        $this->assertEquals('<isNull><variable identifier="RESPONSE"/></isNull><setOutcomeValue identifier="SCORE"><baseValue baseType="float">0</baseValue></setOutcomeValue>', $promptIfString);

        $responseElse = $responseProcessing->getComponentsByClassName('responseElse', true)->getArrayCopy()[0];
        $this->assertTrue($responseElse instanceof ResponseElse);
        $promptElseString = QtiMarshallerUtil::marshallCollection($responseElse->getComponents());
        $this->assertEquals('<responseCondition><responseIf><match><variable identifier="RESPONSE"/><correct identifier="RESPONSE"/></match><setOutcomeValue identifier="SCORE"><variable identifier="MAXSCORE"/></setOutcomeValue></responseIf><responseElse><setOutcomeValue identifier="SCORE"><baseValue baseType="float">0</baseValue></setOutcomeValue></responseElse></responseCondition>', $promptElseString);

        // Assert question mapped correctly to ExtendedTextInteraction
        $this->assertTrue($interaction instanceof ExtendedTextInteraction);
        $this->assertEquals($questionReference, $interaction->getResponseIdentifier());
        $this->assertEquals($questionReference, $interaction->getLabel());
        $this->assertEquals($stimulus, QtiMarshallerUtil::marshallCollection($interaction->getPrompt()->getComponents()));
        $this->assertEquals($placeholder, $interaction->getPlaceholderText());

        // Assert question mapped correctly with default values
        $this->assertEquals(TextFormat::PLAIN, $interaction->getFormat());
        $this->assertEquals(1, $interaction->getMinStrings());
        $this->assertEquals(1000, $interaction->getMaxStrings());
    }
}
