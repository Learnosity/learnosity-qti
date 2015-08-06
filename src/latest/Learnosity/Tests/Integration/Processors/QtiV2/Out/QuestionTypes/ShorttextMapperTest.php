<?php

namespace Integration\Processors\QtiV2\Out\QuestionTypes;

use Learnosity\Processors\Learnosity\In\QuestionMapper;
use Learnosity\Processors\QtiV2\Out\QuestionTypes\ShorttextMapper;
use Learnosity\Tests\AbstractTest;
use Learnosity\Utils\QtiMarshallerUtil;
use qtism\data\content\interactions\ExtendedTextInteraction;
use qtism\data\content\interactions\TextFormat;

class ShorttextMapperTest extends AbstractTest
{
    private function getQuestionDataFromFilepath($filepath)
    {
        $questionMapper = new QuestionMapper();
        $question = $questionMapper->parse(json_decode($this->getFixtureFileContents($filepath), true));
        return $question->get_data();
    }

    public function testShorttextQuestionWithSimpleValidation()
    {
        $question = $this->getQuestionDataFromFilepath('learnosityjsons/shorttext.json');
        $mapper = new ShorttextMapper();
        list($interaction, $responseDeclaration, $responseProcessing) = $mapper->convert($question, 'testIdentifier', 'testIdentifier');

        // Test basic attributes
        /** @var ExtendedTextInteraction $interaction */
        $this->assertTrue($interaction instanceof ExtendedTextInteraction);
        $this->assertEquals('testIdentifier', $interaction->getLabel());
        $this->assertEquals('testIdentifier', $interaction->getResponseIdentifier());
        $this->assertEquals('placeholdertext', $interaction->getPlaceholderText());
        $this->assertEquals('<p>[This is the stem.]</p>', QtiMarshallerUtil::marshallCollection($interaction->getPrompt()->getComponents()));

        // Test default values
        $this->assertEquals(250, $interaction->getExpectedLength());
        $this->assertEquals(1, $interaction->getExpectedLines());
        $this->assertEquals(1, $interaction->getMaxStrings());
        $this->assertEquals(1, $interaction->getMinStrings());
        $this->assertEquals(TextFormat::PLAIN, $interaction->getFormat());

        // Test validation
        die;
    }
}
