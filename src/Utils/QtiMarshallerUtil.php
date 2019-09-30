<?php
namespace LearnosityQti\Utils;

use DOMDocument;
use LearnosityQti\Exceptions\MappingException;
use LearnosityQti\Processors\QtiV2\Marshallers\LearnosityMarshallerFactory;
use LearnosityQti\Services\ConvertToLearnosityService;
use LearnosityQti\Utils\General\StringHelper;
use qtism\data\content\FeedbackInline;
use qtism\data\content\TextRun;
use qtism\data\content\xhtml\ObjectElement;
use qtism\data\QtiComponent;
use qtism\data\QtiComponentCollection;
use qtism\data\storage\xml\marshalling\Qti21MarshallerFactory;

class QtiMarshallerUtil
{

    public static function unmarshallElement($string)
    {
        try {
            libxml_use_internal_errors(true);

            $found = StringHelper::findStringPositionRecursive($string, '<math');
            $i = 0;
            if(is_array($found) && sizeof($found) > 0) {
                foreach($found as $index => $pos) {
                    if($i > 0){
                        $pos += 43*$i;
                    }
                    $string = substr_replace($string, "<math xmlns='http://www.w3.org/1998/Math/MathML'", $pos, 5);
                    $i++;
                }
            }
            $dom = new DOMDocument('1.0', 'UTF-8');
            $dom->formatOutput = true;

            // TODO: Can only unmarshall nice stuff, doesnt work with dodgy or invalid HTML
            if (!$dom->loadXML("<body>$string</body>", LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD)) {
                $errors = libxml_get_errors();
                throw new \Exception('Something wrong with XML format');
            }

            $marshallerFactory = new LearnosityMarshallerFactory();
            $components = new QtiComponentCollection();
            foreach ($dom->documentElement->childNodes as $element) {
                if ($element instanceof \DOMText) {
                    if (!empty(trim($element->data))) {
                        $component = new TextRun($element->nodeValue);
                    }
                } else {
                    $marshaller = $marshallerFactory->createMarshaller($element);
                    $component = $marshaller->unmarshall($element);
                }
                if (isset($component)) {
                    $components->attach($component);
                }
            }
            return $components;
        } catch (\Exception $e) {
            throw new MappingException('[Unable to transform to QTI] ' . $e->getMessage());
        }
    }

    public static function marshallValidCollection(QtiComponentCollection $collection)
    {
        $results = [];
        foreach ($collection as $component) {
            $results[] = static::marshallValidQti($component);
        }
        return implode('', $results);
    }

    public static function marshallCollection(QtiComponentCollection $collection)
    {
        $results = [];
        foreach ($collection as $component) {
            if ($component instanceof ObjectElement) {
                $results[] = static::marshallObjectData($component);
            } elseif (!($component instanceof FeedbackInline)) {
                $results[] = static::marshall($component);
            }
        }
        // Clean extra whitespace characters from the returned string
        $cleanedResults = trim(
            str_replace(
                ["\r\n", "\n", "\r", "\t"], "", implode('', $results)
            )
        );
        return $cleanedResults;
    }

    public static function marshall(QtiComponent $component)
    {
        $marshallerFactory = new LearnosityMarshallerFactory();
        $marshaller = $marshallerFactory->createMarshaller($component);
        $element = $marshaller->marshall($component);

        $dom = new \DOMDocument();
        $dom->preserveWhiteSpace = false;
        $dom->formatOutput = false;
        $dom->substituteEntities = false;
        $node = $dom->importNode($element, true);

        $string = $dom->saveXML($node);

        // TODO: Decode html entitfy back here, this is a hack until I can figure out
        // TODO: how to not do that with DomDocument.saveXML();
        return $string;
    }

    public static function marshallValidQti(QtiComponent $component)
    {
        $marshallerFactory = new Qti21MarshallerFactory();
        $marshaller = $marshallerFactory->createMarshaller($component);
        $element = $marshaller->marshall($component);

        $dom = new \DOMDocument();
        $dom->preserveWhiteSpace = false;
        $dom->formatOutput = false;
        $node = $dom->importNode($element, true);

        return $dom->saveXML($node);
    }

    public static function marshallObjectData(QtiComponent $component)
    {
        $result = '';
        $class = new \ReflectionClass(get_class($component));
        if (property_exists($component, 'data') && property_exists($component, 'type')) {
            $property = $class->getProperty('data');
            $property->setAccessible(true);
            $propertyType = $class->getProperty('type');
            $propertyType->setAccessible(true);
            $type = $propertyType->getValue($component);
            if ($type == 'text/html') {
                $learnosityServiceObject = ConvertToLearnosityService::getInstance();
                $inputPath = $learnosityServiceObject->getInputpath();
                $file = $inputPath . '/' . $property->getValue($component);
                $result = HtmlExtractorUtil::getHtmlData(($file));
            } else {
                $result = static::marshall($component);
            }
        }
        return $result;
    }
}
