<?php

namespace Learnosity\Services;

use Learnosity\Utils\SimpleHtmlDom\SimpleHtmlDom;

class LearnosityToQtiPreProcessingService
{
    public function processJson(array $json)
    {
        array_walk_recursive($json, function (&$item, $key) {
            if (is_string($item)) {
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
        return $html->save();
    }
}
