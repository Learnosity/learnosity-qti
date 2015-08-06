<?php

namespace Learnosity\Tests\Unit\Processors\QtiV2\Out\QuestionTypes;

use Learnosity\Entities\QuestionTypes\longtext;
use Learnosity\Processors\QtiV2\Out\QuestionTypes\LongtextMapper;
use Learnosity\Utils\QtiMarshallerUtil;
use qtism\data\content\interactions\ExtendedTextInteraction;
use qtism\data\content\interactions\TextFormat;

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
}
