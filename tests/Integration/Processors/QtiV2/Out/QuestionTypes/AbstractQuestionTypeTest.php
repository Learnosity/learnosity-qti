<?php

namespace LearnosityQti\Tests\Integration\Processors\QtiV2\Out\QuestionTypes;

use LearnosityQti\Converter;
use LearnosityQti\Tests\AbstractTest;
use qtism\data\AssessmentItem;
use qtism\data\storage\xml\XmlDocument;
use LearnosityQti\Processors\QtiV2\Out\Constants as LearnosityExportConstant;

abstract class AbstractQuestionTypeTest extends AbstractTest {

    protected function convertToAssessmentItem(array $data) {
        $content = $data['content'];
        $features = $data['features'];
        $assessmentItemArray = array();
        foreach ($data['questions'] as $question) {
            $question['feature'] = $features;
            $question['content'] = $content;
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
