<?php

namespace LearnosityQti\Tests\Integration\Processors\QtiV2\Out\QuestionTypes;

use LearnosityQti\Processors\QtiV2\Out\Constants;
use LearnosityQti\Utils\QtiMarshallerUtil;
use qtism\common\enums\BaseType;
use qtism\common\enums\Cardinality;
use qtism\data\content\interactions\ChoiceInteraction;
use qtism\data\content\interactions\Orientation;
use qtism\data\content\interactions\SimpleChoice;
use qtism\data\state\CorrectResponse;
use qtism\data\state\ResponseDeclaration;
use qtism\data\state\Value;

class McqMapperTest extends AbstractQuestionTypeTest
{
    public function testSimpleCase() {
        $data = json_decode($this->getFixtureFileContents('learnosityjsons/mcq.json'), true);

        $assessmentItemArray = $this->convertToAssessmentItem($data);

        foreach ($assessmentItemArray as $assessmentItem) {
            // Simple validation on <responseDeclaration> and <responseProcessing>
            $this->assertEquals(1, $assessmentItem->getResponseDeclarations()->count());
            $this->assertNotNull($assessmentItem->getResponseProcessing());

            // Has <extendedTextInteraction> as the first and only interaction
            /** @var ChoiceInteraction $interaction */
            $interaction = $assessmentItem->getComponentsByClassName('choiceInteraction', true)->getArrayCopy()[0];
            $this->assertTrue($interaction instanceof ChoiceInteraction);

            // And its prompt is mapped correctly
            $promptString = QtiMarshallerUtil::marshallCollection($interaction->getPrompt()->getComponents());
            $this->assertEquals('<p>What is the capital of USA ?</p>', trim($promptString));

            // All the choices also mapped properly
            /** @var SimpleChoice[] $simpleChoices */
            $simpleChoices = $interaction->getSimpleChoices()->getArrayCopy(true);
            $this->assertEquals($simpleChoices[0]->getIdentifier(), 'CHOICE_0');
            $this->assertEquals(QtiMarshallerUtil::marshallCollection($simpleChoices[0]->getContent()), 'Washington DC');
            $this->assertEquals($simpleChoices[1]->getIdentifier(), 'CHOICE_1');
            $this->assertEquals(QtiMarshallerUtil::marshallCollection($simpleChoices[1]->getContent()), 'Delhi');
            $this->assertEquals($simpleChoices[2]->getIdentifier(), 'CHOICE_2');
            $this->assertEquals(QtiMarshallerUtil::marshallCollection($simpleChoices[2]->getContent()), 'Maskow');
            $this->assertEquals($simpleChoices[3]->getIdentifier(), 'CHOICE_3');
            $this->assertEquals(QtiMarshallerUtil::marshallCollection($simpleChoices[3]->getContent()), 'Canbera');

            // Check `minChoices` and `maxChoices`
            $this->assertEquals(1, $interaction->getMinChoices());
            $this->assertEquals(1, $interaction->getMaxChoices());

            // No shuffle option obviously
            $this->assertFalse($interaction->mustShuffle());

            // The usual layout
            $this->assertEquals(Orientation::VERTICAL, $interaction->getOrientation());
        }
    }

    public function testMultipleResponseMcq()
    {
        $data = json_decode($this->getFixtureFileContents('learnosityjsons/item_mcq.json'), true);
        $assessmentItemArray = $this->convertToAssessmentItem($data);
        $this->assertCount(2, $assessmentItemArray);
        // Has <extendedTextInteraction> as the first and only interaction
        /** @var ChoiceInteraction $interactionOne */
        /** @var ChoiceInteraction $interactionTwo */
        $interactionsArray = array();
        $responseDeclarationArray = array();
        foreach($assessmentItemArray as $assessmentItem) {
            $interactionsArray[] = $assessmentItem->getComponentsByClassName('choiceInteraction', true)->getArrayCopy();
            $responseDeclarationArray[] = $assessmentItem->getResponseDeclarations()->getArrayCopy(true);
            
        }
        $interactionOne = $interactionsArray[0][0];
        $interactionTwo = $interactionsArray[1][0];
        
        $this->assertCount(2, $responseDeclarationArray);
        $this->assertTrue($interactionOne instanceof ChoiceInteraction && $interactionTwo instanceof ChoiceInteraction);

        // Simple validation on <responseDeclaration> and <responseProcessing>
        
        $this->assertEquals(Constants::RESPONSE_PROCESSING_TEMPLATE_MATCH_CORRECT, $assessmentItem->getResponseProcessing()->getTemplate());
        $responseDeclarationOne = $responseDeclarationArray[0][$interactionOne->getResponseIdentifier()];
        $responseDeclarationTwo = $responseDeclarationArray[1][$interactionTwo->getResponseIdentifier()];
        // Assert the actual interactions and its response declaration
        $this->assertInteractionOne($interactionOne, $responseDeclarationOne);
        $this->assertInteractionTwo($interactionTwo, $responseDeclarationTwo);
    }

