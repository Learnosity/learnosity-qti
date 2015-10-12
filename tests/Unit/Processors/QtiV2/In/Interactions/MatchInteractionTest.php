<?php

namespace LearnosityQti\Tests\Unit\Processors\QtiV2\In\Interactions;

use LearnosityQti\Entities\QuestionTypes\choicematrix;
use LearnosityQti\Entities\QuestionTypes\choicematrix_validation;
use LearnosityQti\Entities\QuestionTypes\choicematrix_validation_valid_response;
use LearnosityQti\Processors\QtiV2\In\Interactions\MatchInteractionMapper;
use LearnosityQti\Processors\QtiV2\In\ResponseProcessingTemplate;
use LearnosityQti\Tests\Unit\Processors\QtiV2\In\Fixtures\MatchInteractionBuilder;
use LearnosityQti\Tests\Unit\Processors\QtiV2\In\Fixtures\ResponseDeclarationBuilder;
use qtism\common\datatypes\DirectedPair;

class MatchInteractionTest extends AbstractInteractionTest
{
    public function testShouldHandleMapResponseValidationWithMultipleResponses()
    {
        $testMatchInteraction = MatchInteractionBuilder::buildMatchInteraction(
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
            ],
            true
        );

        $testMatchInteraction->setMaxAssociations(3);
        $responseProcessingTemplate = ResponseProcessingTemplate::mapResponse();
        $validResponseIdentifier = [
            'A D' => [1, false],
            'B C' => [2, false],
            'A E' => [3, false]
        ];
        $responseDeclaration = ResponseDeclarationBuilder::buildWithMapping(
            'testIdentifier',
            $validResponseIdentifier,
            'DirectedPair'
        );

        $mapper = new MatchInteractionMapper($testMatchInteraction, $responseDeclaration, $responseProcessingTemplate);
        /** @var choicematrix $choicematrix */
        $choicematrix = $mapper->getQuestionType();
        $this->assertTrue($choicematrix instanceof choicematrix);
        $this->assertEquals('choicematrix', $choicematrix->get_type());
        $this->assertCount(3, $choicematrix->get_options());
        $this->assertContains('Item C', $choicematrix->get_options());
        $this->assertContains('Item D', $choicematrix->get_options());
        $this->assertContains('Item E', $choicematrix->get_options());

        $this->assertCount(2, $choicematrix->get_stems());
        $this->assertContains('Item A', $choicematrix->get_stems());
        $this->assertContains('Item B', $choicematrix->get_stems());

        $validation = $choicematrix->get_validation();
        $this->assertTrue($validation instanceof choicematrix_validation);
        $this->assertEquals('exactMatch', $validation->get_scoring_type());

        $validResponse = $validation->get_valid_response();
        $this->assertTrue($validResponse instanceof choicematrix_validation_valid_response);
        $this->assertEquals(6, $validResponse->get_score());
        $this->assertEquals([[1, 2], [0]], $validResponse->get_value());
        $this->assertTrue($choicematrix->get_multiple_responses());
    }

    public function testShouldHandleMatchCorrectValidationWithMultipleResponses()
    {
        $testMatchInteraction = MatchInteractionBuilder::buildMatchInteraction(
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
            ],
            true
        );

        $testMatchInteraction->setMaxAssociations(3);
        $responseProcessingTemplate = ResponseProcessingTemplate::matchCorrect();
        $validResponseIdentifier = [
            new DirectedPair('A', 'D'),
            new DirectedPair('B', 'C'),
            new DirectedPair('A', 'E')
        ];
        $responseDeclaration = ResponseDeclarationBuilder::buildWithCorrectResponse(
            'testIdentifier',
            $validResponseIdentifier
        );

        $mapper = new MatchInteractionMapper($testMatchInteraction, $responseDeclaration, $responseProcessingTemplate);
        /** @var choicematrix $choicematrix */
        $choicematrix = $mapper->getQuestionType();
        $this->assertTrue($choicematrix instanceof choicematrix);
        $this->assertEquals('choicematrix', $choicematrix->get_type());
        $this->assertCount(3, $choicematrix->get_options());
        $this->assertContains('Item C', $choicematrix->get_options());
        $this->assertContains('Item D', $choicematrix->get_options());
        $this->assertContains('Item E', $choicematrix->get_options());

        $this->assertCount(2, $choicematrix->get_stems());
        $this->assertContains('Item A', $choicematrix->get_stems());
        $this->assertContains('Item B', $choicematrix->get_stems());

        $validation = $choicematrix->get_validation();
        $this->assertTrue($validation instanceof choicematrix_validation);
        $this->assertEquals('exactMatch', $validation->get_scoring_type());

        $validResponse = $validation->get_valid_response();
        $this->assertTrue($validResponse instanceof choicematrix_validation_valid_response);
        $this->assertEquals(1, $validResponse->get_score());
        $this->assertEquals([[1, 2], [0]], $validResponse->get_value());
        $this->assertTrue($choicematrix->get_multiple_responses());
    }


    public function testShouldHandleMatchCorrectValidationWithoutMultipleResponses()
    {
        $interaction = MatchInteractionBuilder::buildMatchInteraction(
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
            ]
        );
        $interaction->setMaxAssociations(2);
        $responseProcessingTemplate = ResponseProcessingTemplate::matchCorrect();
        $validResponseIdentifier = [
            new DirectedPair('A', 'D'),
            new DirectedPair('B', 'C')
        ];
        $responseDeclaration = ResponseDeclarationBuilder::buildWithCorrectResponse(
            'testIdentifier',
            $validResponseIdentifier
        );

        $mapper = new MatchInteractionMapper($interaction, $responseDeclaration, $responseProcessingTemplate);

        /** @var choicematrix $choicematrix */
        $choicematrix = $mapper->getQuestionType();
        $this->assertInstanceOf('LearnosityQti\Entities\QuestionTypes\choicematrix', $choicematrix);
        $this->assertEquals('choicematrix', $choicematrix->get_type());
        $this->assertCount(2, $choicematrix->get_options());
        $this->assertContains('Item C', $choicematrix->get_options());
        $this->assertContains('Item D', $choicematrix->get_options());

        $this->assertCount(2, $choicematrix->get_stems());
        $this->assertContains('Item A', $choicematrix->get_stems());
        $this->assertContains('Item B', $choicematrix->get_stems());

        $validation = $choicematrix->get_validation();
        $this->assertTrue($validation instanceof choicematrix_validation);
        $this->assertEquals('exactMatch', $validation->get_scoring_type());

        $validResponse = $validation->get_valid_response();
        $this->assertTrue($validResponse instanceof choicematrix_validation_valid_response);
        $this->assertEquals(1, $validResponse->get_score());
        $this->assertEquals([[1], [0]], $validResponse->get_value());
        $this->assertFalse($choicematrix->get_multiple_responses());
    }
}
