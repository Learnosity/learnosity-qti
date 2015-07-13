<?php
namespace Learnosity\Tests\Unit\Mappers\QtiV2\In\Interactions;

use Learnosity\Entities\QuestionTypes\orderlist;
use Learnosity\Entities\QuestionTypes\orderlist_validation_valid_response;
use Learnosity\Processors\QtiV2\In\Interactions\OrderInteractionMapper;
use Learnosity\Processors\QtiV2\In\ResponseProcessingTemplate;
use Learnosity\Services\LogService;
use Learnosity\Tests\Unit\Mappers\QtiV2\In\Fixtures\OrderInteractionBuilder;
use Learnosity\Tests\Unit\Mappers\QtiV2\In\Fixtures\ResponseDeclarationBuilder;

class OrderInteractionTest extends AbstractInteractionTest
{
    public function testMismatchSimpleChoiceException()
    {
        $testOrderInteraction = OrderInteractionBuilder::buildOrderInteraction(
            'testOrderInteraction',
            [
                'I' => 'Order I',
                'J' => 'Order J',
                'K' => 'Order K'
            ],
            'testPrompt'
        );
        $responseProcessingTemplate = ResponseProcessingTemplate::matchCorrect();
        $responseDeclaration = ResponseDeclarationBuilder::buildWithCorrectResponse(
            'testIdentifier',
            ['C', 'B', 'A']
        );
        $mapper = new OrderInteractionMapper($testOrderInteraction, $responseDeclaration, $responseProcessingTemplate);
        $questionType = $mapper->getQuestionType();
        $this->assertCount(3, LogService::read());
    }

    public function testShuffleNotSupportedException()
    {
        $testOrderInteraction = OrderInteractionBuilder::buildOrderInteraction(
            'testOrderInteraction',
            [
                'A' => 'Order A',
                'B' => 'Order B',
                'C' => 'Order C'
            ],
            'testPrompt'
        );
        $testOrderInteraction->setShuffle(true);
        $responseProcessingTemplate = ResponseProcessingTemplate::matchCorrect();
        $responseDeclaration = ResponseDeclarationBuilder::buildWithCorrectResponse(
            'testIdentifier',
            ['C', 'B', 'A']
        );
        $mapper = new OrderInteractionMapper($testOrderInteraction, $responseDeclaration, $responseProcessingTemplate);
        $mapper->getQuestionType();
        $this->assertCount(1, LogService::read());
    }

    public function testShouldNotHandleMapResponseValidation()
    {
        $testOrderInteraction = OrderInteractionBuilder::buildOrderInteraction(
            'testOrderInteraction',
            [
                'A' => 'Order A',
                'B' => 'Order B',
                'C' => 'Order C'
            ],
            'testPrompt'
        );
        $responseProcessingTemplate = ResponseProcessingTemplate::mapResponse();
        $validResponseIdentifier = [
            'A' => [1, false],
            'B' => [2, false],
            'C' => [3, false]

        ];
        $responseDeclaration = ResponseDeclarationBuilder::buildWithMapping(
            'testIdentifier',
            $validResponseIdentifier
        );
        $mapper = new OrderInteractionMapper($testOrderInteraction, $responseDeclaration, $responseProcessingTemplate);
        $mapper->getQuestionType();
        $this->assertCount(1, LogService::read());
    }

    public function testShouldHandleMatchCorrectValidation()
    {
        $testOrderInteraction = OrderInteractionBuilder::buildOrderInteraction(
            'testOrderInteraction',
            [
                'A' => 'Order A',
                'B' => 'Order B',
                'C' => 'Order C'
            ],
            'testPrompt'
        );
        $responseProcessingTemplate = ResponseProcessingTemplate::matchCorrect();
        $responseDeclaration = ResponseDeclarationBuilder::buildWithCorrectResponse(
            'testIdentifier',
            ['C', 'B', 'A']
        );
        $mapper = new OrderInteractionMapper($testOrderInteraction, $responseDeclaration, $responseProcessingTemplate);


        /** @var orderlist $q */
        $q = $mapper->getQuestionType();
        $this->assertInstanceOf('Learnosity\Entities\QuestionTypes\orderlist', $q);
        $this->assertEquals('orderlist', $q->get_type());
        $this->assertEquals('testPrompt', $q->get_stimulus());
        $this->assertEquals(['Order A', 'Order B', 'Order C'], $q->get_list());

        $validation = $q->get_validation();
        $this->assertInstanceOf(
            'Learnosity\Entities\QuestionTypes\orderlist_validation',
            $validation
        );
        $this->assertEquals('exactMatch', $validation->get_scoring_type());

        /** @var orderlist_validation_valid_response $validResponse */
        $validResponse = $validation->get_valid_response();
        $this->assertInstanceOf(
            'Learnosity\Entities\QuestionTypes\orderlist_validation_valid_response',
            $validResponse
        );
        $this->assertEquals(1, $validResponse->get_score());
        $this->assertEquals([2, 1, 0], $validResponse->get_value());

        $this->assertNull($validation->get_alt_responses());
    }
}
