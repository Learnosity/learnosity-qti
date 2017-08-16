<?php

namespace LearnosityQti\Processors\QtiV2\In\Processings;

use \LearnosityQti\Processors\QtiV2\In\Processings\AbstractXmlProcessing;
use \SimpleXMLElement;

class QtiV2AssessmentItemProcessing extends AbstractXmlProcessing
{
    const XML_TAG_NAME_ASSESSMENT_ITEM = 'assessmentItem';
    const XML_TAG_NAME_BASE_VALUE = 'baseValue';

    /**
     * @override
     */
    public function processXml($xmlString)
    {
        $xmlDocument = $this->getXmlIterator($xmlString);
        $this->processAllXmlElements($xmlDocument);

        return $xmlDocument->asXML();
    }

    /**
     * @override
     */
    protected function processXmlElement(SimpleXMLElement $xmlElement)
    {
        $this->handleAssessmentItemInvalidTitle($xmlElement);
        $this->handleBaseValue($xmlElement);
    }

    /**
     * Fixes invalid (empty or missing) "title" attribute on assessment item elements.
     *
     * If the "title" attribute is missing or set to an empty string,
     * then the value of the "identifier" attribute is used as the title.
     *
     * This method does nothing if the element is not an assessment item,
     * or it has a valid title.
     *
     * @param SimpleXMLElement $xmlElement
     */
    protected function handleAssessmentItemInvalidTitle(SimpleXMLElement $xmlElement)
    {
        if ($this->isXmlElementAssessmentItem($xmlElement)) {
            $nodeAttributes = $xmlElement->attributes();
            /** @var SimpleXMLIterator $identifierAttribute */
            $identifierAttribute = $nodeAttributes['identifier'];

            if (!isset($nodeAttributes['title'])) {
                $xmlElement->addAttribute('title', $identifierAttribute);
            } elseif (empty($nodeAttributes['title'])) {
                $nodeAttributes['title'] = (string) $identifierAttribute;
            }
        }
    }

    protected function handleBaseValue(SimpleXmlElement $xmlElement)
    {
        if ($this->isXmlElementBaseValue($xmlElement)) {
            if ((string) $xmlElement->attributes()['baseType'] === 'float') {
                // Normalize float value. Invalid values are treated as 0
                $xmlElement[0] = floatval(str_replace(',', '', $xmlElement[0]));
            }
        }
    }

    /**
     * Returns whether a given XML element is a QTI v2 assessment item.
     *
     * @param  SimpleXMLElement $xmlElement
     *
     * @return boolean - true if the element is an assessment item, false otherwise
     */
    private function isXmlElementAssessmentItem(SimpleXMLElement $xmlElement)
    {
        return $xmlElement->getName() === static::XML_TAG_NAME_ASSESSMENT_ITEM;
    }

    private function isXmlElementBaseValue(SimpleXMLElement $xmlElement)
    {
        return $xmlElement->getName() === static::XML_TAG_NAME_BASE_VALUE;
    }
}