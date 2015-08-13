<?php

namespace Learnosity\Tests\Integration\Processors\QtiV2\Out\QuestionTypes;

use Learnosity\Utils\QtiMarshallerUtil;
use qtism\data\content\interactions\ExtendedTextInteraction;
use qtism\data\content\interactions\TextFormat;

class ShorttextMapperTest extends AbstractQuestionTypeTest
{
    public function testShorttextQuestionWithSimpleValidation()
    {
        $data = json_decode($this->getFixtureFileContents('learnosityjsons/shorttext.json'), true);
        $assessmentItem = $this->convertToAssessmentItem($data);

        // Shorttext shall have one simple exactMatch <responseDeclaration> and <responseProcessing>
        // TODO: might need more extensive test to test these more in depth
        $this->assertEquals(1, $assessmentItem->getResponseDeclarations()->count());

        // Has <extendedTextInteraction> as the first and only interaction
        /** @var ExtendedTextInteraction $interaction */
        $interaction = $assessmentItem->getComponentsByClassName('extendedTextInteraction', true)->getArrayCopy()[0];

        // Test basic attributes
        $this->assertTrue($interaction instanceof ExtendedTextInteraction);
        $this->assertEquals('placeholdertext', $interaction->getPlaceholderText());
        $this->assertEquals('<p>[This is the stem.]</p>', QtiMarshallerUtil::marshallCollection($interaction->getPrompt()->getComponents()));

        // Test default values
        $this->assertEquals(250, $interaction->getExpectedLength());
        $this->assertEquals(1, $interaction->getExpectedLines());
        $this->assertEquals(1, $interaction->getMaxStrings());
        $this->assertEquals(1, $interaction->getMinStrings());
        $this->assertEquals(TextFormat::PLAIN, $interaction->getFormat());
    }
}
