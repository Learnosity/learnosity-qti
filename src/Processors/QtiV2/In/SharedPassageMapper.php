<?php

namespace LearnosityQti\Processors\QtiV2\In;

use DOMDocument;
use DOMElement;
use DOMNode;
use DOMNodeList;
use DOMXPath;
use LearnosityQti\Entities\Question;
use LearnosityQti\Entities\QuestionTypes\sharedpassage;
use LearnosityQti\Exceptions\MappingException;
use LearnosityQti\Services\LogService;
use LearnosityQti\Utils\General\DOMHelper;
use LearnosityQti\Utils\QtiMarshallerUtil;
use LearnosityQti\Utils\UuidUtil;
use qtism\data\content\RubricBlock;
use qtism\data\QtiComponentCollection;
use SplFileInfo;

class SharedPassageMapper
{
    const CONTENT_TYPE_HTML = 'text/html';
    const CONTENT_TYPE_XML  = 'application/xml';

    protected $emptyAllowed = true;

    private $sourceDirectoryPath;

    public function __construct($sourceDirectoryPath = null)
    {
        $this->sourceDirectoryPath = $sourceDirectoryPath;
    }

    public function isEmptyAllowed()
    {
        return $this->emptyAllowed;
    }

    public function setEmptyAllowed($emptyAllowed)
    {
        $this->emptyAllowed = boolval($emptyAllowed);
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

        // Check for empty XML elements (no body)
        if (empty($xmlString)) {
            return $results;
        }

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
        $results = [];

        // Extract the object element(s) (if any)
        /** @var QtiComponentCollection $objects */
        $objects = $rubricBlock->getComponentsByClassName('object', true);
        if ($objects->count()) {
            // TODO: Handle HTML content inside rubricBlock that wraps the object element(s)
            $results = $this->buildSharedPassagesFromObjects($objects);
        } else {
            // Fall back to using all the content in the <rubricBlock> verbatim as a single passage
            $xml = QtiMarshallerUtil::marshall($rubricBlock);
            if (strlen(trim($xml)) > 0) {
                $dom          = DOMHelper::getDomForXml($xml);
                $innerContent = DOMHelper::getInnerXmlFragmentFromDom($dom);
                $results      = $this->parseXml($dom->saveXML($innerContent));
            } elseif ($this->isEmptyAllowed()) {
                $results      = $this->parseXml($xml);
            } else {
                throw new MappingException('No content found; cannot create sharedpassage (isEmptyAllowed=false)');
            }
        }

        if (empty($results['features'])) {
            throw new MappingException("Could not process <rubricBlock> passage");
        }

        return $results;
    }

    protected function buildSharedPassagesFromObjects(QtiComponentCollection $objects)
    {
        $allowedObjectContentTypes = [
            static::CONTENT_TYPE_HTML,
            static::CONTENT_TYPE_XML,
        ];

        $results = [
            'features' => [],
        ];

        $objects->rewind();
        // Handle processing multiple passage objects in order
        foreach ($objects as $object) {
            $contentType = $object->getType();
            if (in_array($contentType, $allowedObjectContentTypes)) {
                $contentRelativePath = $object->getData();
                $file = new SplFileInfo($this->sourceDirectoryPath.'/'.$contentRelativePath);
                if (!$file->isFile()) {
                    throw new MappingException("Could not process <rubricBlock> - resource file at {$contentRelativePath} not found in directory: '{$this->sourceDirectoryPath}'");
                }

                $fileResult = $this->parseFile($file, $contentType);

                // The `features` array is a map by feature reference; merge new result with existing ones
                $results['features'] = array_merge($results['features'], $fileResult['features']);
            }
        }

        return $results;
    }

    protected function buildSharedPassageWithContent($passageContent)
    {
        $passage = new sharedpassage('sharedpassage');
        $passage->set_content($passageContent);
        return new Question('sharedpassage', UuidUtil::generate(), $passage);
    }

    protected function parsePassageContentFromHtml($htmlString)
    {
        $htmlDom = new DOMDocument();
        $htmlDom->loadHTML($htmlString);

        /** @var DOMNodeList $body */
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
        $xmlString = DOMHelper::sanitizeXml($xmlString);

        // HACK: Load as XML in one DOM and transfer it to another DOM as HTML for modification
        $xmlDom = new DOMDocument();
        $isValid = $xmlDom->loadXML($xmlString);

        if (!$isValid) {
            throw new MappingException('Invalid XML; Failed to parse DOM for sharedpassage content');
        }

        $htmlDom = new DOMDocument();
        $htmlDom->loadHTML('<body></body>', LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        $xmlDom = $htmlDom->importNode($xmlDom->documentElement, true);
        $htmlDom->replaceChild($xmlDom, $htmlDom->documentElement);

        return $htmlDom;
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
        $xpath = new DOMXPath($htmlDom);
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
