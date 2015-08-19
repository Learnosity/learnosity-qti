<?php

namespace Learnosity\Tests\Integration\Processors\QtiV2\Out\QuestionTypes;

use Learnosity\Processors\QtiV2\Out\Constants;
use Learnosity\Utils\QtiMarshallerUtil;
use qtism\common\enums\Cardinality;
use qtism\data\content\interactions\OrderInteraction;
use qtism\data\content\interactions\SimpleChoice;
use qtism\data\state\ResponseDeclaration;
use qtism\data\state\Value;

class OrderlistMapperTest extends AbstractQuestionTypeTest
{
    public function testSimpleCase()
    {
        $data = json_decode($this->getFixtureFileContents('learnosityjsons/orderlist.json'), true);
        $assessmentItem = $this->convertToAssessmentItem($data);

        /** @var OrderInteraction $interaction */
        $interaction = $assessmentItem->getComponentsByClassName('orderInteraction', true)->getArrayCopy()[0];
        $this->assertTrue($interaction instanceof OrderInteraction);

        // And its prompt is mapped correctly
        $promptString = QtiMarshallerUtil::marshallCollection($interaction->getPrompt()->getComponents());
        $this->assertEquals('<p>[This is the stem.]</p>', $promptString);

        // Assert its choices are correct
        /** @var SimpleChoice[] $simpleChoices */
        $simpleChoices = $interaction->getSimpleChoices()->getArrayCopy(true);
        $this->assertEquals('CHOICE_0', $simpleChoices[0]->getIdentifier());
        $this->assertEquals('[Choice A]', QtiMarshallerUtil::marshallCollection($simpleChoices[0]->getComponents()));
        $this->assertEquals('CHOICE_1', $simpleChoices[1]->getIdentifier());
        $this->assertEquals('[Choice B]', QtiMarshallerUtil::marshallCollection($simpleChoices[1]->getComponents()));
        $this->assertEquals('CHOICE_2', $simpleChoices[2]->getIdentifier());
        $this->assertEquals('[Choice C]', QtiMarshallerUtil::marshallCollection($simpleChoices[2]->getComponents()));
        $this->assertEquals('CHOICE_3', $simpleChoices[3]->getIdentifier());
        $this->assertEquals('[Choice D]', QtiMarshallerUtil::marshallCollection($simpleChoices[3]->getComponents()));

        // Also assert its validation is match_correct and correct
        /** @var ResponseDeclaration $responseDeclaration */
        $responseDeclaration = $assessmentItem->getResponseDeclarations()->getArrayCopy()[0];
        $this->assertEquals(Cardinality::ORDERED, $responseDeclaration->getCardinality());

        /** @var Value[] $correctResponseValues */
        $correctResponseValues = $responseDeclaration->getCorrectResponse()->getValues()->getArrayCopy(true);
        $this->assertEquals('CHOICE_3', $correctResponseValues[0]->getValue());
        $this->assertEquals('CHOICE_1', $correctResponseValues[1]->getValue());
        $this->assertEquals('CHOICE_0', $correctResponseValues[2]->getValue());
        $this->assertEquals('CHOICE_2', $correctResponseValues[3]->getValue());

        $this->assertEquals(Constants::RESPONSE_PROCESSING_TEMPLATE_MATCH_CORRECT, $assessmentItem->getResponseProcessing()->getTemplate());
    }
}
