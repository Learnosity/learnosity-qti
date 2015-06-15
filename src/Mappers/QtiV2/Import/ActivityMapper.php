<?php

namespace Learnosity\Mappers\QtiV2\Import;

use Learnosity\Mappers\QtiV2\Import\Utils\QtiV2Util;
use Learnosity\Utils\FileSystemUtil;

class ActivityMapper
{
    private $dom;
    private $xml;
    private $assessmentTestRootPath;

    public function __construct($assessmentTestFilePath) {

        $this->assessmentTestRootPath = dirname($assessmentTestFilePath);
        $xmlContent = FileSystemUtil::readFile($assessmentTestFilePath)->getContents();
        $this->xml= new \DOMDocument();
        $this->xml->loadXML($xmlContent);
    }

    public function parse()
    {
        $assessmentSectionsElement = QtiV2Util::queryXpath($this->xml, null, '//qti:testPart/qti:assessmentSection');
        foreach($assessmentSectionsElement as $element) {
            $assessmentItemRefElements = QtiV2Util::queryXpath($element->asXML(), '//qti:assessmentItemRef');
            foreach($assessmentItemRefElements as $assessmentItemRefElement) {
                $itemURI = (string)$assessmentItemRefElement->attributes()->href;
                $itemId = (string)$assessmentItemRefElement->attributes()->identifier;
                $parser = new ItemParser($this->assessmentTestRootPath .DIRECTORY_SEPARATOR. $itemURI);
                list($layout, $questions) = $parser->parse();
                die;
            }
            die;
           // $assessmentItemsElement = QtiV2Util::queryXpath($assessmentSectionElement, '//qti:a')
        }
    }
} 
