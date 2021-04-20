<?php

namespace LearnosityQti\Tests\Unit\Processors\QtiV2\Out\QuestionTypes;

use LearnosityQti\Entities\QuestionTypes\plaintext;
use LearnosityQti\Processors\QtiV2\Out\QuestionTypes\PlaintextMapper;
use LearnosityQti\Utils\QtiMarshallerUtil;
use qtism\data\content\interactions\ExtendedTextInteraction;
use qtism\data\content\interactions\TextFormat;

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

        // No validation shall be mapped for plaintext
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
}
