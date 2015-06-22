<?php

namespace Learnosity\Tests\Unit\Mappers\QtiV2\Import\Interactions;

use Learnosity\Mappers\QtiV2\Import\Interactions\TextEntryInteraction;
use Learnosity\Mappers\QtiV2\Import\ResponseProcessingTemplate;
use Learnosity\Tests\Unit\Mappers\QtiV2\Import\Fixtures\ResponseDeclarationBuilder;

class TextEntryInteractionTest extends AbstractInteractionTest
{
    public function testSimpleCaseWithNoValidation()
    {
        $interaction = new \qtism\data\content\interactions\TextEntryInteraction('testIdentifier');
        $mapper = new TextEntryInteraction($interaction);
        $question = $mapper->getQuestionType();

        $this->assertNotNull($question);
        $this->assertNull($question->get_validation());
    }

    public function testShouldConsiderMaxLengthLessThan250()
    {
        $interaction = new \qtism\data\content\interactions\TextEntryInteraction('testIdentifier');
        $interaction->setExpectedLength(50);
        $mapper = new TextEntryInteraction($interaction);
        $question = $mapper->getQuestionType();

        $this->assertNotNull($question);
        $this->assertTrue($question->get_max_length() >= 50);
    }

    public function testShouldConsiderMaxLengthMoreThan250()
    {
        $interaction = new \qtism\data\content\interactions\TextEntryInteraction('testIdentifier');
        $interaction->setExpectedLength(500);
        $mapper = new TextEntryInteraction($interaction);
        $question = $mapper->getQuestionType();

        $this->assertNotNull($question);
        $this->assertTrue($question->get_max_length() == 250);
        $this->assertTrue($question->get_multiple_line());
    }

    public function testSimpleCaseWithMatchCorrectValidation()
    {
        $interaction = new \qtism\data\content\interactions\TextEntryInteraction('testIdentifier');
        $responseDeclaration = ResponseDeclarationBuilder::buildWithCorrectResponse('testIdentifier', ['hello', 'Hello']);
        $mapper = new TextEntryInteraction($interaction, $responseDeclaration, ResponseProcessingTemplate::matchCorrect());
        $question = $mapper->getQuestionType();

        $this->assertNotNull($question);
        $validation = $question->get_validation();
        $this->assertNotNull($validation);

        $validResponse = $validation->get_valid_response();
        $this->assertNotNull($validResponse);
        $this->assertEquals(1, $validResponse->get_score());
        $this->assertContains('hello', $validResponse->get_value());
        $this->assertContains('Hello', $validResponse->get_value());
    }

    public function testSimpleCaseWithMapResponseValidation()
    {
        $interaction = new \qtism\data\content\interactions\TextEntryInteraction('testIdentifier');
        $responseDeclaration = ResponseDeclarationBuilder::buildWithMapping('testIdentifier', [
            'York' => [2, false],
            'york' => [0.5, false],
            'Sydney' => [1, false],
            'Junior' => [1, true]
        ]);
        $mapper = new TextEntryInteraction($interaction, $responseDeclaration, ResponseProcessingTemplate::mapResponse());
        $question = $mapper->getQuestionType();

        $this->assertNotNull($question);
        $this->assertEquals($question->get_template(), '{{response}}');
        $validation = $question->get_validation();
        $this->assertNotNull($validation);

        // First correct mapping value should be mapped to `valid_response`
        $validResponse = $validation->get_valid_response();
        $this->assertNotNull($validResponse);
        $this->assertEquals(2, $validResponse->get_score());
        $this->assertContains('York', $validResponse->get_value());

        // Other correct mapping values should be mapped to `alt_response`
        $altResponses = $validation->get_alt_responses();
        $this->assertNotNull($altResponses);
        $this->assertCount(3, $altResponses);

        $this->assertEquals(0.5, $altResponses[0]->get_score());
        $this->assertContains('york', $altResponses[0]->get_value());
        $this->assertEquals(1, $altResponses[0]->get_score());
        $this->assertContains('Sydney', $altResponses[0]->get_value());
        $this->assertEquals(1, $altResponses[0]->get_score());
        $this->assertContains('Junior', $altResponses[0]->get_value());

        // Since one of them has is set to be case sensitive, so everything shall be case sensitive
        // TODO: Ensure a warning is thrown explaining that as well
        $this->assertTrue($question->get_case_sensitive());
    }
}
