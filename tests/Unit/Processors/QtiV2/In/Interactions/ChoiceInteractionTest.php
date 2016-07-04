<?php

namespace LearnosityQti\Tests\Unit\Processors\QtiV2\In\Interactions;

use LearnosityQti\Processors\QtiV2\In\Interactions\ChoiceInteractionMapper;
use LearnosityQti\Processors\QtiV2\In\ResponseProcessingTemplate;
use LearnosityQti\Services\LogService;
use LearnosityQti\Tests\Unit\Processors\QtiV2\In\Fixtures\ChoiceInteractionBuilder;
use LearnosityQti\Tests\Unit\Processors\QtiV2\In\Fixtures\ResponseDeclarationBuilder;
use qtism\common\enums\Cardinality;
use qtism\data\content\FlowStaticCollection;
use qtism\data\content\InlineCollection;
use qtism\data\content\interactions\Orientation;
use qtism\data\content\interactions\Prompt;
use qtism\data\content\TextRun;
use qtism\data\content\xhtml\text\P;

class ChoiceInteractionTest extends AbstractInteractionTest
{
    public function testShouldHandleNoValidation()
    {
        $responseProcessingDeclaration = null;
        $responseProcessingTemplate = ResponseProcessingTemplate::none();
        $optionsMap = [
            'choiceA' => 'Choice A',
            'choiceB' => 'Choice B',
            'choiceC' => 'Choice C'
        ];
        $interactionMapper = new ChoiceInteractionMapper(
            ChoiceInteractionBuilder::buildSimple('testIdentifier', $optionsMap),
            $responseProcessingDeclaration,
            $responseProcessingTemplate
        );

        $questionType = $interactionMapper->getQuestionType();
        $this->assertNotNull($questionType);
        $this->assertNull($questionType->get_validation());
        $this->assertEquals('mcq', $questionType->get_type());
        $this->assertCount(3, $questionType->get_options());
    }

    public function testShouldHandleMatchCorrectValidation()
    {
        $validResponseIdentifier = ['one', 'two'];
        $responseDeclaration = ResponseDeclarationBuilder::buildWithCorrectResponse(
            'testIdentifier',
            $validResponseIdentifier
        );
        $responseProcessingTemplate = ResponseProcessingTemplate::matchCorrect();
        $optionsMap = [
            'one' => 'Label One',
            'two' => 'Label Two',
            'three' => 'Label Three'
        ];
        $interaction = ChoiceInteractionBuilder::buildSimple('testIdentifier', $optionsMap);
        $interactionMapper = new ChoiceInteractionMapper($interaction, $responseDeclaration, $responseProcessingTemplate);
        $mcq = $interactionMapper->getQuestionType();
        $this->assertEquals('mcq', $mcq->get_type());

        $validation = $mcq->get_validation();
        $this->assertNotNull($validation);

        $this->assertEquals(['one', 'two'], $validation->get_valid_response()->get_value());
        $this->assertEquals(1, $validation->get_valid_response()->get_score());

        $this->assertEquals(count($optionsMap), count($mcq->get_options()));
        foreach ($mcq->get_options() as $option) {
            $this->assertArrayHasKey($option['value'], $optionsMap);
            $this->assertEquals($option['label'], $optionsMap[$option['value']]);
        }
    }

    public function testShouldHandleMapResponseValidation()
    {
        $responseDeclaration = ResponseDeclarationBuilder::buildWithMapping(
            'testIdentifier',
            [
                'one' => [1, false],
                'two' => [3, false]
            ]
        );
        $responseProcessingTemplate = ResponseProcessingTemplate::mapResponse();
        $optionsMap = [
            'one' => 'Label One',
            'two' => 'Label Two',
            'three' => 'Label Three'
        ];
        $interaction = ChoiceInteractionBuilder::buildSimple('testIdentifier', $optionsMap);
        $interactionMapper = new ChoiceInteractionMapper($interaction, $responseDeclaration, $responseProcessingTemplate);
        $mcq = $interactionMapper->getQuestionType();
        $this->assertEquals('mcq', $mcq->get_type());

        $validation = $mcq->get_validation();
        $this->assertNotNull($validation);
        $this->assertEquals(['two'], $validation->get_valid_response()->get_value());
        $this->assertEquals(3, $validation->get_valid_response()->get_score());
        $this->assertEquals(['one'], $validation->get_alt_responses()[0]->get_value());
        $this->assertEquals(1, $validation->get_alt_responses()[0]->get_score());

        // Verify options are sort of correct
        $this->assertEquals(count($optionsMap), count($mcq->get_options()));
        foreach ($mcq->get_options() as $option) {
            $this->assertArrayHasKey($option['value'], $optionsMap);
            $this->assertEquals($option['label'], $optionsMap[$option['value']]);
        }
    }

    public function testShouldHandleMultipleResponseIfMaxChoiceMoreThanOne()
    {
        $interaction = ChoiceInteractionBuilder::buildSimple('testIdentifier', [
            'choiceA' => 'Choice A',
            'choiceB' => 'Choice B',
            'choiceC' => 'Choice C'
        ]);
        $interaction->setMaxChoices(2);
        $interactionMapper = new ChoiceInteractionMapper($interaction);
        $questionType = $interactionMapper->getQuestionType();
        $this->assertTrue($questionType->get_multiple_responses());
    }

    public function testShouldMaxChoiceIsZeroAsMultipleResponsesMcq()
    {
        $interaction = ChoiceInteractionBuilder::buildSimple('testIdentifier', [
            'choiceA' => 'Choice A',
            'choiceB' => 'Choice B',
            'choiceC' => 'Choice C'
        ]);
        $interaction->setMaxChoices(0);
        $interactionMapper = new ChoiceInteractionMapper($interaction);
        $questionType = $interactionMapper->getQuestionType();
        $this->assertTrue($questionType->get_multiple_responses());
    }

