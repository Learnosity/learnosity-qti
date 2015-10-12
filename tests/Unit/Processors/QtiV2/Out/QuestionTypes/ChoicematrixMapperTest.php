<?php

namespace LearnosityQti\Tests\Unit\Processors\QtiV2\Out\QuestionTypes;

use LearnosityQti\Entities\QuestionTypes\choicematrix;
use LearnosityQti\Entities\QuestionTypes\choicematrix_validation;
use LearnosityQti\Processors\Learnosity\In\ValidationBuilder\ValidationBuilder;
use LearnosityQti\Processors\Learnosity\In\ValidationBuilder\ValidResponse;
use LearnosityQti\Processors\QtiV2\Out\Constants;
use LearnosityQti\Processors\QtiV2\Out\QuestionTypes\ChoicematrixMapper;
use LearnosityQti\Utils\QtiMarshallerUtil;
use qtism\common\datatypes\DirectedPair;
use qtism\data\content\interactions\MatchInteraction;
use qtism\data\content\interactions\SimpleAssociableChoice;
use qtism\data\processing\ResponseProcessing;
use qtism\data\state\MapEntry;
use qtism\data\state\ResponseDeclaration;
use qtism\data\state\Value;

class ChoicematrixMapperTest extends \PHPUnit_Framework_TestCase
{
    public function testSingularResponsesWithNoValidation()
    {
        $question = $this->buildSimpleChoiceMatrixQuestion();

        /** @var MatchInteraction $interaction */
        $mapper = new ChoicematrixMapper();
        list($interaction, $responseDeclaration, $responseProcessing) = $mapper->convert($question, 'testIdentifier', 'testIdentifier');
        $this->assertEquals('My stimulus string', QtiMarshallerUtil::marshallCollection($interaction->getPrompt()->getComponents()));
        $this->assertFalse($interaction->mustShuffle());
        $this->assertEquals(2, $interaction->getMaxAssociations());
        $this->assertEquals(2, $interaction->getMinAssociations());

        // Assert its source choices (stems)
        /** @var SimpleAssociableChoice[] $stemAssociableChoices */
        $stemAssociableChoices = $interaction->getSourceChoices()->getSimpleAssociableChoices()->getArrayCopy(true);
        $this->assertEquals('Stem 1', QtiMarshallerUtil::marshallCollection($stemAssociableChoices[0]->getContent()));
        $this->assertEquals('STEM_0', $stemAssociableChoices[0]->getIdentifier());
        $this->assertEquals('Stem 2', QtiMarshallerUtil::marshallCollection($stemAssociableChoices[1]->getContent()));
        $this->assertEquals('STEM_1', $stemAssociableChoices[1]->getIdentifier());
        foreach ($stemAssociableChoices as $choice) {
            $this->assertEquals(1, $choice->getMatchMax());
            $this->assertEquals(1, $choice->getMatchMin());
        }

        // Assert its target choices (options)
        /** @var SimpleAssociableChoice[] $optionAssociableChoices */
        $optionAssociableChoices = $interaction->getTargetChoices()->getSimpleAssociableChoices()->getArrayCopy(true);
        $this->assertEquals('Option 1', QtiMarshallerUtil::marshallCollection($optionAssociableChoices[0]->getContent()));
        $this->assertEquals('OPTION_0', $optionAssociableChoices[0]->getIdentifier());
        $this->assertEquals('Option 2', QtiMarshallerUtil::marshallCollection($optionAssociableChoices[1]->getContent()));
        $this->assertEquals('OPTION_1', $optionAssociableChoices[1]->getIdentifier());
        $this->assertEquals('Option 3', QtiMarshallerUtil::marshallCollection($optionAssociableChoices[2]->getContent()));
        $this->assertEquals('OPTION_2', $optionAssociableChoices[2]->getIdentifier());
        foreach ($optionAssociableChoices as $choice) {
            $this->assertEquals(2, $choice->getMatchMax());
            $this->assertEquals(1, $choice->getMatchMin());
        }
    }

