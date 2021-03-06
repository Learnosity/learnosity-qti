<?php

namespace LearnosityQti\Tests\Unit\Processors\QtiV2\In\Interactions;

use LearnosityQti\Entities\QuestionTypes\clozeassociation;
use LearnosityQti\Processors\QtiV2\In\Interactions\GapMatchInteractionMapper;
use LearnosityQti\Processors\QtiV2\In\ResponseProcessingTemplate;
use LearnosityQti\Services\LogService;
use LearnosityQti\Tests\Unit\Processors\QtiV2\In\Fixtures\GapMatchInteractionBuilder;
use LearnosityQti\Tests\Unit\Processors\QtiV2\In\Fixtures\ResponseDeclarationBuilder;
use qtism\common\datatypes\QtiDirectedPair;

class GapMatchInteractionTest extends AbstractInteractionTest
{
    public function testWithMapResponseValidationMissingGapIdentifier()
    {
        $testInteraction = GapMatchInteractionBuilder::buildGapMatchInteraction(
                'testGapMatchInteraction',
                [
                    'A' => 'Gap A',
                    'B' => 'Gap B',
                    'C' => 'Gap C'
                ],
                [],
                ['G1', 'G2']
            );
        $responseProcessingTemplate = ResponseProcessingTemplate::mapResponse();
        $validResponseIdentifier = [
            'A G1' => [1, false],
            'B G1' => [2, false]
        ];
        $responseDeclaration = ResponseDeclarationBuilder::buildWithMapping(
            'testIdentifier',
            $validResponseIdentifier,
            'QtiDirectedPair'
        );
        $mapper = new GapMatchInteractionMapper($testInteraction, $responseDeclaration, $responseProcessingTemplate);
        /** @var clozeassociation $q */
        $q = $mapper->getQuestionType();
        $this->assertEquals('clozeassociation', $q->get_type());
        $this->assertEquals('<p>{{response}}{{response}}</p>', $q->get_template());
        $this->assertEquals(['Gap A', 'Gap B', 'Gap C'], $q->get_possible_responses());
        $this->assertFalse($q->get_duplicate_responses());
        $this->assertNull($q->get_validation());

        $errorMessages = LogService::read();
        $this->assertCount(1, $errorMessages);
        $this->assertStringEndsWith('Amount of gap identifiers 2 does not match the amount 1 for <responseDeclaration>', $errorMessages[0]);
    }

