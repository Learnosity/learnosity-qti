<?php

namespace LearnosityQti\Tests\Integration\Processors\QtiV2\Out\QuestionTypes;

use LearnosityQti\Utils\QtiMarshallerUtil;
use qtism\common\datatypes\QtiShape;
use qtism\common\enums\BaseType;
use qtism\common\enums\Cardinality;
use qtism\data\content\interactions\HotspotChoice;
use qtism\data\content\interactions\HotspotInteraction;
use qtism\data\state\CorrectResponse;
use qtism\data\state\ResponseDeclaration;
use qtism\data\state\Value;
use ReflectionProperty;

class HotspotMapperTest extends AbstractQuestionTypeTest
{
    public function testSimpleCase()
    {
        $data = json_decode($this->getFixtureFileContents('learnosityjsons/data_hotspot.json'), true);
        $mock = $this->getMock('ConvertToQtiService', array('getFormat'));
            
	    // Replace protected self reference with mock object
        $ref = new ReflectionProperty('LearnosityQti\Services\ConvertToQtiService', 'instance');
	    $ref->setAccessible(true);
	    $ref->setValue(null, $mock);
            
        /*$format = $mock->expects($this->once())
				->method('getFormat')
				->will($this->returnValue('qti'));*/
		
		$assessmentItemArray = $this->convertToAssessmentItem($data);
        foreach ($assessmentItemArray as $assessmentItem) {
			// Simple validation on <responseDeclaration> and <responseProcessing>
			$this->assertEquals(1, $assessmentItem->getResponseDeclarations()->count());
			$this->assertNotNull($assessmentItem->getResponseProcessing());

			// Get dah interaction
			/** @var HotspotInteraction $interaction */
			$interaction = $assessmentItem->getComponentsByClassName('hotspotInteraction', true)->getArrayCopy()[0];

			// And its prompt is mapped correctly
			$promptString = QtiMarshallerUtil::marshallCollection($interaction->getPrompt()->getComponents());
			$this->assertEquals('Hotspot question', $promptString);

			// All the choices also mapped properly
			/** @var HotspotChoice[] $choices */
			$choices = $interaction->getHotspotChoices()->getArrayCopy(true);
			$this->assertEquals($choices[0]->getIdentifier(), 'CHOICE_0');
			$this->assertEquals($choices[0]->getShape(), QtiShape::POLY); // QtiShape shall be POLY
			$this->assertEquals($choices[0]->getCoords()->count(), 96);
			$this->assertEquals($choices[1]->getIdentifier(), 'CHOICE_1');
			$this->assertEquals($choices[2]->getIdentifier(), 'CHOICE_2');
			$this->assertEquals($choices[3]->getIdentifier(), 'CHOICE_3');
			
			// Check 'minChoices' and 'maxChoices'
			$this->assertEquals(0, $interaction->getMinChoices());
			$this->assertEquals(1, $interaction->getMaxChoices());

			// Simple validation on <responseDeclaration> and <responseProcessing>
			$this->assertEquals(1, $assessmentItem->getResponseDeclarations()->count());
			$this->assertNotNull($assessmentItem->getResponseProcessing());
            
			// Check the response declaration looks good
			/** @var ResponseDeclaration $responseDeclaration */
			$responseDeclaration = $assessmentItem->getResponseDeclarations()->getArrayCopy(true)[$interaction->getResponseIdentifier()];
			$this->assertEquals(BaseType::IDENTIFIER, $responseDeclaration->getBaseType());
			$this->assertEquals(Cardinality::SINGLE, $responseDeclaration->getCardinality());

			$correctResponse = $responseDeclaration->getCorrectResponse();
			$this->assertTrue($correctResponse instanceof CorrectResponse);
			/** @var Value[] $values */
			$values = $correctResponse->getValues()->getArrayCopy(true);
			$this->assertEquals($values[0]->getValue(), 'CHOICE_0');
			$this->assertNull($responseDeclaration->getMapping());
		}
    }
}