    public function testShouldHandleMultipleCardinalityWithMatchCorrect()
    {
        $responseDeclaration = ResponseDeclarationBuilder::buildWithCorrectResponse(
            'testIdentifier',
            ['one', 'two']
        );
        $responseDeclaration->setCardinality(Cardinality::MULTIPLE);
        $responseProcessingTemplate = ResponseProcessingTemplate::matchCorrect();
        $optionsMap = [
            'one' => 'Label One',
            'two' => 'Label Two',
            'three' => 'Label Three'
        ];
        $interaction = ChoiceInteractionBuilder::buildSimple('testIdentifier', $optionsMap);
        $interaction->setMaxChoices(0);
        $interactionMapper = new ChoiceInteractionMapper($interaction, $responseDeclaration, $responseProcessingTemplate);
        $mcq = $interactionMapper->getQuestionType();
        $this->assertEquals('mcq', $mcq->get_type());

        $validation = $mcq->get_validation();
        $this->assertNotNull($validation);
        $this->assertEquals(['one', 'two'], $validation->get_valid_response()->get_value());
        $this->assertEquals(1, $validation->get_valid_response()->get_score());
    }

    public function testShouldHandleMultipleCardinalityWithMapResponse()
    {
        $responseDeclaration = ResponseDeclarationBuilder::buildWithMapping(
            'testIdentifier',
            [
                'one' => [1, false],
                'two' => [3, false]
            ]
        );
        $responseDeclaration->setCardinality(Cardinality::MULTIPLE);
        $responseProcessingTemplate = ResponseProcessingTemplate::mapResponse();
        $optionsMap = [
            'one' => 'Label One',
            'two' => 'Label Two',
            'three' => 'Label Three'
        ];
        $interaction = ChoiceInteractionBuilder::buildSimple('testIdentifier', $optionsMap);
        $interaction->setMaxChoices(0);
        $interactionMapper = new ChoiceInteractionMapper($interaction, $responseDeclaration, $responseProcessingTemplate);
        $mcq = $interactionMapper->getQuestionType();
        $this->assertEquals('mcq', $mcq->get_type());

        $validation = $mcq->get_validation();
        $this->assertNotNull($validation);
        $this->assertEquals(['two', 'one'], $validation->get_valid_response()->get_value());
        $this->assertEquals(4, $validation->get_valid_response()->get_score());
        $this->assertEquals(['two'], $validation->get_alt_responses()[0]->get_value());
        $this->assertEquals(3, $validation->get_alt_responses()[0]->get_score());
        $this->assertEquals(['one'], $validation->get_alt_responses()[1]->get_value());
        $this->assertEquals(1, $validation->get_alt_responses()[1]->get_score());

        // Verify options are sort of correct
        $this->assertEquals(count($optionsMap), count($mcq->get_options()));
        foreach ($mcq->get_options() as $option) {
            $this->assertArrayHasKey($option['value'], $optionsMap);
            $this->assertEquals($option['label'], $optionsMap[$option['value']]);
        }
    }

    public function testShouldShuffle()
    {
        $interaction = ChoiceInteractionBuilder::buildSimple('testIdentifier', ['choiceA' => 'Choice A']);
        $interaction->setShuffle(true);
        $interactionMapper = new ChoiceInteractionMapper($interaction);
        $questionType = $interactionMapper->getQuestionType();
        $this->assertTrue($questionType->get_shuffle_options());
    }

    public function testHorizontalOrientation()
    {
        $interaction = ChoiceInteractionBuilder::buildSimple('testIdentifier', ['choiceA' => 'Choice A']);
        $interaction->setOrientation(Orientation::HORIZONTAL);
        $interactionMapper = new ChoiceInteractionMapper($interaction);
        $questionType = $interactionMapper->getQuestionType();
        $this->assertTrue($questionType->get_ui_style()->get_type() === 'horizontal');
        $this->assertTrue($questionType->get_ui_style()->get_columns() === 1);
    }

    public function testPrompt()
    {
        $interaction = ChoiceInteractionBuilder::buildSimple('testIdentifier', ['choiceA' => 'Choice A']);

        $prompt = new Prompt();
        $promptContent = new FlowStaticCollection();
        $promptContent->attach(new TextRun('Test'));
        $htmlCollection = new InlineCollection();
        $htmlCollection->attach(new TextRun('123'));
        $p = new P();
        $p->setContent($htmlCollection);
        $promptContent->attach($p);
        $prompt->setContent($promptContent);
        $interaction->setPrompt($prompt);

        $interactionMapper = new ChoiceInteractionMapper($interaction);
        $questionType = $interactionMapper->getQuestionType();
        $this->assertTrue($questionType->get_stimulus() === 'Test<p>123</p>');
    }

    public function testHasMinChoice()
    {
        $validResponseIdentifier = ['one', 'two'];
        $responseDeclaration = ResponseDeclarationBuilder::buildWithCorrectResponse(
            'testIdentifier',
            $validResponseIdentifier
        );
        $responseProcessingTemplate = ResponseProcessingTemplate::matchCorrect();
        $optionsMap = [
            'one' => 'Label One',
            'two' => 'Label Two',
            'three' => 'Label Three'
        ];
        $interaction = ChoiceInteractionBuilder::buildSimple('testIdentifier', $optionsMap);
        $interaction->setMinChoices(1);
        $interactionMapper = new ChoiceInteractionMapper($interaction, $responseDeclaration, $responseProcessingTemplate);
        $interactionMapper->getQuestionType();
        $this->assertTrue(count(LogService::read()) === 1);
    }
}
