<?php

namespace Learnosity\Tests\Unit\Mappers\QtiV2\In\Interactions;

use qtism\data\content\FlowStaticCollection;
use qtism\data\content\interactions\ExtendedTextInteraction;
use qtism\data\content\interactions\Prompt;
use qtism\data\content\TextRun;

class ExtendedTextInteractionTest extends AbstractInteractionTest
{
    public function testWithNoValidation()
    {
        $interaction = $this->buildExtendedTextInteraction('identifierOne');
        $mapper = new \Learnosity\Processors\QtiV2\In\Interactions\ExtendedTextInteractionMapper($interaction);
        $question = $mapper->getQuestionType();

        $this->assertNotNull($question);
        $this->assertEquals('longtext', $question->get_type());
        $this->assertEquals('Please describe yourself in few words', $question->get_stimulus());
        $this->assertNull($question->get_validation());
    }

    public function testShouldConsiderPlaceholder()
    {
        $interaction = $this->buildExtendedTextInteraction('identifierOne');
        $interaction->setPlaceholderText('I am awesome!');
        $mapper = new \Learnosity\Processors\QtiV2\In\Interactions\ExtendedTextInteractionMapper($interaction);
        $question = $mapper->getQuestionType();

        $this->assertNotNull($question);
        $this->assertEquals('I am awesome!', $question->get_placeholder());
    }

    public function testShouldConsiderExpectedLengthWithNoMaxString()
    {
        $interaction = $this->buildExtendedTextInteraction('identifierOne');
        $interaction->setExpectedLength(200);
        $mapper = new \Learnosity\Processors\QtiV2\In\Interactions\ExtendedTextInteractionMapper($interaction);
        $question = $mapper->getQuestionType();

        $this->assertNotNull($question);
        $this->assertEquals(40, $question->get_max_length());
        $this->assertTrue($question->get_submit_over_limit());
    }

    public function testShouldConsiderExpectedLengthWithMaxString()
    {
        $interaction = $this->buildExtendedTextInteraction('identifierOne');
        $interaction->setMaxStrings(5);
        $interaction->setExpectedLength(10);
        $mapper = new \Learnosity\Processors\QtiV2\In\Interactions\ExtendedTextInteractionMapper($interaction);
        $question = $mapper->getQuestionType();

        $this->assertNotNull($question);
        $this->assertEquals(10, $question->get_max_length());
        $this->assertTrue($question->get_submit_over_limit());
    }

    private function buildExtendedTextInteraction($identifier)
    {
        $prompt = new Prompt();
        $collection = new FlowStaticCollection();
        $collection->attach(new TextRun('Please describe yourself in few words'));
        $prompt->setContent($collection);

        $interaction = new ExtendedTextInteraction($identifier);
        $interaction->setPrompt($prompt);
        return $interaction;
    }
} 
