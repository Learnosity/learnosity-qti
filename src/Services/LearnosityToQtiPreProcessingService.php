<?php

namespace LearnosityQti\Services;

use LearnosityQti\Exceptions\MappingException;
use LearnosityQti\Processors\QtiV2\Out\ContentCollectionBuilder;
use LearnosityQti\Utils\MimeUtil;
use LearnosityQti\Utils\QtiMarshallerUtil;
use LearnosityQti\Utils\SimpleHtmlDom\SimpleHtmlDom;
use LearnosityQti\Utils\StringUtil;
use qtism\data\content\xhtml\ObjectElement;

class LearnosityToQtiPreProcessingService
{
    private $widgets = [];

    public function __construct(array $widgets = [])
    {
        $this->widgets = array_column($widgets, null, 'reference');
    }

    public function processJson(array $json)
    {
        array_walk_recursive($json, function (&$item, $key) {
            if (is_string($item)) {
                // Replace nbsp with '&#160;'
                $item = str_replace('&nbsp;', '&#160;', $item);
                $item = $this->processHtml($item);
            }
        });
        return $json;
    }

    private function processHtml($content)
    {
        $html = new SimpleHtmlDom();
        $html->load($content);

        // Replace <br> with <br />, <img ....> with <img />, etc
        /** @var array $selfClosingTags ie. `img, br, input, meta, link, hr, base, embed, spacer` */
        $selfClosingTags = implode(array_keys($html->getSelfClosingTags()), ', ');
        foreach ($html->find($selfClosingTags) as &$node) {
            $node->outertext = rtrim($node->outertext, '>') . '/>';
        }

        // Replace these audioplayer and videoplayer feature with <object> nodes
        foreach ($html->find('span.learnosity-feature') as &$node) {
            try {
                // Replace <span..> with <object..>
                $replacement = $this->getFeatureReplacementString($node);
                $node->outertext = $replacement;
            } catch (MappingException $e) {
                LogService::log($e->getMessage() . '. Ignoring mapping feature ' . $node->outertext . '`');
            }
        }

        return $html->save();
    }

    private function getFeatureReplacementString($node)
    {
        // Process inline feature
        if (isset($node->attr['data-type']) && isset($node->attr['data-src'])) {
            $src = trim($node->attr['data-src']);
            $type = trim($node->attr['data-type']);
            if ($type === 'audioplayer' || $type === 'audioplayer') {
                return QtiMarshallerUtil::marshallValidQti(new Object($src, MimeUtil::guessMimeType(basename($src))));
            }
        // Process regular question feature
        } else {
            $nodeClassAttribute = $node->attr['class'];
            $featureReference = $this->getFeatureReferenceFromClassName($nodeClassAttribute);
            $feature = $this->widgets[$featureReference];
            $type = $feature['data']['type'];

            if ($type === 'audioplayer' || $type === 'audioplayer') {
                $src = $feature['data']['src'];
                $object = new Object($src, MimeUtil::guessMimeType(basename($src)));
                $object->setLabel($featureReference);
                return QtiMarshallerUtil::marshallValidQti($object);

            } else if ($type === 'sharedpassage') {
                $content = $feature['data']['content'];
                $object = new Object('', 'text/html');
                $object->setContent(ContentCollectionBuilder::buildObjectFlowCollectionContent(QtiMarshallerUtil::unmarshallElement($content)));
                $object->setLabel($featureReference);
                return QtiMarshallerUtil::marshallValidQti($object);
            }
        }
        throw new MappingException($type . ' not supported');
    }

    private function getFeatureReferenceFromClassName($classname)
    {
        // Parse classname, ie `learnosity-feature feature-DEMOFEATURE123`
        // Then, return `DEMOFEATURE123`
        $parts = preg_split('/\s+/', $classname);
        foreach ($parts as $part) {
            if (StringUtil::startsWith(strtolower($part), 'feature-')) {
                return explode('-', $part)[1];
            }
        }
        // TODO: throw exception
        return null;
    }
}
