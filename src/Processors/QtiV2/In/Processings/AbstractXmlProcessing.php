<?php

namespace LearnosityQti\Processors\QtiV2\In\Processings;

use \SimpleXMLElement;
use \SimpleXMLIterator;

/**
 * AbstractXmlProcessing represents a class of data processing jobs that
 * handle XML.
 */
abstract class AbstractXmlProcessing
{
    /**
     * Processes an XML document.
     *
     * @param  string $xmlString - the XML to process
     *
     * @return string - the processed XML document as a string
     */
    abstract public function processXml($xmlString);

    /**
     * Parses a given XML string and returns an XML element iterator for it.
     *
     * @param  string $xmlString - the XML to retrieve an iterator for
     *
     * @return \SimpleXMLIterator
     *
     * @throws \Exception - if the XML cannot be parsed
     */
    protected function getXmlIterator($xmlString)
    {
        return new SimpleXMLIterator($xmlString);
    }

    /**
     * Processes a single element from an XML document.
     *
     * NOTE: Subclasses may override this method if they need to process
     * XML at the element level. Default behavior is a no-op.
     *
     * @param SimpleXMLElement $xmlElement
     */
    protected function processXmlElement(SimpleXMLElement $xmlElement)
    {
        // Method stub. Subclasses may override this method where needed
    }

    /**
     * Recursively processes all elements in an XML document.
     *
     * The elements are processed in the order that they are read from
     * the document. This is determined by the iterator implementation.
     *
     * NOTE: Subclasses should override AbstractXmlProcessing::processXmlElement()
     * to define behavior for this method. The default is a no-op.
     *
     * @param  SimpleXMLIterator $xmlIterator - an iterator for an XML document
     *
     * @see \LearnosityQti\Processors\QtiV2\In\Processings\AbstractXmlProcessing::processXmlElement()
     */
    protected function processAllXmlElements(SimpleXMLIterator $xmlIterator)
    {
        // Process the top element in the iterator's current stack
        $this->processXmlElement($xmlIterator);

        // Look for siblings and children to process
        for ($xmlIterator->rewind(); $xmlIterator->valid(); $xmlIterator->next()) {
            if ($xmlIterator->hasChildren()) {
                $this->processAllXmlElements($xmlIterator->current());
            } else {
                $this->processXmlElement($xmlIterator->current());
            }
        }
    }
}
