<?php

namespace Learnosity\Tests\Processors\QtiV2\Out;

use Learnosity\Converter;
use Learnosity\Tests\Integration\Processors\QtiV2\Out\QuestionTypes\AbstractQuestionTypeTest;
use Learnosity\Utils\StringUtil;
use qtism\data\storage\xml\XmlDocument;
use qtism\runtime\rendering\markup\xhtml\XhtmlRenderingEngine;

class QuestionMapperTest extends AbstractQuestionTypeTest
{
    public function testMappingMcqQuestion()
    {
        $questionJson = $this->getFixtureFileContents('learnosityjsons/item_mcq.json');
        $question = json_decode($questionJson, true);
        list($xmlString, $messages) = Converter::convertLearnosityToQtiItem($question);
        $this->assertNotNull($xmlString);
        $this->assertTrue(StringUtil::startsWith($xmlString, '<?xml version="1.0" encoding="UTF-8"?>
<assessmentItem xmlns="http://www.imsglobal.org/xsd/imsqti_v2p1"'));

        $document = new XmlDocument();
        $document->loadFromString($xmlString);
        $this->assertNotNull($document);
    }
}
