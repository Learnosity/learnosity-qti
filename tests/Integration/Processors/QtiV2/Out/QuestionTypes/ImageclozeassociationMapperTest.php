<?php

namespace LearnosityQti\Tests\Integration\Processors\QtiV2\Out\QuestionTypes;

use LearnosityQti\Processors\QtiV2\Out\Constants;
use LearnosityQti\Utils\QtiMarshallerUtil;
use qtism\common\datatypes\DirectedPair;
use qtism\common\enums\BaseType;
use qtism\common\enums\Cardinality;
use qtism\data\AssessmentItem;
use qtism\data\content\interactions\GapImg;
use qtism\data\content\interactions\GraphicGapMatchInteraction;
use qtism\data\state\ResponseDeclaration;
use qtism\data\state\Value;

class ImageclozeassociationMapperTest extends AbstractQuestionTypeTest
{
    public function testSimpleCommonCase()
    {
        /** @var AssessmentItem $assessmentItem */
        $question = json_decode($this->getFixtureFileContents('learnosityjsons/data_imageclozeassociation.json'), true);
        $assessmentItem = $this->convertToAssessmentItem($question);

        /** @var GraphicGapMatchInteraction $interaction */
        $interaction = $assessmentItem->getComponentsByClassName('graphicGapMatchInteraction', true)->getArrayCopy()[0];
        $this->assertTrue($interaction instanceof GraphicGapMatchInteraction);

        // And its prompt is mapped correctly
        $promptString = QtiMarshallerUtil::marshallCollection($interaction->getPrompt()->getComponents());
        $this->assertEquals('<p>[This is the stem.]</p>', $promptString);

        // And its gapimages mapped well
        /** @var GapImg[] $gapImages */
        $gapImages = $interaction->getGapImgs()->getArrayCopy();
        $this->assertEquals(3, count($gapImages));

        $this->assertEquals('CHOICE_0', $gapImages[0]->getIdentifier());
        $object0 = $gapImages[0]->getComponents()->current();
        $this->assertEquals('image/png', $object0->getType());
        $this->assertEquals(56, $object0->getWidth());
        $this->assertEquals(13, $object0->getHeight());

        $this->assertEquals('CHOICE_1', $gapImages[1]->getIdentifier());
        $object1 = $gapImages[1]->getComponents()->current();
        $this->assertEquals('image/png', $object1->getType());
        $this->assertEquals(56, $object1->getWidth());
        $this->assertEquals(13, $object1->getHeight());

        $this->assertEquals('CHOICE_2', $gapImages[2]->getIdentifier());
        $object2 = $gapImages[2]->getComponents()->current();
        $this->assertEquals('image/png', $object2->getType());
        $this->assertEquals(100, $object2->getWidth());
        $this->assertEquals(100, $object2->getHeight());

        // And its associableHotspot
        // TODO: Do more through assert with coords and matchmax/matchmin check
        $this->assertEquals(3, $interaction->getAssociableHotspots()->count());

        // And its response processing and response declaration
        $this->assertEquals(Constants::RESPONSE_PROCESSING_TEMPLATE_MATCH_CORRECT, $assessmentItem->getResponseProcessing()->getTemplate());
        /** @var ResponseDeclaration $responseDeclaration */
        $responseDeclaration = $assessmentItem->getResponseDeclarations()->getArrayCopy()[0];
        $this->assertEquals(Cardinality::MULTIPLE, $responseDeclaration->getCardinality());
        $this->assertEquals(BaseType::DIRECTED_PAIR, $responseDeclaration->getBaseType());

        /** @var Value[] $values */
        $values = $responseDeclaration->getCorrectResponse()->getValues()->getArrayCopy(true);
        $this->assertDirectPair($values[0]->getValue(), 'ASSOCIABLEHOTSPOT_0', 'CHOICE_2');
        $this->assertDirectPair($values[1]->getValue(), 'ASSOCIABLEHOTSPOT_1', 'CHOICE_1');
        $this->assertDirectPair($values[2]->getValue(), 'ASSOCIABLEHOTSPOT_2', 'CHOICE_0');

        // And, we don't have mapping because we simply won't
        $this->assertEquals(null, $responseDeclaration->getMapping());
    }

    private function assertDirectPair(DirectedPair $pair, $expectedFirstValue, $expectedSecondValue)
    {
        $this->assertEquals($expectedFirstValue, $pair->getFirst());
        $this->assertEquals($expectedSecondValue, $pair->getSecond());
    }
}
