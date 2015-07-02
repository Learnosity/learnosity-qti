<?php

namespace Learnosity\Tests\Unit\Mappers\QtiV2\Import\Interactions;

use Learnosity\Entities\QuestionTypes\clozeassociation;
use Learnosity\Mappers\QtiV2\Import\Interactions\GapMatchInteraction;
use Learnosity\Mappers\QtiV2\Import\ResponseProcessingTemplate;
use Learnosity\Tests\Unit\Mappers\QtiV2\Import\Fixtures\GapMatchInteractionBuilder;
use Learnosity\Tests\Unit\Mappers\QtiV2\Import\Fixtures\ResponseDeclarationBuilder;
use qtism\common\datatypes\DirectedPair;

class GapMatchInteractionTest extends AbstractInteractionTest
{
    public function testWithNoValidation()
    {
        $testInteraction =
            GapMatchInteractionBuilder::buildGapMatchInteraction(
                'testGapMatchInteraction',
                [
                    'A' => 'Gap A',
                    'B' => 'Gap B'
                ],
                [],
                ['G1', 'G2']);

        $mapper = new GapMatchInteraction($testInteraction);
        $question = $mapper->getQuestionType();

        $this->assertNotNull($question);
        $this->assertEquals('clozeassociation', $question->get_type());
        $this->assertEquals('<p>{{response}}{{response}}</p>', $question->get_template());
        $this->assertEquals(['Gap A', 'Gap B'], $question->get_possible_responses());
        $this->assertNull($question->get_validation());
    }

    public function testWithMapResponseValidDuplicatedResponses()
    {

        $testInteraction =
            GapMatchInteractionBuilder::buildGapMatchInteraction(
                'testGapMatchInteraction',
                [
                    'A' => 'Gap A',
                    'B' => 'Gap B',
                    'C' => 'Gap C'
                ],
                [],
                ['G1', 'G2']);
        $responseProcessingTemplate = ResponseProcessingTemplate::mapResponse();
        $validResponseIdentifier = [
            'A G1' => [1, false],
            'B G1' => [2, false],
            'C G2' => [3, false],
            'B G2' => [4, false]
        ];
        $responseDeclaration = ResponseDeclarationBuilder::buildWithMapping('testIdentifier',
            $validResponseIdentifier, 'DirectedPair');
        $mapper = new GapMatchInteraction($testInteraction, $responseDeclaration, $responseProcessingTemplate);
        /** @var clozeassociation $q */
        $q = $mapper->getQuestionType();
        $this->assertEquals('clozeassociation', $q->get_type());
        $this->assertEquals('<p>{{response}}{{response}}</p>', $q->get_template());
        $this->assertEquals(['Gap A', 'Gap B', 'Gap C'], $q->get_possible_responses());
        $this->assertTrue($q->get_duplicate_responses());

        $validation = $q->get_validation();
        $this->assertInstanceOf(
            'Learnosity\Entities\QuestionTypes\clozeassociation_validation',
            $validation);
        $this->assertEquals('exactMatch', $validation->get_scoring_type());

        $validResponse = $validation->get_valid_response();
        $this->assertInstanceOf(
            'Learnosity\Entities\QuestionTypes\clozeassociation_validation_valid_response',
            $validResponse);
        $this->assertEquals(6, $validResponse->get_score());
        $this->assertEquals(['Gap B', 'Gap B'], $validResponse->get_value());

        $altResponses = $validation->get_alt_responses();
        $this->assertCount(3, $altResponses);
        $this->assertEquals(5, $altResponses[0]->get_score());
        $this->assertEquals(['Gap B', 'Gap C'], $altResponses[0]->get_value());
        $this->assertEquals(5, $altResponses[1]->get_score());
        $this->assertEquals(['Gap A', 'Gap B'], $altResponses[1]->get_value());
        $this->assertEquals(4, $altResponses[2]->get_score());
        $this->assertEquals(['Gap A', 'Gap C'], $altResponses[2]->get_value());

        $this->assertEmpty($mapper->getExceptions());
    }

    public function testWithMatchCorrectResponseValidDuplicatedResponses()
    {
        $testInteraction =
            GapMatchInteractionBuilder::buildGapMatchInteraction(
                'testGapMatchInteraction',
                [
                    'A' => 'Gap A',
                    'B' => 'Gap B',
                    'C' => 'Gap C'
                ],
                [],
                ['G1', 'G2']);
        $responseProcessingTemplate = ResponseProcessingTemplate::matchCorrect();
        $validResponseIdentifier = [
            new DirectedPair('A', 'G1'),
            new DirectedPair('B', 'G1'),
            new DirectedPair('C', 'G2'),
            new DirectedPair('B', 'G2')
        ];
        $responseDeclaration =
            ResponseDeclarationBuilder::buildWithCorrectResponse('testIdentifier', $validResponseIdentifier);
        $mapper = new GapMatchInteraction($testInteraction, $responseDeclaration, $responseProcessingTemplate);
        /** @var clozeassociation $q */
        $q = $mapper->getQuestionType();
        $this->assertEquals('clozeassociation', $q->get_type());
        $this->assertEquals('<p>{{response}}{{response}}</p>', $q->get_template());
        $this->assertEquals(['Gap A', 'Gap B', 'Gap C'], $q->get_possible_responses());
        $this->assertTrue($q->get_duplicate_responses());

        $validation = $q->get_validation();
        $this->assertInstanceOf(
            'Learnosity\Entities\QuestionTypes\clozeassociation_validation',
            $validation);
        $this->assertEquals('exactMatch', $validation->get_scoring_type());

        $validResponse = $validation->get_valid_response();
        $this->assertInstanceOf(
            'Learnosity\Entities\QuestionTypes\clozeassociation_validation_valid_response',
            $validResponse);
        $this->assertEquals(1, $validResponse->get_score());
        $this->assertEquals(['Gap A', 'Gap C'], $validResponse->get_value());

        $altResponses = $validation->get_alt_responses();
        $this->assertCount(3, $altResponses);
        $this->assertEquals(1, $altResponses[0]->get_score());
        $this->assertEquals(['Gap A', 'Gap B'], $altResponses[0]->get_value());
        $this->assertEquals(1, $altResponses[1]->get_score());
        $this->assertEquals(['Gap B', 'Gap C'], $altResponses[1]->get_value());
        $this->assertEquals(1, $altResponses[2]->get_score());
        $this->assertEquals(['Gap B', 'Gap B'], $altResponses[2]->get_value());

        $this->assertEmpty($mapper->getExceptions());
    }

}