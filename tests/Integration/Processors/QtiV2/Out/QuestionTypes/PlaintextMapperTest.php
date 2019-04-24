<?php

namespace LearnosityQti\Tests\Integration\Processors\QtiV2\Out\QuestionTypes;

use LearnosityQti\Utils\QtiMarshallerUtil;
use qtism\data\content\interactions\ExtendedTextInteraction;
use qtism\data\content\interactions\TextFormat;

class PlaintextMapperTest extends AbstractQuestionTypeTest
{
    public function testSimpleWithNoValidation()
    {
        $data = json_decode($this->getFixtureFileContents('learnosityjsons/item_plaintext.json'), true);
        $assessmentItem = $this->convertToAssessmentItem($data);

        // Plaintext shall have no <responseDeclaration> and <responseProcessing>
        $this->assertEquals(1, $assessmentItem->getResponseDeclarations()->count());
        $this->assertNull($assessmentItem->getResponseProcessing());

        // Has <extendedTextInteraction> as the first and only interaction
        /** @var ExtendedTextInteraction $interaction */
        $interaction = $assessmentItem->getComponentsByClassName('extendedTextInteraction', true)->getArrayCopy()[0];
        $this->assertTrue($interaction instanceof ExtendedTextInteraction);

        // And its prompt is mapped correctly
        $promptString = QtiMarshallerUtil::marshallCollection($interaction->getPrompt()->getComponents());
        $this->assertEquals('<p>Write an essay</p>', $promptString);

        // And it is a HTML text by default
        $this->assertEquals(TextFormat::PLAIN, $interaction->getFormat());
    }
}
