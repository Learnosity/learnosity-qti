<?php

namespace Learnosity\Tests\Unit\Mappers\QtiV2\Import\Interactions;


use Learnosity\Entities\QuestionTypes\choicematrix;
use Learnosity\Mappers\QtiV2\Import\Interactions\MatchInteraction;
use Learnosity\Mappers\QtiV2\Import\ResponseProcessingTemplate;
use Learnosity\Tests\Unit\Mappers\QtiV2\Import\Fixtures\MatchInteractionBuilder;
use Learnosity\Tests\Unit\Mappers\QtiV2\Import\Fixtures\ResponseDeclarationBuilder;
use qtism\common\datatypes\DirectedPair;

class MatchInteractionTest extends AbstractInteractionTest
{


    public function setup()
    {

    }

    public function testShouldHandleMatchCorrectValidationWithMultipleResponses()
    {
        $testMatchInteraction =
            MatchInteractionBuilder::buildMatchInteraction(
                'testMatchInteraction',
                [
                    [
                        'A' => 'Item A',
                        'B' => 'Item B'
                    ],
                    [
                        'C' => 'Item C',
                        'D' => 'Item D',
                        'E' => 'Item E'
                    ]
                ]);

        $testMatchInteraction->setMaxAssociations(3);
        $responseProcessingTemplate = ResponseProcessingTemplate::matchCorrect();
        $validResponseIdentifier = [
            new DirectedPair('A', 'D'),
            new DirectedPair('B', 'C'),
            new DirectedPair('A', 'E')
        ];
        $responseDeclaration = ResponseDeclarationBuilder::buildWithCorrectResponse('testIdentifier',
            $validResponseIdentifier);

        $mapper = new MatchInteraction($testMatchInteraction, $responseDeclaration, $responseProcessingTemplate);

        /** @var choicematrix $q */
        $q = $mapper->getQuestionType();
        $this->assertInstanceOf('Learnosity\Entities\QuestionTypes\choicematrix', $q);
        $this->assertEquals('choicematrix', $q->get_type());
        $this->assertCount(3, $q->get_options());
        $this->assertContains('Item C', $q->get_options());
        $this->assertContains('Item D', $q->get_options());
        $this->assertContains('Item E', $q->get_options());

        $this->assertCount(2, $q->get_stems());
        $this->assertContains('Item A', $q->get_stems());
        $this->assertContains('Item B', $q->get_stems());

        $validation = $q->get_validation();
        $this->assertInstanceOf(
            'Learnosity\Entities\QuestionTypes\choicematrix_validation',
            $validation);
        $this->assertEquals('exactMatch', $validation->get_scoring_type());

        $validResponse = $validation->get_valid_response();
        $this->assertInstanceOf(
            'Learnosity\Entities\QuestionTypes\choicematrix_validation_valid_response',
            $validResponse);
        $this->assertEquals(1, $validResponse->get_score());
        $this->assertEquals([[1, 2], [0]], $validResponse->get_value());
        $this->assertTrue($q->get_multiple_responses());
    }


    public function testShouldHandleMatchCorrectValidationWithoutMultipleResponses()
    {
        $testMatchInteraction =
            MatchInteractionBuilder::buildMatchInteraction(
                'testMatchInteraction',
                [
                    [
                        'A' => 'Item A',
                        'B' => 'Item B'
                    ],
                    [
                        'C' => 'Item C',
                        'D' => 'Item D'
                    ]
                ]);

        $testMatchInteraction->setMaxAssociations(2);
        $responseProcessingTemplate = ResponseProcessingTemplate::matchCorrect();
        $validResponseIdentifier = [
            new DirectedPair('A', 'D'),
            new DirectedPair('B', 'C')
        ];
        $responseDeclaration = ResponseDeclarationBuilder::buildWithCorrectResponse('testIdentifier',
            $validResponseIdentifier);

        $mapper = new MatchInteraction($testMatchInteraction, $responseDeclaration, $responseProcessingTemplate);

        /** @var choicematrix $q */
        $q = $mapper->getQuestionType();
        $this->assertInstanceOf('Learnosity\Entities\QuestionTypes\choicematrix', $q);
        $this->assertEquals('choicematrix', $q->get_type());
        $this->assertCount(2, $q->get_options());
        $this->assertContains('Item C', $q->get_options());
        $this->assertContains('Item D', $q->get_options());

        $this->assertCount(2, $q->get_stems());
        $this->assertContains('Item A', $q->get_stems());
        $this->assertContains('Item B', $q->get_stems());

        $validation = $q->get_validation();
        $this->assertInstanceOf(
            'Learnosity\Entities\QuestionTypes\choicematrix_validation',
            $validation);
        $this->assertEquals('exactMatch', $validation->get_scoring_type());

        $validResponse = $validation->get_valid_response();
        $this->assertInstanceOf(
            'Learnosity\Entities\QuestionTypes\choicematrix_validation_valid_response',
            $validResponse);
        $this->assertEquals(1, $validResponse->get_score());
        $this->assertEquals([[1], [0]], $validResponse->get_value());
        $this->assertFalse($q->get_multiple_responses());
    }
}
