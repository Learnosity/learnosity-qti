<?php

namespace LearnosityQti\Processors\QtiV2\In\Processings;

use \LearnosityQti\Processors\QtiV2\In\Processings\AbstractXmlProcessing;
use \LearnosityQti\Services\LogService;
use \qtism\common\utils\Format;
use \SimpleXMLElement;

class QtiV2AssessmentItemProcessing extends AbstractXmlProcessing
{
    const XML_TAG_NAME_ASSESSMENT_ITEM  = 'assessmentItem';
    const XML_TAG_NAME_BASE_VALUE       = 'baseValue';
    const XML_TAG_NAME_CORRECT_RESPONSE = 'correctResponse';
    const XML_TAG_NAME_GAP_TEXT         = 'gapText';

    const VALID_IDENTIFIER_PREFIX_DEFAULT = 'LRN-';

    private $elementsMarkedForRemoval = [];

    /**
     * @override
     */
    public function processXml($xmlString)
    {
        $xmlDocument = $this->getXmlIterator($xmlString);
        $this->processAllXmlElements($xmlDocument);

        // Process late removals that need to be performed after iteration
        if (!empty($this->elementsMarkedForRemoval)) {
            foreach ($this->elementsMarkedForRemoval as &$xmlElement) {
                unset($xmlElement[0]);
                $xmlElement = null;
            }
            $this->elementsMarkedForRemoval = array_values(array_filter($this->elementsMarkedForRemoval));
        }

        return $xmlDocument->asXML();
    }

    /**
     * @override
     */
    protected function processXmlElement(SimpleXMLElement $xmlElement)
    {
        $this->handleAssessmentItemInvalidTitle($xmlElement);
        $this->handleAssessmentItemInvalidIdentifier($xmlElement);
        $this->handleGapTextHtmlContent($xmlElement);
        $this->handleBaseValue($xmlElement);
        $this->handleInvalidCorrectResponse($xmlElement);
    }

    /**
     * Modifies invalid "identifier" attribute on assessment item elements
     * to be valid, if possible.
     *
     * The "identifier" attribute is specified as invalid when it begins with a
     * forbidden character, contains any forbidden characters, is zero-length
     * or is not set.
     *
     * Currently, this method only modifies the identifier if it has an
     * incorrect format.
     *
     * This mutates the identifier attribute in-place, which will affect
     * anything that reads it after this operation takes place.
     *
     * @param  SimpleXMLElement $xmlElement
     */
    protected function handleAssessmentItemInvalidIdentifier(SimpleXMLElement $xmlElement)
    {
        if ($this->isXmlElementAssessmentItem($xmlElement)) {
            $nodeAttributes = $xmlElement->attributes();

            if (!isset($nodeAttributes['identifier'])) {
                LogService::log('Assessment item preprocessing - <assessmentItem> missing identifier attribute');
                return;
            }

            $identifier = (string) $nodeAttributes['identifier'];
            if (!Format::isIdentifier($identifier, false)) {
                $newIdentifier = null;
                if (!empty($identifier)) {
                    $newIdentifier = static::VALID_IDENTIFIER_PREFIX_DEFAULT . $identifier;
                }

                if (Format::isIdentifier($newIdentifier, false)) {
                    $nodeAttributes['identifier'] = $newIdentifier;
                    LogService::log('Assessment item preprocessing - <assessmentItem> invalid identifier attribute; using modified identifier value');
                }
            }
        }
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
                LogService::log('Assessment item preprocessing - <assessmentItem> missing title; using identifier as the title attribute');
            } elseif (empty($nodeAttributes['title'])) {
                $nodeAttributes['title'] = (string) $identifierAttribute;
                LogService::log('Assessment item preprocessing - <assessmentItem> missing title; using identifier as the title attribute');
            }
        }
    }

    protected function handleBaseValue(SimpleXmlElement $xmlElement)
    {
        if ($this->isXmlElementBaseValue($xmlElement)) {
            if ((string) $xmlElement->attributes()['baseType'] === 'float') {
                // Normalize float value. Invalid values are treated as 0
                // TODO: Add a log entry for invalid values that will be coerced to zero
                $xmlElement[0] = floatval(str_replace(',', '', $xmlElement[0]));
            }
        }
    }

    protected function handleGapTextHtmlContent(SimpleXmlElement $xmlElement)
    {
        if ($this->isXmlElementGapText($xmlElement)) {
            $xmlElement[0] = trim(strip_tags($xmlElement->asXML()));
        }
    }

    protected function handleInvalidCorrectResponse(SimpleXmlElement $xmlElement)
    {
        if ($this->isXmlElementCorrectResponse($xmlElement)) {
            // Remove the element completely if it does not have at least 1 <value> child
            if (empty($xmlElement[0]->value)) {
                $this->markElementForRemoval($xmlElement);
                LogService::log('Assessment item preprocessing - Empty <correctResponse> found; removing <correctResponse>');
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

    private function isXmlElementCorrectResponse(SimpleXmlElement $xmlElement)
    {
        return $xmlElement->getName() === static::XML_TAG_NAME_CORRECT_RESPONSE;
    }

    private function isXmlElementGapText(SimpleXmlElement $xmlElement)
    {
        return $xmlElement->getName() === static::XML_TAG_NAME_GAP_TEXT;
    }

    private function markElementForRemoval(SimpleXmlElement $xmlElement)
    {
        $this->elementsMarkedForRemoval[] = $xmlElement;
    }
}
