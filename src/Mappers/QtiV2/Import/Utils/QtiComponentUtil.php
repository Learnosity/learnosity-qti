<?php

namespace Learnosity\Mappers\QtiV2\Import\Utils;

use DOMDocument;
use DOMXPath;
use qtism\common\datatypes\Shape;
use qtism\data\content\xhtml\Img;
use qtism\data\content\xhtml\Object;
use qtism\data\QtiComponent;
use qtism\data\QtiComponentCollection;
use qtism\data\storage\xml\marshalling\MarshallerFactory;

class QtiComponentUtil
{
    const MIME_IMAGE = 'image';
    const MIME_AUDIO = 'audio';
    const MIME_HTML = 'html';
    const MIME_NOT_SUPPORTED = 'na';

    public static function marshallCollection(QtiComponentCollection $collection)
    {
        $results = [];
        foreach ($collection as $component) {
            $results[] = self::marshall($component);
        }
        return implode('', $results);
    }

    public static function marshall(QtiComponent $component)
    {
        $marshallerFactory = new MarshallerFactory();
        $marshaller = $marshallerFactory->createMarshaller($component);
        $element = $marshaller->marshall($component);

        $dom = new \DOMDocument();
        $dom->preserveWhiteSpace = false;
        $dom->formatOutput = false;
        $node = $dom->importNode($element, true);
        $dom->appendChild($node);

        // processing all object tags in the final dom
        $xp = new DOMXpath($dom);
        $objectNodeList = $xp->query('//object');
        foreach ($objectNodeList as $value) {
            $newElement = self::processObject($value, $dom);
            if ($newElement) {
                $value->parentNode->replaceChild($newElement, $value);
            }
        }

        return trim($dom->saveHTML());
    }

    public static function processObject(\DOMElement $objectElement, $dom = null)
    {
        if (!$dom) {
            $dom = new DOMDocument('1.0', 'UTF-8');
        }
        switch (self::getMIMEType($objectElement->getAttribute('type'))) {
            case self::MIME_IMAGE:
                $element = $dom->createElement('img');
                $element->setAttribute('src', $objectElement->getAttribute('data'));
                return $element;
                break;
            case self::MIME_AUDIO:
                break;
            default:
                break;
        }
        return null;

    }

    public static function getMIMEType($mimeValue)
    {
        if (strpos($mimeValue, 'image') !== FALSE) {
            return self::MIME_IMAGE;
        } elseif (strpos($mimeValue, 'audio') !== FALSE) {
            return self::MIME_AUDIO;
        } elseif (strpos($mimeValue, 'html') !== FALSE) {
            return self::MIME_AUDIO;
        }
        return self::MIME_NOT_SUPPORTED;
    }

    /**
     * @param array $areaCoords
     * @param array $objectCoords
     * @param $qtiShape
     * @return array
     */
    public static function convertQtiCoordsToPercentage(array $areaCoords, array $objectCoords, $qtiShape)
    {
        switch ($qtiShape) {
            case Shape::RECT:
                return [
                    'x' => round($objectCoords[0] / $areaCoords[0] * 100, 4),
                    'y' => round($objectCoords[1] / $areaCoords[1] * 100, 4),
                    'width' => $objectCoords[2] - $objectCoords[0],
                    'height' => $objectCoords[3] - $objectCoords[1]
                ];
            default:
                return null;
        }

    }
}