    public function testWithMatchCorrectValidationMissingGapIdentifier()
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
                ['G1', 'G2']
            );
        $responseProcessingTemplate = ResponseProcessingTemplate::matchCorrect();
        $validResponseIdentifier = [
            new QtiDirectedPair('A', 'G1')
        ];
        $responseDeclaration =
            ResponseDeclarationBuilder::buildWithCorrectResponse('testIdentifier', $validResponseIdentifier);
        $mapper = new GapMatchInteractionMapper($testInteraction, $responseDeclaration, $responseProcessingTemplate);
        /** @var clozeassociation $q */
        $q = $mapper->getQuestionType();
        $this->assertEquals('clozeassociation', $q->get_type());
        $this->assertEquals('<p>{{response}}{{response}}</p>', $q->get_template());
        $this->assertEquals(['Gap A', 'Gap B', 'Gap C'], $q->get_possible_responses());
        $this->assertFalse($q->get_duplicate_responses());
        $this->assertNull($q->get_validation());

        $errorMessages = LogService::read();
        $this->assertCount(1, $errorMessages);
        $this->assertStringEndsWith('Amount of gap identifiers 2 does not match the amount 1 for <responseDeclaration>', $errorMessages[0]);
    }

    public function testWithMapResponseValidationDuplicatedResponses()
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
                ['G1', 'G2']
            );
        $responseProcessingTemplate = ResponseProcessingTemplate::mapResponse();
        $validResponseIdentifier = [
            'A G1' => [1, false],
            'B G1' => [2, false],
            'C G2' => [3, false],
            'B G2' => [4, false]
        ];
        $responseDeclaration = ResponseDeclarationBuilder::buildWithMapping(
            'testIdentifier',
            $validResponseIdentifier,
            'QtiDirectedPair'
        );
        $mapper = new GapMatchInteractionMapper($testInteraction, $responseDeclaration, $responseProcessingTemplate);
        /** @var clozeassociation $q */
        $q = $mapper->getQuestionType();
        $this->assertEquals('clozeassociation', $q->get_type());
        $this->assertEquals('<p>{{response}}{{response}}</p>', $q->get_template());
        $this->assertEquals(['Gap A', 'Gap B', 'Gap C'], $q->get_possible_responses());
        $this->assertTrue($q->get_duplicate_responses());

        $validation = $q->get_validation();
        $this->assertInstanceOf(
            'LearnosityQti\Entities\QuestionTypes\clozeassociation_validation',
            $validation
        );
        $this->assertEquals('exactMatch', $validation->get_scoring_type());

        $validResponse = $validation->get_valid_response();
        $this->assertInstanceOf(
            'LearnosityQti\Entities\QuestionTypes\clozeassociation_validation_valid_response',
            $validResponse
        );
        $this->assertEquals(6, $validResponse->get_score());
        $this->assertEquals(['Gap B', 'Gap B'], $validResponse->get_value());

        $altResponses = $validation->get_alt_responses();
        $this->assertCount(3, $altResponses);
        $this->assertEquals(5, $altResponses[0]->get_score());
        $this->assertEquals(['Gap A', 'Gap B'], $altResponses[0]->get_value());
        $this->assertEquals(5, $altResponses[1]->get_score());
        $this->assertEquals(['Gap B', 'Gap C'], $altResponses[1]->get_value());
        $this->assertEquals(4, $altResponses[2]->get_score());
        $this->assertEquals(['Gap A', 'Gap C'], $altResponses[2]->get_value());

        $errorMessages = LogService::read();
        $this->assertEmpty($errorMessages);
    }

    public function testWithMatchCorrectResponseValidationDuplicatedResponses()
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
                ['G1', 'G2']
            );
        $responseProcessingTemplate = ResponseProcessingTemplate::matchCorrect();
        $validResponseIdentifier = [
            new QtiDirectedPair('A', 'G1'),
            new QtiDirectedPair('B', 'G1'),
            new QtiDirectedPair('C', 'G2'),
            new QtiDirectedPair('B', 'G2')
        ];
        $responseDeclaration =
            ResponseDeclarationBuilder::buildWithCorrectResponse('testIdentifier', $validResponseIdentifier);
        $mapper = new GapMatchInteractionMapper($testInteraction, $responseDeclaration, $responseProcessingTemplate);
        /** @var clozeassociation $q */
        $q = $mapper->getQuestionType();
        $this->assertEquals('clozeassociation', $q->get_type());
        $this->assertEquals('<p>{{response}}{{response}}</p>', $q->get_template());
        $this->assertEquals(['Gap A', 'Gap B', 'Gap C'], $q->get_possible_responses());
        $this->assertTrue($q->get_duplicate_responses());

        $validation = $q->get_validation();
        $this->assertInstanceOf(
            'LearnosityQti\Entities\QuestionTypes\clozeassociation_validation',
            $validation
        );
        $this->assertEquals('exactMatch', $validation->get_scoring_type());

        $validResponse = $validation->get_valid_response();
        $this->assertInstanceOf(
            'LearnosityQti\Entities\QuestionTypes\clozeassociation_validation_valid_response',
            $validResponse
        );
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

        $errorMessages = LogService::read();
        $this->assertEmpty($errorMessages);
    }

    public function testWithMapResponseValidationMixedResponses()
    {
        $testInteraction = GapMatchInteractionBuilder::buildGapMatchInteraction(
            'testGapMatchInteraction',
            [
                'A' => 'Gap A',
                'B' => 'Gap B',
            ],
            [
                'C' => 'http://img_C',
                'D' => 'http://img_D'
            ],
            ['G1', 'G2']
        );
        $responseProcessingTemplate = ResponseProcessingTemplate::mapResponse();
        $validResponseIdentifier = [
            'A G1' => [1, false],
            'B G1' => [2, false],
            'C G2' => [3, false],
            'B G2' => [4, false],
            'D G1' => [5, false]
        ];
        $responseDeclaration = ResponseDeclarationBuilder::buildWithMapping(
            'testIdentifier',
            $validResponseIdentifier,
            'QtiDirectedPair'
        );
        $mapper = new GapMatchInteractionMapper($testInteraction, $responseDeclaration, $responseProcessingTemplate);
        /** @var clozeassociation $q */
        $q = $mapper->getQuestionType();
        $this->assertEquals('clozeassociation', $q->get_type());
        $this->assertEquals('<p>{{response}}{{response}}</p>', $q->get_template());
        $this->assertEquals(['Gap A', 'Gap B', '<img src="http://img_C" width="-1" height="-1"/>', '<img src="http://img_D" width="-1" height="-1"/>'], $q->get_possible_responses());
        $this->assertTrue($q->get_duplicate_responses());

        $validation = $q->get_validation();
        $this->assertInstanceOf(
            'LearnosityQti\Entities\QuestionTypes\clozeassociation_validation',
            $validation
        );
        $this->assertEquals('exactMatch', $validation->get_scoring_type());

        $validResponse = $validation->get_valid_response();
        $this->assertInstanceOf(
            'LearnosityQti\Entities\QuestionTypes\clozeassociation_validation_valid_response',
            $validResponse
        );
        $this->assertEquals(9, $validResponse->get_score());
        $this->assertEquals(['<img src="http://img_D" width="-1" height="-1"/>', 'Gap B'], $validResponse->get_value());

        $altResponses = $validation->get_alt_responses();
        $this->assertCount(5, $altResponses);
        $this->assertEquals(8, $altResponses[0]->get_score());
        $this->assertEquals(['<img src="http://img_D" width="-1" height="-1"/>', '<img src="http://img_C" width="-1" height="-1"/>'], $altResponses[0]->get_value());
        $this->assertEquals(6, $altResponses[1]->get_score());
        $this->assertEquals(['Gap B', 'Gap B'], $altResponses[1]->get_value());
        $this->assertEquals(5, $altResponses[2]->get_score());
        $this->assertEquals(['Gap A', 'Gap B'], $altResponses[2]->get_value());
        $this->assertEquals(5, $altResponses[3]->get_score());
        $this->assertEquals(['Gap B', '<img src="http://img_C" width="-1" height="-1"/>'], $altResponses[3]->get_value());
        $this->assertEquals(4, $altResponses[4]->get_score());
        $this->assertEquals(['Gap A', '<img src="http://img_C" width="-1" height="-1"/>'], $altResponses[4]->get_value());
    }

    public function testWithMatchCorrectResponseSwappedValuePair()
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
                ['G1', 'G2']
            );
        $responseProcessingTemplate = ResponseProcessingTemplate::matchCorrect();
        $validResponseIdentifier = [
            new QtiDirectedPair('G1', 'A'),
            new QtiDirectedPair('G1', 'B'),
            new QtiDirectedPair('G2', 'C'),
            new QtiDirectedPair('G2', 'B')
        ];
        $responseDeclaration =
            ResponseDeclarationBuilder::buildWithCorrectResponse('testIdentifier', $validResponseIdentifier);
        $mapper = new GapMatchInteractionMapper($testInteraction, $responseDeclaration, $responseProcessingTemplate);
        /** @var clozeassociation $q */
        $q = $mapper->getQuestionType();
        $this->assertEquals('clozeassociation', $q->get_type());
        $this->assertEquals('<p>{{response}}{{response}}</p>', $q->get_template());
        $this->assertEquals(['Gap A', 'Gap B', 'Gap C'], $q->get_possible_responses());
        $this->assertTrue($q->get_duplicate_responses());

        $validation = $q->get_validation();
        $this->assertInstanceOf(
            'LearnosityQti\Entities\QuestionTypes\clozeassociation_validation',
            $validation
        );
        $this->assertEquals('exactMatch', $validation->get_scoring_type());

        $validResponse = $validation->get_valid_response();
        $this->assertInstanceOf(
            'LearnosityQti\Entities\QuestionTypes\clozeassociation_validation_valid_response',
            $validResponse
        );
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

        $errorMessages = LogService::read();
        $this->assertEmpty($errorMessages);
    }

    public function testMapResponseWithSwappedValuePair()
    {
        $testInteraction = GapMatchInteractionBuilder::buildGapMatchInteraction(
            'testGapMatchInteraction',
            [
                'A' => 'Gap A',
                'B' => 'Gap B',
            ],
            [
                'C' => 'http://img_C',
                'D' => 'http://img_D'
            ],
            ['G1', 'G2']
        );

        $responseProcessingTemplate = ResponseProcessingTemplate::mapResponse();
        $validResponseIdentifier = [
            'G1 A' => [1, false],
            'G1 B' => [2, false],
            'G2 C' => [3, false],
            'G2 B' => [4, false],
            'D G1' => [5, false]
        ];
        $responseDeclaration = ResponseDeclarationBuilder::buildWithMapping(
            'testIdentifier',
            $validResponseIdentifier,
            'QtiDirectedPair'
        );
        $mapper = new GapMatchInteractionMapper($testInteraction, $responseDeclaration, $responseProcessingTemplate);
        /** @var clozeassociation $q */
        $q = $mapper->getQuestionType();
        $validation = $q->get_validation();
        $this->assertInstanceOf(
            'LearnosityQti\Entities\QuestionTypes\clozeassociation_validation',
            $validation
        );
        $this->assertEquals('exactMatch', $validation->get_scoring_type());

        $validResponse = $validation->get_valid_response();
        $this->assertInstanceOf(
            'LearnosityQti\Entities\QuestionTypes\clozeassociation_validation_valid_response',
            $validResponse
        );
        $this->assertEquals(9, $validResponse->get_score());
        $this->assertEquals(['<img src="http://img_D" width="-1" height="-1"/>', 'Gap B'], $validResponse->get_value());

        $altResponses = $validation->get_alt_responses();
        $this->assertCount(5, $altResponses);
        $this->assertEquals(8, $altResponses[0]->get_score());
        $this->assertEquals(['<img src="http://img_D" width="-1" height="-1"/>', '<img src="http://img_C" width="-1" height="-1"/>'], $altResponses[0]->get_value());
        $this->assertEquals(6, $altResponses[1]->get_score());
        $this->assertEquals(['Gap B', 'Gap B'], $altResponses[1]->get_value());
        $this->assertEquals(5, $altResponses[2]->get_score());
        $this->assertEquals(['Gap A', 'Gap B'], $altResponses[2]->get_value());
        $this->assertEquals(5, $altResponses[3]->get_score());
        $this->assertEquals(['Gap B', '<img src="http://img_C" width="-1" height="-1"/>'], $altResponses[3]->get_value());
        $this->assertEquals(4, $altResponses[4]->get_score());
        $this->assertEquals(['Gap A', '<img src="http://img_C" width="-1" height="-1"/>'], $altResponses[4]->get_value());
    }
}