    public function testWithMultipleResponses()
    {
        $question = $this->buildSimpleChoiceMatrixQuestion();
        $question->set_multiple_responses(true);

        /** @var MatchInteraction $interaction */
        $mapper = new ChoicematrixMapper();
        list($interaction, $responseDeclaration, $responseProcessing) = $mapper->convert($question, 'testIdentifier', 'testIdentifier');
        $this->assertEquals(6, $interaction->getMaxAssociations());

        /** @var SimpleAssociableChoice[] $stemAssociableChoices */
        $stemAssociableChoices = $interaction->getSourceChoices()->getSimpleAssociableChoices()->getArrayCopy(true);
        foreach ($stemAssociableChoices as $choice) {
            $this->assertEquals(3, $choice->getMatchMax()); // Option count
            $this->assertEquals(1, $choice->getMatchMin());
        }

        /** @var SimpleAssociableChoice[] $optionAssociableChoices */
        $optionAssociableChoices = $interaction->getTargetChoices()->getSimpleAssociableChoices()->getArrayCopy(true);
        $this->assertEquals(1, $interaction->getMinAssociations());
        foreach ($optionAssociableChoices as $choice) {
            $this->assertEquals(2, $choice->getMatchMax()); // Stem count
            $this->assertEquals(1, $choice->getMatchMin());
        }
    }

    public function testSingularResponsesWithValidation()
    {
        $question = $this->buildSimpleChoiceMatrixQuestion();

        /** @var choicematrix_validation $validation */
        $validation = ValidationBuilder::build('choicematrix', 'exactMatch', [
            new ValidResponse(1, [0, [2]]), // Test both using array of array (multiple response mode) and just a single value
            new ValidResponse(1, [0, 1]) // This is altresponse and shall be ignored
        ]);
        $question->set_validation($validation);

        /** @var MatchInteraction $interaction */
        /** @var ResponseDeclaration $responseDeclaration */
        /** @var ResponseProcessing $responseProcessing */
        $mapper = new ChoicematrixMapper();
        list($interaction, $responseDeclaration, $responseProcessing) = $mapper->convert($question, 'testIdentifier', 'testIdentifier');
        $this->assertEquals(2, $interaction->getMaxAssociations());
        $this->assertEquals(2, $interaction->getMinAssociations()); // Stem count

        /** @var Value[] $correctResponseValues */
        $correctResponseValues = $responseDeclaration->getCorrectResponse()->getValues()->getArrayCopy(true);
        $this->assertDirectPair($correctResponseValues[0]->getValue(), 'STEM_0', 'OPTION_0');
        $this->assertDirectPair($correctResponseValues[1]->getValue(), 'STEM_1', 'OPTION_2');

        $this->assertNull($responseDeclaration->getMapping());

        $this->assertEquals(Constants::RESPONSE_PROCESSING_TEMPLATE_MATCH_CORRECT, $responseProcessing->getTemplate());
    }

    public function testMultipleResponsesWithValidation()
    {
        $question = $this->buildSimpleChoiceMatrixQuestion();

        /** @var choicematrix_validation $validation */
        $validation = ValidationBuilder::build('choicematrix', 'exactMatch', [
            new ValidResponse(3, [0, [1, 2]]), // Test both using array of array (multiple response mode) and just a single value
            new ValidResponse(1, [0, 1]) // This is altresponse and shall be ignored
        ]);
        $question->set_validation($validation);
        $question->set_multiple_responses(true);

        /** @var MatchInteraction $interaction */
        /** @var ResponseDeclaration $responseDeclaration */
        /** @var ResponseProcessing $responseProcessing */
        $mapper = new ChoicematrixMapper();
        list($interaction, $responseDeclaration, $responseProcessing) = $mapper->convert($question, 'testIdentifier', 'testIdentifier');
        $this->assertEquals(6, $interaction->getMaxAssociations());
        $this->assertEquals(1, $interaction->getMinAssociations()); // Stem count

        /** @var Value[] $correctResponseValues */
        $correctResponseValues = $responseDeclaration->getCorrectResponse()->getValues()->getArrayCopy(true);
        $this->assertDirectPair($correctResponseValues[0]->getValue(), 'STEM_0', 'OPTION_0');
        $this->assertDirectPair($correctResponseValues[1]->getValue(), 'STEM_1', 'OPTION_1');
        $this->assertDirectPair($correctResponseValues[2]->getValue(), 'STEM_1', 'OPTION_2');

        // The validation in `choicematrix` relies on its key to describe the index of stem/option pair
        // Scoring is always set to `1`, with the upper bound is set to the actual valid_response`'s score
        $this->assertNull($responseDeclaration->getMapping());
    }

    private function buildSimpleChoiceMatrixQuestion()
    {
        $question = new choicematrix('choicematrix', [
            "Option 1",
            "Option 2",
            "Option 3"
        ], false, ["Stem 1", "Stem 2"]);
        $question->set_stimulus('My stimulus string');
        return $question;
    }

    private function assertDirectPair(DirectedPair $pair, $expectedFirstValue, $expectedSecondValue)
    {
        $this->assertEquals($expectedFirstValue, $pair->getFirst());
        $this->assertEquals($expectedSecondValue, $pair->getSecond());
    }
}
