<?php

namespace Learnosity\Tests\Unit\Mappers\QtiV2\Import\Interactions;

use Learnosity\Mappers\QtiV2\Import\Interactions\ChoiceInteraction;
use Learnosity\Mappers\QtiV2\Import\ResponseProcessingTemplate;
use Learnosity\Tests\Unit\Mappers\QtiV2\Import\Fixtures\ChoiceInteractionBuilder;
use Learnosity\Tests\Unit\Mappers\QtiV2\Import\Fixtures\ResponseDeclarationBuilder;
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
        $responseProcessingTemplate = null;
        $optionsMap = [
            'choiceA' => 'Choice A',
            'choiceB' => 'Choice B',
            'choiceC' => 'Choice C'
        ];
        $interactionMapper = new ChoiceInteraction(
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
        $responseDeclaration = ResponseDeclarationBuilder::buildWithCorrectResponse('testIdentifier',
            $validResponseIdentifier);
        $responseProcessingTemplate = ResponseProcessingTemplate::matchCorrect();
        $optionsMap = [
            'one' => 'Label One',
            'two' => 'Label Two',
            'three' => 'Label Three'
        ];
        $interaction = ChoiceInteractionBuilder::buildSimple('testIdentifier', $optionsMap);
        $interactionMapper = new ChoiceInteraction($interaction, $responseDeclaration, $responseProcessingTemplate);
        $mcq = $interactionMapper->getQuestionType();
        $this->assertEquals('mcq', $mcq->get_type());

        $validation = $mcq->get_validation();
        $this->assertNotNull($validation);
        $this->assertEquals(count($validResponseIdentifier), count($validation->get_valid_response()->get_value()));
        $this->assertEquals(count($optionsMap), count($mcq->get_options()));
        foreach ($mcq->get_options() as $option) {
            $this->assertArrayHasKey($option['value'], $optionsMap);
            $this->assertEquals($option['label'], $optionsMap[$option['value']]);
        }
    }

    public function testShouldHandleInvalidValidation()
    {
        $validResponseIdentifier = ['one', 'two'];
        $responseDeclaration = ResponseDeclarationBuilder::buildWithCorrectResponse('testIdentifier',
            $validResponseIdentifier);
        $responseProcessingTemplate = ResponseProcessingTemplate::mapResponse();
        $optionsMap = [
            'one' => 'Label One',
            'two' => 'Label Two',
            'three' => 'Label Three'
        ];
        $interaction = ChoiceInteractionBuilder::buildSimple('testIdentifier', $optionsMap);
        $interactionMapper = new ChoiceInteraction($interaction, $responseDeclaration, $responseProcessingTemplate);
        $mcq = $interactionMapper->getQuestionType();
        $this->assertEquals('mcq', $mcq->get_type());

        $validation = $mcq->get_validation();
        $this->assertNull($validation);

        $this->assertTrue(count($interactionMapper->getExceptions()) === 1);
    }

    public function testShouldHandleMultipleResponseIfMaxChoiceMoreThanOne()
    {
        $interaction = ChoiceInteractionBuilder::buildSimple('testIdentifier', [
            'choiceA' => 'Choice A',
            'choiceB' => 'Choice B',
            'choiceC' => 'Choice C'
        ]);
        $interaction->setMaxChoices(2);
        $interactionMapper = new ChoiceInteraction($interaction);
        $questionType = $interactionMapper->getQuestionType();
        $this->assertTrue($questionType->get_multiple_responses());
    }

    public function testShouldShuffle()
    {
        $interaction = ChoiceInteractionBuilder::buildSimple('testIdentifier', ['choiceA' => 'Choice A']);
        $interaction->setShuffle(true);
        $interactionMapper = new ChoiceInteraction($interaction);
        $questionType = $interactionMapper->getQuestionType();
        $this->assertTrue($questionType->get_shuffle_options());
    }

    public function testHorizontalOrientation()
    {
        $interaction = ChoiceInteractionBuilder::buildSimple('testIdentifier', ['choiceA' => 'Choice A']);
        $interaction->setOrientation(Orientation::HORIZONTAL);
        $interactionMapper = new ChoiceInteraction($interaction);
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

        $interactionMapper = new ChoiceInteraction($interaction);
        $questionType = $interactionMapper->getQuestionType();
        $this->assertTrue($questionType->get_stimulus() === 'Test<p>123</p>');
    }

    public function testHasMinChoice()
    {
        $validResponseIdentifier = ['one', 'two'];
        $responseDeclaration = ResponseDeclarationBuilder::buildWithCorrectResponse('testIdentifier',
            $validResponseIdentifier);
        $responseProcessingTemplate = ResponseProcessingTemplate::matchCorrect();
        $optionsMap = [
            'one' => 'Label One',
            'two' => 'Label Two',
            'three' => 'Label Three'
        ];
        $interaction = ChoiceInteractionBuilder::buildSimple('testIdentifier', $optionsMap);
        $interaction->setMinChoices(1);
        $interactionMapper = new ChoiceInteraction($interaction, $responseDeclaration, $responseProcessingTemplate);
        $interactionMapper->getQuestionType();
        $this->assertTrue(count($interactionMapper->getExceptions()) === 1);
    }
}
