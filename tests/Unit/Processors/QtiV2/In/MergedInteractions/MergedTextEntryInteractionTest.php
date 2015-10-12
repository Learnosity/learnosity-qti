<?php

namespace LearnosityQti\Tests\Unit\Processors\QtiV2\In\MergedInteractions;

use LearnosityQti\Processors\QtiV2\In\MergedInteractions\MergedTextEntryInteractionMapper;
use LearnosityQti\Processors\QtiV2\In\ResponseProcessingTemplate;
use LearnosityQti\Services\LogService;
use LearnosityQti\Tests\Unit\Processors\QtiV2\In\Fixtures\ResponseDeclarationBuilder;
use LearnosityQti\Tests\Unit\Processors\QtiV2\In\Interactions\AbstractInteractionTest;
use qtism\data\content\BlockCollection;
use qtism\data\content\InlineCollection;
use qtism\data\content\interactions\TextEntryInteraction;
use qtism\data\content\ItemBody;
use qtism\data\content\TextRun;
use qtism\data\content\xhtml\text\P;
use qtism\data\QtiComponentCollection;

class MergedTextEntryInteractionTest extends AbstractInteractionTest
{
    public function testSimpleCaseWithNoValidation()
    {
        $itemBody = $this->buildItemBodyWithTwoInteraction();
        $mapper = new MergedTextEntryInteractionMapper('dummyReference', $itemBody, null, null);
        $question = $mapper->getQuestionType();

        $this->assertNotNull($question);
        $this->assertNull($question->get_validation());
    }

    public function testShouldConsiderMaxLengthLessThan250()
    {
        $interactionOne = new TextEntryInteraction('testIdentifierOne');
        $interactionOne->setExpectedLength(50);
        $interactionTwo = new TextEntryInteraction('testIdentifierTwo');
        $interactionTwo->setExpectedLength(100);
        $itemBody = $this->buildItemBodyWithTwoInteraction($interactionOne, $interactionTwo);

        $mapper = new MergedTextEntryInteractionMapper('dummyReference', $itemBody);
        $question = $mapper->getQuestionType();

        $this->assertNotNull($question);
        $this->assertTrue($question->get_max_length() >= 100);
    }

    public function testShouldConsiderMaxLengthMoreThan250()
    {
        $interactionOne = new TextEntryInteraction('testIdentifierOne');
        $interactionOne->setExpectedLength(50);
        $interactionTwo = new TextEntryInteraction('testIdentifierTwo');
        $interactionTwo->setExpectedLength(500);
        $itemBody = $this->buildItemBodyWithTwoInteraction($interactionOne, $interactionTwo);

        $mapper = new MergedTextEntryInteractionMapper('dummyReference', $itemBody);
        $question = $mapper->getQuestionType();

        $this->assertNotNull($question);
        $this->assertTrue($question->get_max_length() >= 250);
        $this->assertTrue($question->get_multiple_line());
    }

    public function testSingleInteractionWithCorrectAnswersValidation()
    {
        $itemBody = $this->buildItemBodyWithSingleInteraction();
        $responseDeclarations = new QtiComponentCollection();
        $responseDeclarations->attach(ResponseDeclarationBuilder::buildWithCorrectResponse('testIdentifierOne',
            ['Gloria Foster', 'Keanu Reeves', 'Laurence Fishburne']));
        $mapper = new MergedTextEntryInteractionMapper('dummyReference', $itemBody, $responseDeclarations, ResponseProcessingTemplate::matchCorrect());
        $question = $mapper->getQuestionType();

        // Should map question correctly with `validation` object of `exactMatch` scoring type
        $this->assertNotNull($question);
        $this->assertEquals('<p>The Matrix movie is starring {{response}}.</p>', $question->get_template());
        $this->assertEquals('clozetext', $question->get_type());

        // Should set both `valid_response` and `alt_responses` for multiple correct values
        $validation = $question->get_validation();
        $this->assertNotNull($validation);
        $this->assertEquals('exactMatch', $validation->get_scoring_type());

        $options = [];

        $validResponse = $validation->get_valid_response();
        $this->assertNotNull($validResponse);
        $this->assertEquals(1, $validResponse->get_score());
        $options = array_merge($options, $validResponse->get_value());

        $altResponses = $validation->get_alt_responses();
        $this->assertCount(2, $altResponses);
        foreach ($altResponses as $altResponse) {
            $this->assertEquals(1, $altResponse->get_score());
            $options = array_merge($options, $altResponse->get_value());
        }
        $this->assertContains('Gloria Foster', $options);
        $this->assertContains('Keanu Reeves', $options);
        $this->assertContains('Laurence Fishburne', $options);

        // `match_correct` validation template always do not set casesensitive
        $this->assertFalse($question->get_case_sensitive());
    }

