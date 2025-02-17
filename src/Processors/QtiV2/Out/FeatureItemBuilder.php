<?php
namespace LearnosityQti\Processors\QtiV2\Out;

use DOMDocument;

class FeatureItemBuilder
{

    /**
     * @var FeatureBodyBuilder
     */
    private $doc;
    private $doctype = '<!DOCTYPE html>';

    public function __construct()
    {
        $this->doc = new DOMDocument('1.0', 'UTF-8');

        $html = $this->doc->appendChild($this->doc->createElement('html'));
        $head = $html->appendChild($this->doc->createElement('head'));
        $meta = [['charset' => 'utf-8']];

        foreach ($meta as $attributes) {
            $node = $head->appendChild($this->doc->createElement('meta'));
            foreach ($attributes as $key => $value) {
                $node->setAttribute($key, $value);
            }
        }
        $this->doc->formatOutput = true;
    }

    public function build(array $feature)
    {
        $html = $this->doc->getElementsByTagName('html')->item(0);
        $body = $html->appendChild($this->doc->createElement('body'));

        $content = trim($feature['data']['content']);

        $content = mb_convert_encoding($content, 'UTF-8', 'UTF-8'); // Ensures UTF-8 correctness

        $content = preg_replace('/\xC2\xA0/', ' ', $content); // Replaces non-breaking spaces with normal spaces
        // Decode all other HTML entities (but keep numeric ones)
        $content = preg_replace_callback('/&#?[a-zA-Z0-9]+;/', function ($match) {
            return ($match[0] === '&#160;') ? '&#160;' : html_entity_decode($match[0], ENT_QUOTES | ENT_HTML5, 'UTF-8');
        }, $content);

        $map = [0x80, 0x10FFFF, 0, 0xFFFF]; // UTF-8 character range
        $heading = isset($feature['data']['heading']) ? mb_encode_numericentity($feature['data']['heading'], $map, 'UTF-8') : '';
        if (!empty($heading)) {
            $h3 = $this->doc->createElement('h3');
            $h3->appendChild($this->doc->createTextNode($heading));
            $body->appendChild($h3);
        }

        if (!empty($content)) {
            // Check if content already starts with <p>
            if (!preg_match('/^\s*<p|div[^>]*>/', $content)) {
                $p = $this->doc->createElement('p');
                $fragment = $this->doc->createDocumentFragment();
                // Only append if appendXML() succeeds (valid HTML)
                if ($fragment->appendXML($content)) {
                    $p->appendChild($fragment);
                } else {
                    // If content is plain text or invalid HTML, use createTextNode()
                    $p->appendChild($this->doc->createTextNode($content));
                }
                // Append <p> to the body
                $body->appendChild($p);
            } else {
                // Content already has <p> or <div>, insert as a raw fragment
                $fragment = $this->doc->createDocumentFragment();

                $body->appendChild($this->doc->createTextNode($content));
                // Only append if appendXML() succeeds
                // if ($fragment->appendXML($content)) {
                //     $body->appendChild($fragment);
                // } else {
                //     // If content is plain text, use createTextNode()
                //     $body->appendChild($this->doc->createTextNode($content));
                // }
            }
        }

        $finalOutput = $this->doc->saveXML();
        $finalOutput = preg_replace_callback('/&#?[a-zA-Z0-9]+;/', function ($match) {
            return ($match[0] === '&#160;') ? '&#160;' : html_entity_decode($match[0], ENT_QUOTES | ENT_HTML5, 'UTF-8');
        }, $finalOutput);
        return $this->doctype . $finalOutput;
    }
}
