<?php

namespace LearnosityQti\Tests\Integration\Processors\QtiV2\Out\QuestionTypes;

use LearnosityQti\Entities\QuestionTypes\imageclozeassociation;
use LearnosityQti\Processors\QtiV2\Out\QuestionTypes\ImageclozeassociationMapper;
use LearnosityQti\Entities\QuestionTypes\imageclozeassociation_image;
use LearnosityQti\Entities\QuestionTypes\imageclozeassociation_metadata;
use LearnosityQti\Entities\QuestionTypes\imageclozeassociation_response_container;
use LearnosityQti\Entities\QuestionTypes\imageclozeassociation_validation_valid_response;
use LearnosityQti\Entities\QuestionTypes\imageclozeassociation_validation;
use LearnosityQti\Processors\QtiV2\Out\Constants;
use LearnosityQti\Services\ConvertToQtiService;
use LearnosityQti\Utils\QtiMarshallerUtil;
use qtism\common\datatypes\QtiDirectedPair;
use qtism\common\enums\BaseType;
use qtism\common\enums\Cardinality;
use qtism\data\content\interactions\GapImg;
use qtism\data\content\interactions\GraphicGapMatchInteraction;
use qtism\data\state\ResponseDeclaration;
use qtism\data\state\Value;

class ImageclozeassociationMapperTest extends \PHPUnit_Framework_TestCase
{
    
    public function testSimpleCommonCase()
    {
        $question = $this->buildSimpleImageClozeassociationQuestion();
        $imagePath = realpath($_SERVER["DOCUMENT_ROOT"]).'/Fixtures/assets/world_map.png';
        $mock = $this->getMock('ConvertToQtiService', array('getInstance'));
         
		// Replace protected self reference with mock object
        $ref = new ReflectionProperty('LearnosityQti\Services\ConvertToQtiService', 'instance');
		$ref->setAccessible(true);
		$ref->setValue(null, $mock);
			
        $path = $mock->expects($this->once())
            ->method('getInputPath')
            ->will($this->returnValue($imagePath));
		
		/** @var graphicGapMatchInteraction $interaction */
        $mapper = new ImageclozeassociationMapper();
        //$mapper->attach($mock);
        list($interaction, $responseDeclaration, $responseProcessing) = $mapper->convert($question, 'testIdentifier', 'testIdentifier');
        
        /** @var GraphicGapMatchInteraction $interaction */
        $interaction = $assessmentItem->getComponentsByClassName('graphicGapMatchInteraction', true)->getArrayCopy()[0];
        $this->assertTrue($interaction instanceof GraphicGapMatchInteraction);

        // And its prompt is mapped correctly
        $promptString = QtiMarshallerUtil::marshallCollection($interaction->getPrompt()->getComponents());
        $this->assertEquals('Imagecloze association question', $promptString);

        //print_r($interaction); die;
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

    private function assertDirectPair(QtiDirectedPair $pair, $expectedFirstValue, $expectedSecondValue)
    {
        $this->assertEquals($expectedFirstValue, $pair->getFirst());
        $this->assertEquals($expectedSecondValue, $pair->getSecond());
    }
    
    private function buildSimpleImageClozeassociationQuestion()
    {
        $imagePath = realpath($_SERVER["DOCUMENT_ROOT"]) . '/Fixtures/assets/world_map.png';
        $imageObj = new imageclozeassociation_image();
        $imageObj->set_src($imagePath);
        $response_positions = [
            ['x' => "0.14", 'y' => "48"],
            ['x' => "35.1", 'y' => "73.57"],
            ['x' => "-2.97", 'y' => "20.24"],
            ['x' => "42.65", 'y' => "40.48"]
        ];
        $possible_responses = ["Choice A", "Choice B", "Choice C", "Choice D"];
        $response_container = new imageclozeassociation_response_container;
        $response_container->set_pointer('left');
        $response_container->set_vertical_top(true);
        $question = new imageclozeassociation('imageclozeassociation', $imageObj, $response_positions, $possible_responses);
        $question->set_stimulus('Imagecloze association question');
        $question->set_response_container($response_container);

        return $question;
    }
}
