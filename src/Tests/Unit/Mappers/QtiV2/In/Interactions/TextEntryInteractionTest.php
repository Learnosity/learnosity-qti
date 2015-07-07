<?php

namespace Learnosity\Tests\Unit\Mappers\QtiV2\In\Interactions;

use Learnosity\Processors\QtiV2\In\Interactions\TextEntryInteractionMapper;
use Learnosity\Processors\QtiV2\In\ResponseProcessingTemplate;
use Learnosity\Tests\Unit\Mappers\QtiV2\In\Fixtures\ResponseDeclarationBuilder;

class TextEntryInteractionTest extends AbstractInteractionTest
{
    public function testSimpleCaseWithNoValidation()
    {
        $interaction = new \qtism\data\content\interactions\TextEntryInteraction('testIdentifier');

        $mapper = new TextEntryInteractionMapper($interaction);
        $question = $mapper->getQuestionType();

        $this->assertNotNull($question);
        $this->assertNull($question->get_validation());
        $this->assertCount(1,$mapper->getExceptions());
    }

    public function testShouldConsiderMaxLengthLessThan250()
    {
        $interaction = new \qtism\data\content\interactions\TextEntryInteraction('testIdentifier');
        $interaction->setExpectedLength(50);
        $mapper = new TextEntryInteractionMapper($interaction);
        $question = $mapper->getQuestionType();

        $this->assertNotNull($question);
        $this->assertTrue($question->get_max_length() >= 50);
    }

    public function testShouldConsiderMaxLengthMoreThan250()
    {
        $interaction = new \qtism\data\content\interactions\TextEntryInteraction('testIdentifier');
        $interaction->setExpectedLength(500);
        $mapper = new TextEntryInteractionMapper($interaction);
        $question = $mapper->getQuestionType();

        $this->assertNotNull($question);
        $this->assertTrue($question->get_max_length() >= 250);
        $this->assertTrue($question->get_multiple_line());
    }

    public function testSimpleCaseWithMatchCorrectValidation()
    {
        $interaction = new \qtism\data\content\interactions\TextEntryInteraction('testIdentifier');
        $responseDeclaration = ResponseDeclarationBuilder::buildWithCorrectResponse('testIdentifier', ['hello', 'Hello']);
        $mapper = new TextEntryInteractionMapper($interaction, $responseDeclaration, ResponseProcessingTemplate::matchCorrect());
        $question = $mapper->getQuestionType();
        $this->assertNotNull($question);
        $validation = $question->get_validation();
        $this->assertNotNull($validation);
        $validResponse = $validation->get_valid_response();
        $this->assertNotNull($validResponse);
        $this->assertEquals(1, $validResponse->get_score());
        $responses = [];
        $responses[] = $validResponse->get_value()[0];
        $altResponses = $validation->get_alt_responses();
        $this->assertTrue(count($altResponses) === 1);
        $this->assertEquals(1, $altResponses[0]->get_score());
        foreach ($altResponses[0]->get_value() as $value) {
            $responses[] = $value;
        }
        $this->assertTrue(count($responses) === 2);
        $this->assertContains('hello', $responses);
        $this->assertContains('Hello', $responses);
    }

    public function testSimpleCaseWithMapResponseValidation()
    {
        $interaction = new \qtism\data\content\interactions\TextEntryInteraction('testIdentifier');
        $responseDeclaration = ResponseDeclarationBuilder::buildWithMapping('testIdentifier', [
            'york' => [0.5, false],
            'Sydney' => [1, false],
            'York' => [2, false],
            'Junior' => [1.5, true],

        ]);
        $mapper = new TextEntryInteractionMapper($interaction, $responseDeclaration, ResponseProcessingTemplate::mapResponse());
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

        foreach($altResponses as $altResponse) {
            switch($altResponse->get_value()) {
                case 'york':
                    $this->assertEquals(0.5, $altResponse->get_score());
                    break;
                case 'Sydney':
                    $this->assertEquals(1, $altResponse->get_score());
                    break;
                case 'Junior':
                    $this->assertEquals(1.5, $altResponse->get_score());
                    break;
            }
        }

        // Since one of them has is set to be case sensitive, so everything shall be case sensitive
        // TODO: Ensure a warning is thrown explaining that as well
        $this->assertTrue($question->get_case_sensitive());
    }

    public function testInvalidResponseProcessingTemplate() {
        $interaction = new \qtism\data\content\interactions\TextEntryInteraction('testIdentifier');
        $mapper = new TextEntryInteractionMapper($interaction, null, ResponseProcessingTemplate::getFromTemplateUrl(''));
        $question = $mapper->getQuestionType();
        $this->assertCount(1, $mapper->getExceptions());
    }
}
