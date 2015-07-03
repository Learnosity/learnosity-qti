<?php

namespace Learnosity\Tests\Unit\Mappers\QtiV2\Import\Utils;

use DOMDocument;
use Learnosity\Mappers\QtiV2\Import\Utils\QtiComponentUtil;
use Learnosity\Tests\Unit\Mappers\QtiV2\Import\Fixtures\ObjectElementBuilder;
use PHPUnit_Framework_TestCase;
use qtism\data\content\InlineCollection;
use qtism\data\content\ObjectFlowCollection;
use qtism\data\content\TextRun;
use qtism\data\content\xhtml\Object;
use qtism\data\content\xhtml\text\P;

class QtiComponentUtilTest extends PHPUnit_Framework_TestCase
{
    public function testProcessObjectImage()
    {
        $sampleObject = ObjectElementBuilder::buildDomImageObject('http://test.com/test.png', 'image/png');
        $res = QtiComponentUtil::processObject($sampleObject);
        $this->assertInstanceOf('DOMElement', $res);
        $this->assertEquals('img', $res->nodeName);
        $this->assertEquals('http://test.com/test.png', $res->getAttribute('src'));
    }

    public function testMarshallObjectImageNested()
    {
        $p = new P();
        $inlineCollection = new InlineCollection();
        $inlineCollection->attach(ObjectElementBuilder::buildQtiImageObject('http://test.com/test.png', 'image/png'));
        $p->setContent($inlineCollection);
        $res = QtiComponentUtil::marshall($p);
        $this->assertEquals('<p><img src="http://test.com/test.png"></p>', $res);
    }

    public function testMarshallObjectImageSimple() {
        $obj = new Object('http://test.com/test.png', 'image/png');
        $res = QtiComponentUtil::marshall($obj);
        $this->assertEquals('<img src="http://test.com/test.png">', $res);
    }
}
