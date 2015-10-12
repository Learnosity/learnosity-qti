<?php

namespace Learnosity\Tests\Integration\Processors\QtiV2\Out\QuestionTypes;

use Learnosity\Converter;
use Learnosity\Tests\AbstractTest;
use qtism\data\AssessmentItem;
use qtism\data\storage\xml\XmlDocument;

abstract class AbstractQuestionTypeTest extends AbstractTest
{
    protected function convertToAssessmentItem(array $data)
    {
        list($xml, $manifest) = Converter::convertLearnosityToQtiItem($data);

        // Assert the XML string is formed and not empty
        // Also, assert manifest is in form of array, regardless it was empty or not
        $this->assertTrue(is_string($xml) && !empty($xml));
        $this->assertTrue(is_array($manifest));

        $document = new XmlDocument();
        $document->loadFromString($xml);

        /** @var AssessmentItem $assessmentItem */
        $assessmentItem = $document->getDocumentComponent();

        // Basic assert on <assessmentItem> object
        $this->assertNotNull($assessmentItem);
        $this->assertTrue($assessmentItem instanceof AssessmentItem);

        return $assessmentItem;
    }
}
