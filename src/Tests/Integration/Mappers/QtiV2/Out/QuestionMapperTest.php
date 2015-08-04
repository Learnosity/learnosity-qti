<?php

namespace Learnosity\Tests\Mappers\QtiV2\Out;

use Learnosity\Converter;
use Learnosity\Utils\FileSystemUtil;
use qtism\data\storage\xml\XmlDocument;
use qtism\runtime\rendering\markup\xhtml\XhtmlRenderingEngine;

class QuestionMapperTest extends \PHPUnit_Framework_TestCase
{
    public function testMappingMcqQuestion()
    {
        $questionJson = FileSystemUtil::readFile(FileSystemUtil::getRootPath() . '/src/Tests/Fixtures/learnosityjsons/longtext.json')->getContents();
        list($xmlString, $messages) = Converter::convertLearnosityToQtiItem($questionJson);
        $this->assertNotNull($xmlString);

        $document = new XmlDocument();
        $document->loadFromString($xmlString);

        $engine = new XhtmlRenderingEngine();
        $renderResult = $engine->render($document->getDocumentComponent());
        $body = $renderResult->saveXml($renderResult->documentElement);
        die;
    }
}
