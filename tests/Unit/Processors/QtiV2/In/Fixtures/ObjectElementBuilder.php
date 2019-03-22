<?php
namespace LearnosityQti\Tests\Unit\Processors\QtiV2\In\Fixtures;

use DOMDocument;
use qtism\data\content\ObjectFlowCollection;
use qtism\data\content\TextRun;
use qtism\data\content\xhtml\ObjectElement;

class ObjectElementBuilder
{

    public static function buildDomImageObject($data,$type) {

        $dom = new DOMDocument('1.0', 'UTF-8');
        $testObjElement = $dom->createElement('object');
        $testObjElement->setAttribute('data', $data);
        $testObjElement->setAttribute('type', $type);
        return $testObjElement;

    }

    public static function buildQtiImageObject($data, $type) {
        $object = new ObjectElement($data, $type);
        $objectFlowCollection = new ObjectFlowCollection();
        $objectFlowCollection->attach(new TextRun('qwe'));
        $object->setContent($objectFlowCollection);
        return $object;
    }
}
