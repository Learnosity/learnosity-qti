<?php

namespace Learnosity\Tests\Unit\Mappers\QtiV2\Import\MergedInteractions;

use Learnosity\Mappers\QtiV2\Import\MergedInteractions\MergedTextEntryInteraction;
use Learnosity\Mappers\QtiV2\Import\ResponseProcessingTemplate;
use Learnosity\Tests\Unit\Mappers\QtiV2\Import\Fixtures\ResponseDeclarationBuilder;
use Learnosity\Tests\Unit\Mappers\QtiV2\Import\Interactions\AbstractInteractionTest;
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
        $itemBody = $this->buildItemBodyTestCase();
        $mapper = new MergedTextEntryInteraction('dummyReference', $itemBody, null, null);
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
        $itemBody = $this->buildItemBodyTestCase($interactionOne, $interactionTwo);

        $mapper = new MergedTextEntryInteraction('dummyReference', $itemBody);
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
        $itemBody = $this->buildItemBodyTestCase($interactionOne, $interactionTwo);

        $mapper = new MergedTextEntryInteraction('dummyReference', $itemBody);
        $question = $mapper->getQuestionType();

        $this->assertNotNull($question);
        $this->assertTrue($question->get_max_length() >= 250);
        $this->assertTrue($question->get_multiple_line());
    }

    public function testSimpleCaseWithCorrectAnswersValidation()
    {
        $itemBody = $this->buildItemBodyTestCase();
        $responseDeclarations = new QtiComponentCollection();
        $responseDeclarations->attach(ResponseDeclarationBuilder::buildWithCorrectResponse('testIdentifierOne', ['Sydney']));
        $responseDeclarations->attach(ResponseDeclarationBuilder::buildWithCorrectResponse('testIdentifierTwo', ['Gloria Foster', 'Keanu Reeves', 'Laurence Fishburne']));
        $mapper = new MergedTextEntryInteraction('dummyReference', $itemBody, $responseDeclarations, ResponseProcessingTemplate::matchCorrect());
        $question = $mapper->getQuestionType();

        // Should map question correctly with `validation` object of `exactMatch` scoring type
        $this->assertNotNull($question);
        $this->assertEquals('<p>The Matrix movie is filmed at {{response}}, and starring {{response}}</p>', $question->get_template());
        $this->assertEquals('clozetext', $question->get_type());

        // Should set both `valid_response` and `alt_responses` for multiple correct values
        $validation = $question->get_validation();
        $this->assertNotNull($validation);
        $this->assertEquals('exactMatch', $validation->get_scoring_type());

        $validResponse = $validation->get_valid_response();
        $this->assertNotNull($validResponse);
        $this->assertEquals(1, $validResponse->get_score());
        $this->assertEquals(['Sydney', 'Keanu Reeves'], $validResponse->get_value());

        $altResponses = $validation->get_alt_responses();
        $this->assertNotNull($altResponses);
        $this->assertCount(2, $altResponses);
        $this->assertEquals(1, $altResponses[0]->get_score());
        $this->assertEquals(['Sydney', 'Gloria Foster'], $altResponses[0]->get_value());
        $this->assertEquals(1, $altResponses[1]->get_score());
        $this->assertEquals(['Sydney', 'Laurence Fishburne'], $altResponses[1]->get_value());

        // `match_correct` validation template always do not set casesensitive
        $this->assertFalse($question->get_case_sensitive());
    }

    public function testSimpleCaseWithMapResponseValidation()
    {
        $itemBody = $this->buildItemBodyTestCase();
        $responseDeclarations = new QtiComponentCollection();
        $responseDeclarations->attach(ResponseDeclarationBuilder::buildWithMapping('testIdentifierOne', [
            'Sydney' => [2, false],
            'sydney' => [1, false],
        ]));
        $responseDeclarations->attach(ResponseDeclarationBuilder::buildWithMapping('testIdentifierTwo', [
            'Keanu Reeves' => [1, true],
            'Gloria Foster' => [0.5, false],
        ]));
        $mapper = new MergedTextEntryInteraction('dummyReference', $itemBody, $responseDeclarations, ResponseProcessingTemplate::matchCorrect());
        $question = $mapper->getQuestionType();

        $this->assertNotNull($question);
        $this->assertEquals('clozetext', $question->get_type());

        $validation = $question->get_validation();
        $this->assertNotNull($validation);

        // First correct mapping value should be mapped to `valid_response`
        $validResponse = $validation->get_valid_response();
        $this->assertNotNull($validResponse);
        $this->assertEquals(3, $validResponse->get_score()); // The score would be the total `mappedValue` of the combination
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

    private function buildItemBodyTestCase(TextEntryInteraction $interactionOne = null, TextEntryInteraction $interactionTwo = null)
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
}
