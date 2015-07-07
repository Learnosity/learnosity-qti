<?php

namespace Learnosity\Tests\Unit\Mappers\QtiV2\In\Interactions;

use Learnosity\Processors\QtiV2\In\Interactions\HottextInteractionMapper;
use Learnosity\Processors\QtiV2\In\ResponseProcessingTemplate;
use Learnosity\Tests\Unit\Mappers\QtiV2\In\Fixtures\HottextInteractionBuilder;
use Learnosity\Tests\Unit\Mappers\QtiV2\In\Fixtures\ResponseDeclarationBuilder;
use qtism\data\content\FlowStaticCollection;
use qtism\data\content\interactions\Prompt;
use qtism\data\content\TextRun;

class HottextInteractionTest extends AbstractInteractionTest
{
    public function testWithNoValidation()
    {
        $interaction = $this->buildHottextInteraction('identifierOne');
        $mapper = new HottextInteractionMapper($interaction);
        $question = $mapper->getQuestionType();

        $this->assertNotNull($question);
        $this->assertEquals('tokenhighlight', $question->get_type());
        $this->assertEquals('Select the error in the following passage of text', $question->get_stimulus());
        $this->assertNull($question->get_validation());
    }

    public function testWithMatchCorrectValidation()
    {
        $interaction = $this->buildHottextInteraction('identifierOne');
        $responseDeclaration = ResponseDeclarationBuilder::buildWithCorrectResponse('identifierOne', ['A', 'C']);
        $mapper = new HottextInteractionMapper($interaction, $responseDeclaration, ResponseProcessingTemplate::matchCorrect());
        $question = $mapper->getQuestionType();

        $this->assertNotNull($question);
        $this->assertEquals('tokenhighlight', $question->get_type());

        $validation = $question->get_validation();
        $this->assertNotNull($validation);
        $this->assertEquals(1, $validation->get_valid_response()->get_score());
        $this->assertEquals([0, 2], $validation->get_valid_response()->get_value());
    }

    public function testWithMapResponseValidation()
    {
        $interaction = $this->buildHottextInteraction('identifierOne');
        $interaction->setMaxChoices(2);
        $responseDeclaration = ResponseDeclarationBuilder::buildWithMapping('identifierOne', [
            'A' => [4],
            'C' => [1]
        ]);
        $mapper = new HottextInteractionMapper($interaction, $responseDeclaration, ResponseProcessingTemplate::mapResponse());
        $question = $mapper->getQuestionType();

        $this->assertNotNull($question);
        $this->assertEquals('tokenhighlight', $question->get_type());

        $validation = $question->get_validation();
        $this->assertNotNull($validation);
        $this->assertEquals(5, $validation->get_valid_response()->get_score());
        $this->assertEquals([2, 0], $validation->get_valid_response()->get_value());

        $altResponses = $validation->get_alt_responses();
        $this->assertNotNull($altResponses);
        $this->assertCount(2, $altResponses);
        $this->assertEquals([0], $altResponses[0]->get_value());
        $this->assertEquals(4, $altResponses[0]->get_score());
        $this->assertEquals([2], $altResponses[1]->get_value());
        $this->assertEquals(1, $altResponses[1]->get_score());
    }

    private function buildHottextInteraction($responseIdentifier)
    {
        $interaction = HottextInteractionBuilder::buildSimple($responseIdentifier, [
            'Hello, my name ', ['A' => 'are'] , ' James. I ' , ['B' => 'have'] , ' five brothers. and all of them ', ['C' => 'is'] , ' girls.'
        ]);
        $prompt = new Prompt();
        $promptCollection = new FlowStaticCollection();
        $promptCollection->attach(new TextRun('Select the error in the following passage of text'));
        $prompt->setContent($promptCollection);
        $interaction->setPrompt($prompt);
        return $interaction;
    }
} 