    private function assertInteractionOne(ChoiceInteraction $interaction, ResponseDeclaration $responseDeclaration)
    {
        // And its prompt is mapped correctly
        $promptString = QtiMarshallerUtil::marshallCollection($interaction->getPrompt()->getComponents());
        $this->assertEquals('Which of the following are not equals to 1', $promptString);

        // All the choices also mapped properly
        /** @var SimpleChoice[] $simpleChoices */
        $simpleChoices = $interaction->getSimpleChoices()->getArrayCopy(true);
        $this->assertEquals($simpleChoices[0]->getIdentifier(), 'CHOICE_0');
        $this->assertEquals(QtiMarshallerUtil::marshallCollection($simpleChoices[0]->getContent()), '\\(5^2+\\sqrt[n]{19}\\)');
        $this->assertEquals($simpleChoices[1]->getIdentifier(), 'CHOICE_1');
        $this->assertEquals(QtiMarshallerUtil::marshallCollection($simpleChoices[1]->getContent()), '\\(9\\)');
        $this->assertEquals($simpleChoices[2]->getIdentifier(), 'CHOICE_2');
        $this->assertEquals(QtiMarshallerUtil::marshallCollection($simpleChoices[2]->getContent()), '\\(8+3\\)');
        $this->assertEquals($simpleChoices[3]->getIdentifier(), 'CHOICE_3');
        $this->assertEquals(QtiMarshallerUtil::marshallCollection($simpleChoices[3]->getContent()), '\\(\\left(\\frac{5}{9}\\right)+\\pi\\)');

        // Check the other stuff
        $this->assertEquals(1, $interaction->getMinChoices());
        $this->assertEquals(4, $interaction->getMaxChoices());
        $this->assertTrue($interaction->mustShuffle());
        $this->assertEquals(Orientation::VERTICAL, $interaction->getOrientation());

        // Check the response declaration fine for mcq with multiple responses
        $this->assertEquals(BaseType::IDENTIFIER, $responseDeclaration->getBaseType());
        $this->assertEquals(Cardinality::MULTIPLE, $responseDeclaration->getCardinality());

        $correctResponse = $responseDeclaration->getCorrectResponse();
        $this->assertTrue($correctResponse instanceof CorrectResponse);
        /** @var Value[] $values */
        $values = $correctResponse->getValues()->getArrayCopy(true);
        $this->assertEquals($values[0]->getValue(), 'CHOICE_0');
        $this->assertEquals($values[1]->getValue(), 'CHOICE_1');
        $this->assertEquals($values[2]->getValue(), 'CHOICE_3');

        $this->assertNull($responseDeclaration->getMapping());
    }

    private function assertInteractionTwo(ChoiceInteraction $interaction, ResponseDeclaration $responseDeclaration)
    {
        // And its prompt is mapped correctly
        $promptString = QtiMarshallerUtil::marshallCollection($interaction->getPrompt()->getComponents());
        $this->assertEquals('Pick the odd one out', $promptString);

        // All the choices also mapped properly
        /** @var SimpleChoice[] $simpleChoices */
        $simpleChoices = $interaction->getSimpleChoices()->getArrayCopy(true);
        $this->assertEquals($simpleChoices[0]->getIdentifier(), 'CHOICE_0');
        $this->assertEquals(QtiMarshallerUtil::marshallCollection($simpleChoices[0]->getContent()), 'Tomato');
        $this->assertEquals($simpleChoices[1]->getIdentifier(), 'CHOICE_1');
        $this->assertEquals(QtiMarshallerUtil::marshallCollection($simpleChoices[1]->getContent()), 'Orange');
        $this->assertEquals($simpleChoices[2]->getIdentifier(), 'CHOICE_2');
        $this->assertEquals(QtiMarshallerUtil::marshallCollection($simpleChoices[2]->getContent()), 'Celery');
        $this->assertEquals($simpleChoices[3]->getIdentifier(), 'CHOICE_3');
        $this->assertEquals(QtiMarshallerUtil::marshallCollection($simpleChoices[3]->getContent()), 'Pear');

        // Check usual stuff
        $this->assertEquals(1, $interaction->getMinChoices());
        $this->assertEquals(1, $interaction->getMaxChoices());
        $this->assertFalse($interaction->mustShuffle());
        $this->assertEquals(Orientation::HORIZONTAL, $interaction->getOrientation());

        // Check the response declaration fine for mcq with multiple responses
        $this->assertEquals(BaseType::IDENTIFIER, $responseDeclaration->getBaseType());
        $this->assertEquals(Cardinality::SINGLE, $responseDeclaration->getCardinality());

        $correctResponse = $responseDeclaration->getCorrectResponse();
        $this->assertTrue($correctResponse instanceof CorrectResponse);
        /** @var Value[] $values */
        $values = $correctResponse->getValues()->getArrayCopy(true);
        $this->assertEquals($values[0]->getValue(), 'CHOICE_2');

        $this->assertNull($responseDeclaration->getMapping());
    }
}