    public function testTwoInteractionWithCorrectAnswersValidation()
    {
        $itemBody = $this->buildItemBodyWithTwoInteraction();
        $responseDeclarations = new QtiComponentCollection();
        $responseDeclarations->attach(ResponseDeclarationBuilder::buildWithCorrectResponse('testIdentifierTwo', ['Gloria Foster', 'Keanu Reeves', 'Laurence Fishburne']));
        $responseDeclarations->attach(ResponseDeclarationBuilder::buildWithCorrectResponse('testIdentifierOne', ['Sydney']));
        $mapper = new MergedTextEntryInteractionMapper('dummyReference', $itemBody, $responseDeclarations, ResponseProcessingTemplate::matchCorrect());
        $question = $mapper->getQuestionType();

        // Should map question correctly with `validation` object of `exactMatch` scoring type
        $this->assertNotNull($question);
        $this->assertEquals('<p>The Matrix movie is filmed at {{response}}, and starring {{response}}</p>', $question->get_template());
        $this->assertEquals('clozetext', $question->get_type());

        // Should set both `valid_response` and `alt_responses` for multiple correct values
        $validation = $question->get_validation();
        $this->assertNotNull($validation);
        $this->assertEquals('exactMatch', $validation->get_scoring_type());

        $options = [];

        $validResponse = $validation->get_valid_response();
        $this->assertNotNull($validResponse);
        $this->assertEquals(2, $validResponse->get_score());
        $options[] = $validResponse->get_value();

        $altResponses = $validation->get_alt_responses();
        $this->assertNotNull($altResponses);
        $this->assertCount(2, $altResponses);
        foreach ($altResponses as $altResponse) {
            $this->assertEquals(2, $altResponse->get_score());
            $options[] = $altResponse->get_value();
        }
        $this->assertContains(['Sydney', 'Gloria Foster'], $options);
        $this->assertContains(['Sydney', 'Keanu Reeves'], $options);
        $this->assertContains(['Sydney', 'Laurence Fishburne'], $options);

        // `match_correct` validation template always do not set casesensitive
        $this->assertFalse($question->get_case_sensitive());
    }

    public function testSingleInteractionWithMapResponseValidation()
    {
        $itemBody = $this->buildItemBodyWithSingleInteraction();
        $responseDeclarations = new QtiComponentCollection();
        $responseDeclarations->attach(ResponseDeclarationBuilder::buildWithMapping('testIdentifierOne', [
            'Keanu Reeves' => [1.75, true],
            'Gloria Foster' => [2, false],
            'Stella Lie' => [5, false]
        ]));
        $mapper = new MergedTextEntryInteractionMapper('dummyReference', $itemBody, $responseDeclarations, ResponseProcessingTemplate::mapResponse());
        $question = $mapper->getQuestionType();

        $this->assertNotNull($question);
        $this->assertEquals('clozetext', $question->get_type());

        $validation = $question->get_validation();
        $this->assertNotNull($validation);

        // First correct mapping value should be mapped to `valid_response`
        $validResponse = $validation->get_valid_response();
        $this->assertNotNull($validResponse);
        $this->assertEquals(5, $validResponse->get_score()); // The score would be the total `mappedValue` of the combination with highest score
        $this->assertContains('Stella Lie', $validResponse->get_value());
        $this->assertTrue($question->get_case_sensitive());
        // TODO: Check alt_responses as well?
    }

