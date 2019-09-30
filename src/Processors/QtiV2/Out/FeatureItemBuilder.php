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
        $this->doc = new DOMDocument();
        $html = $this->doc->appendChild($this->doc->createElement('html'));
        $head = $html->appendChild($this->doc->createElement('head'));
        $meta = array(
            array('charset' => 'utf-8'),
        );
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
        if(isset($feature['data']['heading'])) {
            $body->appendChild($this->doc->createElement('h3', $feature['data']['heading']));
        }
        $body->appendChild($this->doc->createElement('p', $feature['data']['content']));
        $html->appendChild($body);
        return html_entity_decode($this->doctype . $this->doc->saveHTML());
    }
}
