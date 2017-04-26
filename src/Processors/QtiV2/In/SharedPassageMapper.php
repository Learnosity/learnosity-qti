<?php

namespace LearnosityQti\Processors\QtiV2\In;

use DOMDocument;
use DOMElement;
use LearnosityQti\Entities\Question;
use LearnosityQti\Entities\QuestionTypes\sharedpassage;
use LearnosityQti\Utils\UuidUtil;
use LearnosityQti\Utils\QtiMarshallerUtil;
use LearnosityQti\Utils\Xml\EntityUtil as XmlEntityUtil;
use qtism\data\content\RubricBlock;
use qtism\data\content\xhtml\Object;
use qtism\data\QtiComponentCollection;
use SplFileInfo;
use LearnosityQti\Services\LogService;
use LearnosityQti\Exceptions\MappingException;

class SharedPassageMapper
{
    const CONTENT_TYPE_HTML = 'text/html';
    const CONTENT_TYPE_XML  = 'application/xml';

    private $sourceDirectoryPath;

    public function __construct($sourceDirectoryPath = null)
    {
        $this->sourceDirectoryPath = $sourceDirectoryPath;
    }

    public function parse($xmlString)
    {
        return $this->parseXml($xmlString);
    }

    public function parseXml($xmlString)
    {
        $results = [
            'features' => [],
        ];

        $passageContent = $this->parsePassageContentFromXml($xmlString);

        $widget = $this->buildSharedPassageWithContent($passageContent);

        $results['features'][$widget->get_reference()] = $widget;

        return $results;
    }

    public function parseHtml($htmlString)
    {
        $results = [
            'features' => [],
        ];

        $passageContent = $this->parsePassageContentFromHtml($htmlString);

        $widget = $this->buildSharedPassageWithContent($passageContent);

        $results['features'][$widget->get_reference()] = $widget;

        return $results;
    }

    public function parseFile(SplFileInfo $file, $contentType = self::CONTENT_TYPE_XML)
    {
        $passageContentString = file_get_contents($file->getRealPath());
        switch ($contentType) {
            case static::CONTENT_TYPE_HTML:
                $result = $this->parseHtml($passageContentString);
                break;
            case static::CONTENT_TYPE_XML:
                # Falls through
            default:
                $result = $this->parseXml($passageContentString);
                break;
        }

        return $result;
    }

    public function parseWithRubricBlockComponent(RubricBlock $rubricBlock)
    {
        $result = [];

        // Extract the object element(s) (if any)
        /** @var QtiComponentCollection $objects */
        $objects = $rubricBlock->getComponentsByClassName('object', true);
        if ($objects->count()) {
            // TODO: Handle HTML content inside rubricBlock that wraps the object element(s)
            $result = $this->buildSharedPassagesFromObjects($objects);
        } else {
            // Fall back to using all the content in the <rubricBlock> verbatim
            $result = $this->parseXml(QtiMarshallerUtil::marshall($rubricBlock));
        }

        return $result;
    }

    protected function buildSharedPassagesFromObjects(QtiComponentCollection $objects)
    {
        $allowedObjectContentTypes = [
            static::CONTENT_TYPE_HTML,
            static::CONTENT_TYPE_XML,
        ];

        $result = [];

        if ($objects->count() > 1) {
            LogService::log('<rubricBlock use="context"> - multiple <object> elements found, will use the first only');
        }
        $objects->rewind();
        $contentType = $objects->current()->getType();
        if (in_array($contentType, $allowedObjectContentTypes)) {
            $contentRelativePath = $objects->current()->getData();
            $file = new SplFileInfo($this->sourceDirectoryPath.'/'.$contentRelativePath);
            if (!$file->isFile()) {
                throw new MappingException("Could not process <rubricBlock> - resource file at {$contentRelativePath} not found in directory: '{$this->sourceDirectoryPath}'");
            }

            $result = $this->parseFile($file, $contentType);
        }

        return $result;
    }

    protected function buildSharedPassageWithContent($passageContent)
    {
        return new Question('sharedpassage', UuidUtil::generate(), new sharedpassage('sharedpassage', $passageContent));
    }

