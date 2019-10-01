<?php

namespace LearnosityQti\Tests\Integration\Processors\QtiV2\Out\QuestionTypes;

use LearnosityQti\Converter;
use LearnosityQti\Tests\AbstractTest;
use qtism\data\AssessmentItem;
use qtism\data\storage\xml\XmlDocument;
use LearnosityQti\Processors\QtiV2\Out\Constants as LearnosityExportConstant;
use ReflectionProperty;

abstract class AbstractQuestionTypeTest extends AbstractTest {
   
    protected function convertToAssessmentItem(array $data) 
    {
        $imagePath = realpath($_SERVER["DOCUMENT_ROOT"]).'/Fixtures/assets/world_map.png';
        $mock = $this->getMock('ConvertToQtiService', array('getFormat','getInputPath'));
        // Replace protected self reference with mock object
        $ref = new ReflectionProperty('LearnosityQti\Services\ConvertToQtiService', 'instance');
	    $ref->setAccessible(true);
	    $ref->setValue(null, $mock);

        /*$format = $mock->expects($this->atLeastOnce())
				->method('getFormat')
				->will($this->returnValue('qti'));*/
		
		/*$inputPath = $mock->expects($this->atLeastOnce())
				->method('getInputPath')
				->will($this->returnValue($imagePath));*/
		
		$content = $data['content'];
        $features = $data['features'];
        $assessmentItemArray = array();
        foreach ($data['questions'] as $question) {
            $question['feature'] = $features;
            $question['content'] = $content;
            $question['itemreference'] = $data['reference'];
            if (in_array($question['data']['type'], LearnosityExportConstant::$supportedQuestionTypes)) {
                list($xml, $manifest) = Converter::convertLearnosityToQtiItem($question);

                // Assert the XML string is formed and not empty
                // Also, assert manifest is in form of array, regardless it was empty or not
                $this->assertTrue(is_string($xml) && !empty($xml));
                $this->assertTrue(is_array($manifest));
                
                $document = new XmlDocument();
                $document->loadFromString($xml);

                /** @var AssessmentItem $assessmentItem */
                $assessmentItem = $document->getDocumentComponent();
                $assessmentItemArray[] = $assessmentItem;
                // Basic assert on <assessmentItem> object
                $this->assertNotNull($assessmentItem);
                $this->assertTrue($assessmentItem instanceof AssessmentItem);
            }
        }
        return $assessmentItemArray;
    }

}
