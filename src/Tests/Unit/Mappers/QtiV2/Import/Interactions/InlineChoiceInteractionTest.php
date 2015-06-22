<?php

namespace Learnosity\Tests\Unit\Mappers\QtiV2\Import\Interactions;

use Learnosity\Mappers\QtiV2\Import\Interactions\InlineChoiceInteraction;
use Learnosity\Mappers\QtiV2\Import\ResponseProcessingTemplate;
use Learnosity\Tests\Unit\Mappers\QtiV2\Import\Fixtures\InlineChoiceInteractionBuilder;
use Learnosity\Tests\Unit\Mappers\QtiV2\Import\Fixtures\ResponseDeclarationBuilder;

class InlineChoiceInteractionTest extends AbstractInteractionTest
{
    public function testSimpleCaseWithNoValidation()
    {
        $interaction = InlineChoiceInteractionBuilder::buildSimple('testIdentifier', [
            'sydney' => 'Sydney',
            'melbourne' => 'Melbourne',
            'canberra' => 'Canberra',
        ]);
        $mapper = new InlineChoiceInteraction($interaction);
        $question = $mapper->getQuestionType();

        // Should map question correctly with no `validation` object
        $this->assertNotNull($question);
        $this->assertEquals('clozedropdown', $question->get_type());
        $validation = $question->get_validation();
        $this->assertNull($validation);
    }

    public function testSimpleCaseWithMatchCorrectValidation()
    {
        $interaction = InlineChoiceInteractionBuilder::buildSimple('testIdentifier', [
            'sydney' => 'Sydney',
            'melbourne' => 'Melbourne',
            'canberra' => 'Canberra',
        ]);
        $responseDeclaration = ResponseDeclarationBuilder::buildWithCorrectResponse('testIdentifier', [
            'sydney',
            'melbourne'
        ]);
        $mapper = new InlineChoiceInteraction($interaction, $responseDeclaration, ResponseProcessingTemplate::matchCorrect());
        $question = $mapper->getQuestionType();

        $this->assertNotNull($question);
        $this->assertEquals('clozedropdown', $question->get_type());

        $validation = $question->get_validation();
        $this->assertNotNull($validation);
        $this->assertInstanceOf('Learnosity\Entities\QuestionTypes\clozedropdown_validation', $validation);
        $this->assertEquals('exactMatch', $validation->get_scoring_type());

        // Should set both `valid_response` and `alt_responses` for multiple correct values
        $validResponse = $validation->get_valid_response();
        $this->assertNotNull($validResponse);
        $this->assertEquals(1, $validResponse->get_score());
        $this->assertEquals(["Sydney"], $validResponse->get_value());

        $altResponses = $validation->get_alt_responses();
        $this->assertNotNull($altResponses);
        $this->assertCount(1, $altResponses);
        $this->assertEquals(1, $altResponses[0]->get_score());
        $this->assertEquals(["Melbourne"], $altResponses[0]->get_value());
    }

    public function testSimpleCaseWithMapResponseValidation()
    {
        //TODO: Do it!
    }
}
