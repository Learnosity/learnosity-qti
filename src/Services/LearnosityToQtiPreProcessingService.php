<?php

namespace LearnosityQti\Services;

use LearnosityQti\Exceptions\MappingException;
use LearnosityQti\Processors\QtiV2\Out\ContentCollectionBuilder;
use LearnosityQti\Utils\MimeUtil;
use LearnosityQti\Utils\QtiMarshallerUtil;
use LearnosityQti\Utils\SimpleHtmlDom\SimpleHtmlDom;
use LearnosityQti\Utils\StringUtil;
use qtism\data\content\xhtml\ObjectElement;
use LearnosityQti\Processors\QtiV2\Out\Constants as LearnosityExportConstant;

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
            if (!strpos($node->outertext, '/>')) {
                $node->outertext = rtrim($node->outertext, '>') . '/>';
            }
        }

        foreach ($html->find('img') as &$node) {
            $src = $this->getQtiMediaSrcFromLearnositySrc($node->attr['src']);
            $node->outertext = str_replace($node->attr['src'], $src, $node->outertext);
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
            if ($type === 'audioplayer' || $type === 'videoplayer') {
                $src = $this->getSourceBasedOnMediaFormat($src);
                return QtiMarshallerUtil::marshallValidQti(new ObjectElement($src, MimeUtil::guessMimeType(basename($src))));
            }
        // Process regular question feature
        } else {
            $nodeClassAttribute = $node->attr['class'];
            $featureReference = $this->getFeatureReferenceFromClassName($nodeClassAttribute);
            $feature = $this->widgets[$featureReference];
            $type = $feature['data']['type'];

            if ($type === 'audioplayer' || $type === 'videoplayer') {
                $src = $feature['data']['src'];
                $object = new ObjectElement($src, MimeUtil::guessMimeType(basename($src)));
                $object->setLabel($featureReference);
                return QtiMarshallerUtil::marshallValidQti($object);

            } else if ($type === 'sharedpassage') {
                $content = $feature['data']['content'];
                $object = new ObjectElement('', 'text/html');
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

    /**
     * This method take the original media source and return the desired media path
     * for an item based on their media type.
     *
     * @param type $src source of the desired media
     * @return string media href
     */
    private function getQtiMediaSrcFromLearnositySrc($src)
    {
        $fileName = substr($src, strlen(LearnosityExportConstant::DIRPATH_ASSETS));
        $mimeType = MimeUtil::guessMimeType($fileName);
        $mediaFormatArray = explode('/', $mimeType);
        $href = '';
        if (is_array($mediaFormatArray) && !empty($mediaFormatArray[0])) {
            $mediaFormat = $mediaFormatArray[0];
            if ($mediaFormat == 'video') {
                $href = '../' . LearnosityExportConstant::DIRNAME_VIDEO . '/' . $fileName;
            } elseif ($mediaFormat == 'audio') {
                $href = '../' . LearnosityExportConstant::DIRNAME_AUDIO . '/' . $fileName;
            } elseif ($mediaFormat == 'image') {
                $href = '../' . LearnosityExportConstant::DIRNAME_IMAGES . '/' . $fileName;
            }
        }
        return $href;
    }
}
