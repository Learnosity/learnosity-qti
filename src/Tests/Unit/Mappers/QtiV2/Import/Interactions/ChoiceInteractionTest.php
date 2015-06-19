<?php

namespace Learnosity\Tests\Unit\Mappers\QtiV2\Import\Interactions;

use Learnosity\Mappers\QtiV2\Import\Interactions\ChoiceInteraction;
use Learnosity\Mappers\QtiV2\Import\ResponseProcessingTemplate;
use Learnosity\Tests\Unit\Mappers\QtiV2\Import\Fixtures\ChoiceInteractionBuilder;
use Learnosity\Tests\Unit\Mappers\QtiV2\Import\Fixtures\ResponseDeclarationBuilder;
use qtism\data\content\interactions\ChoiceInteraction as QtiChoiceInteraction;
use qtism\data\content\interactions\SimpleChoice;
use qtism\data\content\interactions\SimpleChoiceCollection;
use qtism\data\processing\ResponseProcessing;
use qtism\data\state\CorrectResponse;
use qtism\data\state\ResponseDeclaration;
use qtism\data\state\Value;
use qtism\data\state\ValueCollection;

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
            'one'   => 'Label One',
            'two'   => 'Label Two',
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
}