    protected function parsePassageContentFromHtml($htmlString)
    {
        $htmlDom = new DOMDocument();
        $htmlDom->loadHTML($htmlString);

        /** @var \DOMNodeList $body */
        $body = $htmlDom->getElementsByTagName('body');
        if ($body->item(0)) {
            $oldHtmlDom = $htmlDom;

            // Parse to get only the HTML body content.
            $htmlDom = new DOMDocument();
            foreach ($body->item(0)->childNodes as $childNode) {
                $htmlDom->appendChild($htmlDom->importNode($childNode, true));
            }

            // Check for any stripped elements (e.g. <link> stylesheets) that needs to be logged.
            foreach ($oldHtmlDom->getElementsByTagName('link') as $linkElement) {
                /** @var DOMElement $linkElement */
                $rel = $linkElement->getAttribute('rel');
                $href = $linkElement->getAttribute('href');
                LogService::log("Could not import <link> element in passage content with rel: {$rel} href: {$href}");
            }
        }

        return $this->parsePassageContentFromDom($htmlDom);
    }

    protected function parsePassageContentFromXml($xmlString)
    {
        return $this->parsePassageContentFromDom($this->loadXmlAsHtmlDocument($xmlString));
    }

    private function loadXmlAsHtmlDocument($xmlString)
    {
        // Sanitize the XML for DOMDocument usage
        $xmlString = $this->sanitizeXml($xmlString);

        // HACK: Load as XML in one DOM and transfer it to another DOM as HTML for modification
        $xmlDom = new DOMDocument();
        $xmlDom->loadXML($xmlString);

        $htmlDom = new DOMDocument();
        $htmlDom->loadHTML('<body></body>', LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        $xmlDom = $htmlDom->importNode($xmlDom->documentElement, true);
        $htmlDom->replaceChild($xmlDom, $htmlDom->documentElement);

        return $htmlDom;
    }

    private function sanitizeXml($xml)
    {
        $dom = new \DOMDocument('1.0', 'UTF-8');

        // HACK: Pass the version and encoding to prevent libxml from decoding HTML entities (esp. &amp; which libxml borks at)
        $dom->loadHTML('<?xml version="1.0" encoding="UTF-8">'.$xml, LIBXML_HTML_NODEFDTD | LIBXML_HTML_NOIMPLIED);
        $xml = $dom->saveXML($dom->documentElement);

        // HACK: Handle the fact that XML can't handle named entities (and HTML5 has no DTD for it)
        $xml = XmlEntityUtil::convertNamedEntitiesToHexInString($xml);

        return $xml;
    }

    private function parsePassageContentFromDom(DOMDocument $htmlDom)
    {
        // Strip all <rubricBlock> elements (keep inner HTML)
        foreach ($htmlDom->getElementsByTagName('rubricBlock') as $rubricBlockElement) {
            /** @var DOMElement $rubricBlockElement */
            $replacementNode = $htmlDom->createDocumentFragment();
            while ($rubricBlockElement->childNodes->length > 0) {
                /** @var DOMNode $childNode */
                $replacementNode->appendChild($rubricBlockElement->childNodes->item(0));
            }
            $rubricBlockElement->parentNode->replaceChild($replacementNode, $rubricBlockElement);
        }

        // Strip all <apipAccessibility> elements
        foreach ($htmlDom->getElementsByTagName('apipAccessibility') as $apipElement) {
            /** @var DOMElement $apipElement */
            $apipElement->parentNode->removeChild($apipElement);
        }

        // Process all <object> elements
        $xpath = new \DOMXPath($htmlDom);
        foreach ($xpath->query('//object') as $objectElement) {
            $this->handleObjectElementInDocument($objectElement, $htmlDom);
        }

        // HACK: Saving exclusively from the documentElement breaks content
        // with no wrapper element/multiple root elements. So handle it differently
        // TODO: Check if this logic is even needed; we may not need to save from the documentElement anymore
        if ($htmlDom->documentElement->nextSibling) {
            LogService::log('SharedPassageMapper - found passage content with more than one root element; Assume that the content is correct');
            return $htmlDom->saveHTML();
        }

        return $htmlDom->saveHTML($htmlDom->documentElement);
    }

    private function handleObjectElementInDocument(DOMElement $objectElement, DOMDocument $context)
    {
        // If <object> has `image/*` MIME type:
        if (strpos((string)$objectElement->getAttribute('type'), 'image') !== false) {
            /** @var DOMElement $replacementElement */
            $replacementElement = $context->createElement('img');
            $replacementElement->setAttribute('src', $objectElement->getAttribute('data'));

            // TODO: support `alt` text (using the inner text content of the <object> element)
            if ($objectElement->hasAttribute('height')) {
                $replacementElement->setAttribute('height', $objectElement->getAttribute('height'));
            }
            if ($objectElement->hasAttribute('width')) {
                $replacementElement->setAttribute('width', $objectElement->getAttribute('width'));
            }
            $objectElement->parentNode->replaceChild($replacementElement, $objectElement);
        }
    }
}
