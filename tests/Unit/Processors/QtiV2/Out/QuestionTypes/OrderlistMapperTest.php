<?php

namespace LearnosityQti\Tests\Unit\Processors\QtiV2\Out\QuestionTypes;

use LearnosityQti\Entities\QuestionTypes\orderlist;
use LearnosityQti\Entities\QuestionTypes\orderlist_validation;
use LearnosityQti\Processors\Learnosity\In\ValidationBuilder\ValidationBuilder;
use LearnosityQti\Processors\Learnosity\In\ValidationBuilder\ValidResponse;
use LearnosityQti\Processors\QtiV2\Out\Constants;
use LearnosityQti\Processors\QtiV2\Out\QuestionTypes\OrderlistMapper;
use LearnosityQti\Utils\QtiMarshallerUtil;
use qtism\common\enums\Cardinality;
use qtism\data\content\interactions\OrderInteraction;
use qtism\data\content\interactions\Orientation;
use qtism\data\content\interactions\SimpleChoice;
use qtism\data\processing\ResponseProcessing;
use qtism\data\state\ResponseDeclaration;
use qtism\data\state\Value;

class OrderlistMapperTest extends \PHPUnit_Framework_TestCase
{
    public function testWithNoValidation()
    {
        $orderlist = $this->buildOrderlist([
            'ant',
            'elephant',
            'dog'
        ], 'Order these animal from big to small');

        /** @var orderlist_validation $validation */
        $orderlistMapper = new OrderlistMapper();
        list($interaction, $responseDeclaration, $responseProcessing) = $orderlistMapper->convert($orderlist, 'testIdentifier', 'testIdentifierLabel');
        $this->assertNull($responseDeclaration);
        $this->assertNull($responseProcessing);

        /** @var OrderInteraction $interaction */
        $this->assertTrue($interaction instanceof OrderInteraction);
        $this->assertEquals(false, $interaction->mustShuffle());
        $this->assertEquals(-1, $interaction->getMaxChoices());
        $this->assertEquals(-1, $interaction->getMinChoices());
        $this->assertEquals(Orientation::VERTICAL, $interaction->getOrientation());

        /** @var SimpleChoice[] $choices */
        $choices = $interaction->getSimpleChoices()->getArrayCopy(true);
        $this->assertEquals('CHOICE_0', $choices[0]->getIdentifier());
        $this->assertEquals('ant', QtiMarshallerUtil::marshallCollection($choices[0]->getComponents()));
        $this->assertEquals('CHOICE_1', $choices[1]->getIdentifier());
        $this->assertEquals('elephant', QtiMarshallerUtil::marshallCollection($choices[1]->getComponents()));
        $this->assertEquals('CHOICE_2', $choices[2]->getIdentifier());
        $this->assertEquals('dog', QtiMarshallerUtil::marshallCollection($choices[2]->getComponents()));
    }

    public function testWithSimpleExactMatchValidation()
    {
        $orderlist = $this->buildOrderlist([
            'ant',
            'elephant',
            'dog'
        ], 'Order these animal from big to small');
        /** @var orderlist_validation $validation */
        $validation = ValidationBuilder::build('orderlist', 'exactMatch', [
            new ValidResponse(1, [1, 2, 0]),
            new ValidResponse(1, [2, 1, 0])
        ]);
        $orderlist->set_validation($validation);

        /** @var orderlist_validation $validation */
        $orderlistMapper = new OrderlistMapper();
        /** @var ResponseDeclaration $responseDeclaration */
        /** @var ResponseProcessing $responseProcessing */
        list($interaction, $responseDeclaration, $responseProcessing) = $orderlistMapper->convert($orderlist, 'testIdentifier', 'testIdentifierLabel');

        /** @var OrderInteraction $interaction */
        $this->assertTrue($interaction instanceof OrderInteraction);

        // Assert response declaration is correct
        /** @var Value[] $correctResponseValues */
        $this->assertEquals(Cardinality::ORDERED, $responseDeclaration->getCardinality());
        $correctResponseValues = $responseDeclaration->getCorrectResponse()->getValues()->getArrayCopy(true);
        $this->assertEquals('CHOICE_1', $correctResponseValues[0]->getValue());
        $this->assertEquals('CHOICE_2', $correctResponseValues[1]->getValue());
        $this->assertEquals('CHOICE_0', $correctResponseValues[2]->getValue());

        $this->assertEquals(Constants::RESPONSE_PROCESSING_TEMPLATE_MATCH_CORRECT, $responseProcessing->getTemplate());
    }

    public function testWithComplexExactMatchValidation()
    {
        $orderlist = $this->buildOrderlist([
            'ant',
            'elephant',
            'dog'
        ], 'Order these animal from big to small');
        /** @var orderlist_validation $validation */
        $validation = ValidationBuilder::build('orderlist', 'exactMatch', [
            new ValidResponse(3, [1, 2, 0]),
            new ValidResponse(1, [2, 1, 0])
        ]);
        $orderlist->set_validation($validation);

        /** @var orderlist_validation $validation */
        $orderlistMapper = new OrderlistMapper();
        /** @var ResponseDeclaration $responseDeclaration */
        /** @var ResponseProcessing $responseProcessing */
        list($interaction, $responseDeclaration, $responseProcessing) = $orderlistMapper->convert($orderlist, 'testIdentifier', 'testIdentifierLabel');

        /** @var OrderInteraction $interaction */
        $this->assertTrue($interaction instanceof OrderInteraction);

        // Assert response declaration is correct
        /** @var Value[] $correctResponseValues */
        $this->assertEquals(Cardinality::ORDERED, $responseDeclaration->getCardinality());
        $correctResponseValues = $responseDeclaration->getCorrectResponse()->getValues()->getArrayCopy(true);
        $this->assertEquals('CHOICE_1', $correctResponseValues[0]->getValue());
        $this->assertEquals('CHOICE_2', $correctResponseValues[1]->getValue());
        $this->assertEquals('CHOICE_0', $correctResponseValues[2]->getValue());

        $this->assertEquals(Constants::RESPONSE_PROCESSING_TEMPLATE_MATCH_CORRECT, $responseProcessing->getTemplate());
    }

    private function buildOrderlist(array $choices, $stimulus = '')
    {
        $list = [];
        foreach ($choices as $value) {
            $list[] = $value;
        }
        $question = new orderlist('orderlist', $list);
        if (!empty($stimulus)) {
            $question->set_stimulus($stimulus);
        }
        return $question;
    }
}