    public function testTwoInteractionsWithMapResponseValidation()
    {
        $itemBody = $this->buildItemBodyWithTwoInteraction();
        $responseDeclarations = new QtiComponentCollection();
        $responseDeclarations->attach(ResponseDeclarationBuilder::buildWithMapping('testIdentifierOne', [
            'Sydney' => [2, false],
            'sydney' => [1, false],
        ]));
        $responseDeclarations->attach(ResponseDeclarationBuilder::buildWithMapping('testIdentifierTwo', [
            'Keanu Reeves' => [1, true],
            'Gloria Foster' => [0.5, false],
        ]));
        $mapper = new MergedTextEntryInteractionMapper('dummyReference', $itemBody, $responseDeclarations, ResponseProcessingTemplate::mapResponse());
        $question = $mapper->getQuestionType();

        $this->assertNotNull($question);
        $this->assertEquals('clozetext', $question->get_type());

        $validation = $question->get_validation();
        $this->assertNotNull($validation);

        // First correct mapping value should be mapped to `valid_response`
        $validResponse = $validation->get_valid_response();
        $this->assertNotNull($validResponse);
        $this->assertEquals(3, $validResponse->get_score()); // The score would be the total `mappedValue` of the combination with highest score
        $this->assertContains('Sydney', $validResponse->get_value());
        $this->assertContains('Keanu Reeves', $validResponse->get_value());

        // Other correct mapping values should be mapped to `alt_response`
        $altResponses = $validation->get_alt_responses();
        $this->assertNotNull($altResponses);
        $this->assertCount(3, $altResponses);

        $this->assertEquals(2.5, $altResponses[0]->get_score());
        $this->assertContains('Sydney', $altResponses[0]->get_value());
        $this->assertContains('Gloria Foster', $altResponses[0]->get_value());

        $this->assertEquals(2, $altResponses[1]->get_score());
        $this->assertContains('sydney', $altResponses[1]->get_value());
        $this->assertContains('Keanu Reeves', $altResponses[1]->get_value());

        $this->assertEquals(1.5, $altResponses[2]->get_score());
        $this->assertContains('sydney', $altResponses[2]->get_value());
        $this->assertContains('Gloria Foster', $altResponses[2]->get_value());

        // If one of the mapping has case sensitivity as true, then everything become case sensitive
        $this->assertTrue($question->get_case_sensitive());
    }

    public function testInvalidResponseProcessingTemplate()
    {
        $itemBody = $this->buildItemBodyWithSingleInteraction();
        $responseDeclarations = new QtiComponentCollection();
        $responseDeclarations->attach(ResponseDeclarationBuilder::buildWithMapping('testIdentifierOne', [
            'Sydney' => [2, false],
            'sydney' => [1, false],
        ]));
        $mapper = new MergedTextEntryInteractionMapper('dummyReference', $itemBody, $responseDeclarations, ResponseProcessingTemplate::getFromTemplateUrl(''));
        $question = $mapper->getQuestionType();

        $this->assertNotNull($question);
        $this->assertNull($question->get_validation());
        $this->assertCount(1, LogService::read());
    }

    private function buildItemBodyWithTwoInteraction(TextEntryInteraction $interactionOne = null, TextEntryInteraction $interactionTwo = null)
    {
        $interactionOne = (empty($interactionOne)) ? new TextEntryInteraction('testIdentifierOne') : $interactionOne;
        $interactionTwo = (empty($interactionTwo)) ? new TextEntryInteraction('testIdentifierTwo') : $interactionTwo;
        $itemBody = new ItemBody();
        $itemBodyCollection = new BlockCollection();

        // Build `<p>The Matrix .... <inlineChoiceInteraction...></inlineChoiceInteraction>.</p>`
        $p = new P();
        $pCollection = new InlineCollection();
        $pCollection->attach(new TextRun('The Matrix movie is filmed at '));
        $pCollection->attach($interactionOne);
        $pCollection->attach(new TextRun(', and starring '));
        $pCollection->attach($interactionTwo);
        $p->setContent($pCollection);

        // Build the <itemBody>
        $itemBodyCollection->attach($p);
        $itemBody->setContent($itemBodyCollection);

        return $itemBody;
    }

    private function buildItemBodyWithSingleInteraction(TextEntryInteraction $interactionOne = null)
    {
        $interactionOne = (empty($interactionOne)) ? new TextEntryInteraction('testIdentifierOne') : $interactionOne;
        $itemBody = new ItemBody();
        $itemBodyCollection = new BlockCollection();

        // Build `<p>The Matrix .... <inlineChoiceInteraction...></inlineChoiceInteraction>.</p>`
        $p = new P();
        $pCollection = new InlineCollection();
        $pCollection->attach(new TextRun('The Matrix movie is starring '));
        $pCollection->attach($interactionOne);
        $pCollection->attach(new TextRun('.'));
        $p->setContent($pCollection);

        // Build the <itemBody>
        $itemBodyCollection->attach($p);
        $itemBody->setContent($itemBodyCollection);

        return $itemBody;
    }
}
