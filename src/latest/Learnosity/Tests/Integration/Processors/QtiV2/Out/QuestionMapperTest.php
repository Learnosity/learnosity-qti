<?php

namespace Learnosity\Tests\Processors\QtiV2\Out;

use Learnosity\Tests\Integration\Processors\QtiV2\Out\QuestionTypes\AbstractQuestionTypeTest;
use qtism\data\storage\xml\XmlDocument;
use qtism\runtime\rendering\markup\xhtml\XhtmlRenderingEngine;

class QuestionMapperTest extends AbstractQuestionTypeTest
{
    public function testMappingMcqQuestion()
    {
        $this->markTestIncomplete();

        $questionJson = $this->getFixtureFileContents('learnosityjsons/item_mcq.json');
        list($xmlString, $messages) = Converter::convertLearnosityToQtiItem($questionJson);
        $this->assertNotNull($xmlString);

        $document = new XmlDocument();
        $document->loadFromString($xmlString);

        $engine = new XhtmlRenderingEngine();
        $renderResult = $engine->render($document->getDocumentComponent());
        $body = $renderResult->saveXml($renderResult->documentElement);
    }
}
