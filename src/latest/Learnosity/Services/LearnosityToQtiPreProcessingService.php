<?php

namespace Learnosity\Services;

use Learnosity\Exceptions\MappingException;
use Learnosity\Utils\MimeUtil;
use Learnosity\Utils\QtiMarshallerUtil;
use Learnosity\Utils\SimpleHtmlDom\SimpleHtmlDom;
use Learnosity\Utils\StringUtil;
use qtism\data\content\xhtml\Object;

class LearnosityToQtiPreProcessingService
{
    private $questions = [];
    private $features = [];

    public function process(array $widgets, array $item = null)
    {
        // Separate question and feature by type
        foreach ($widgets as $widget) {
            $reference = $widget['reference'];
            if ($widget['widget_type'] === 'response') {
                $this->questions[$reference] = $widget;
            } else if ($widget['widget_type'] === 'feature') {
                $this->features[$reference] = $widget;
            }
        }

        // We only want to process the questions and return the questions
        $processedQuestions = $this->processJson(array_values($this->questions));
        $processItem = empty($item) ? null : $this->processJson($item);
        return [$processedQuestions, $processItem];
    }

    private function processJson(array $json)
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
                $node->outertext = $this->getFeatureReplacementString($node);
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
        // Process regular question feature
        } else {
            $featureReference = $this->getFeatureReferenceFromClassName($node->attr['class']);
            $feature = $this->features[$featureReference];
            $src = $feature['data']['src'];
            $type = $feature['data']['type'];
        }

        if ($type === 'audioplayer' || $type === 'audioplayer') {
            // Replace <span..> with <object..>
            return QtiMarshallerUtil::marshallValidQti(new Object($src, MimeUtil::guessMimeType(basename($src))));
        } else {
            throw new MappingException($type . ' not supported');
        }
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
