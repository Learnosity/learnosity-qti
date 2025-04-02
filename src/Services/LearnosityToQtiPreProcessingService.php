<?php

namespace LearnosityQti\Services;

use LearnosityQti\Exceptions\MappingException;
use LearnosityQti\Processors\QtiV2\Out\ContentCollectionBuilder;
use LearnosityQti\Services\LogService;
use LearnosityQti\Utils\MimeUtil;
use LearnosityQti\Utils\QtiMarshallerUtil;
use LearnosityQti\Utils\SimpleHtmlDom\SimpleHtmlDom;
use LearnosityQti\Utils\StringUtil;
use qtism\data\content\FlowCollection;
use qtism\data\content\xhtml\ObjectElement;
use qtism\data\content\xhtml\text\Div;
use LearnosityQti\Processors\QtiV2\Out\Constants as LearnosityExportConstant;

class LearnosityToQtiPreProcessingService
{
    private $widgets = [];
    private $inputPath = '';
    private $widgetType = '';

    public function __construct(array $widgets = [])
    {
        $this->widgets = array_column($widgets, null, 'reference');
    }

    public function processJson(array $json, $inputPath = '')
    {
        $this->widgetType = $json['data']['type'];

        // The source input path to the files we are converting
        if (!empty($inputPath)) {
            $this->inputPath = $inputPath;
        }

        $this->recursiveArrayWalk($json, function (&$key, &$item, $parentKey) {
            $propertiesExtraProcessing = ['stimulus', 'label', 'distractor_rationale', 'template'];
            if (is_string($item)) {
                $item = $this->processHtml($item, $key);

                if (in_array($key, $propertiesExtraProcessing)) {
                    $item = $this->processHtmlPostProcessing($item, $key, $this->widgetType);
                }

                // Replace all &nbsp; entities with &#160; as the former are not allowed in XML
                $item = str_replace('&nbsp;', '&#160;', $item);
            }

            if ($key === 'content') {
                $item = $this->processContentPostProcessing($item);
                // Replace all &nbsp; entities with &#160; as the former are not allowed in XML
                $item = str_replace('&nbsp;', '&#160;', $item);
            }

            if ($key === 'list') {
                foreach ($item as $i => $listItem) {
                    $item[$i] = $this->processHtmlPostProcessing($listItem, 'list', $this->widgetType);
                }
            }
        });

        $json = $this->processWidget($this->widgetType, $json);

        return $json;
    }

    private function processHtml($content, $key)
    {
        if ($key === 'template') {
            // Look for `template` attributes and make sure they're wrapped in a block element as QTI expects
            if (substr($content, 0, 3) !== '<p>' && substr($content, 0, 5) !== '<span' && !preg_match('/<table\b[^>]*>/i', $content)) {
                $content = '<span>' . $content . '</span>';
            }

            // Ensure {{response}} containers are wrapped in a valid flow element (if they aren't already)
            $content = preg_replace('/(<td[^>]*>)(\s*{{response}}\s*)(<\/td>)/', '$1<span>$2</span>$3', $content);
        }

        // Fix for <img src=...> tags that are missing quotes around the src attribute
        $content = preg_replace('/<img\s+src=([^"\'\s>]+)(\s|>)/i', '<img src="$1"$2', $content);

        $html = new SimpleHtmlDom();
        $html->load($content);

        // Find all <center> elements and remove them from the deepest first
        $centerTags = $html->find('center');
        for ($i = count($centerTags) - 1; $i >= 0; $i--) {
            $centerTags[$i]->outertext = $centerTags[$i]->innertext; // Replace <center> with its content
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

    /**
     * Due to problems with SimpleHtmlDom, we need to use DOMDocument to process the HTML content
     * to do things like injecting <tbody> into <table> elements, closing any unclosed tags.
     * We also try to escape invalid XML characters in text nodes.
     */
    private function processHtmlPostProcessing($content, $key, $type)
    {
        if (empty($content)) return $content;

        $map = [0x80, 0x10FFFF, 0, 0xFFFF]; // UTF-8 character range
        $content = mb_encode_numericentity($content, $map, 'UTF-8');

        $doc = new \DOMDocument('1.0', 'UTF-8');

        // Replace `<` and `>` characters that are not part of tags
        $content = preg_replace_callback(
            '/<(?!(?:\/?[a-zA-Z0-9]+(?:\s|\/?>)))|>(?!(?:[^<]*<\/[a-zA-Z]+>|[^<]*\/?>))/',
            function ($matches) {
                return ($matches[0] === '<') ? '__LT__' : '>'; // Do NOT replace `>`
            },
            $content
        );

        // Wrap the HTML in a minimal valid structure (fixes issues with `loadHTML`)
        $htmlWrapped = "<!DOCTYPE html><html><body><div>$content</div></body></html>";

        // Suppress warnings for malformed HTML
        libxml_use_internal_errors(true);

        // Load the wrapped HTML
        $doc->loadHTML($htmlWrapped, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);

        // Clear any parsing errors
        libxml_clear_errors();

        /***************** Start processing the HTML ****************/

        // Preserve MathML by wrapping it in CDATA
        foreach ($doc->getElementsByTagName('math') as $mathTag) {
            $cdata = $doc->createCDATASection($doc->saveHTML($mathTag));
            $mathTag->parentNode->replaceChild($cdata, $mathTag);
        }

        // Process tables inside the div
        foreach ($doc->getElementsByTagName('table') as $table) {
            // Ensure the table has a <tbody>
            if (!$table->getElementsByTagName('tbody')->length) {
                $tbody = $doc->createElement('tbody');

                // Move all <tr> elements into <tbody>
                $trs = [];
                foreach ($table->childNodes as $child) {
                    if ($child->nodeName === 'tr') {
                        $trs[] = $child;
                    }
                }

                foreach ($trs as $tr) {
                    $tbody->appendChild($tr);
                }

                $table->appendChild($tbody);
            }
        }

        // Replace <b> with <strong>
        foreach ($doc->getElementsByTagName('b') as $bTag) {
            $strongTag = $doc->createElement('strong');

            // Copy all child nodes from <b> to <strong> to preserve structure
            while ($bTag->childNodes->length > 0) {
                $strongTag->appendChild($bTag->childNodes->item(0));
            }

            // Replace <b> with <strong>, keeping math content intact
            $bTag->parentNode->replaceChild($strongTag, $bTag);
        }

        // Replace <i> with <em>
        foreach ($doc->getElementsByTagName('i') as $iTag) {
            $emTag = $doc->createElement('em');

            // Copy all child nodes from <b> to <strong> to preserve structure
            while ($iTag->childNodes->length > 0) {
                $emTag->appendChild($iTag->childNodes->item(0));
            }

            // Replace <b> with <strong>, keeping math content intact
            $iTag->parentNode->replaceChild($emTag, $iTag);
        }

        // Replace <u> with <span style="text-decoration: underline;">
        $uTags = iterator_to_array($doc->getElementsByTagName('u'));
        foreach ($uTags as $uTag) {
            $spanTag = $doc->createElement('span', $uTag->textContent);
            $spanTag->setAttribute('style', 'text-decoration: underline;');
            $uTag->parentNode->replaceChild($spanTag, $uTag);
        }

        // Remove all <meta> elements
        $metaTags = $doc->getElementsByTagName('meta');
        // Loop backwards to safely remove elements
        for ($i = $metaTags->length - 1; $i >= 0; $i--) {
            $metaTag = $metaTags->item($i);
            $metaTag->parentNode->removeChild($metaTag);
        }

        // Remove all <link> elements
        $linkTags = $doc->getElementsByTagName('link');
        // Loop backwards to safely remove elements
        for ($i = $linkTags->length - 1; $i >= 0; $i--) {
            $linkTag = $linkTags->item($i);
            $linkTag->parentNode->removeChild($linkTag);
        }

        // Find any <img> attributes that have a `px` suffix
        foreach ($doc->getElementsByTagName('img') as $imgTag) {
            // Remove "px" from width and height attributes
            if ($imgTag->hasAttribute('width')) {
                $imgTag->setAttribute('width', preg_replace('/px$/', '', $imgTag->getAttribute('width')));
            }
            if ($imgTag->hasAttribute('height')) {
                $imgTag->setAttribute('height', preg_replace('/px$/', '', $imgTag->getAttribute('height')));
            }
        }

        // Remove empty paragraphs
        $paragraphs = $doc->getElementsByTagName('p');
        // Loop backwards to avoid skipping elements after removal
        for ($i = $paragraphs->length - 1; $i >= 0; $i--) {
            $pTag = $paragraphs->item($i);

            // Remove empty <p> tags but keep those with inline elements
            if (
                trim($pTag->textContent, "\u{00A0} \t\n\r\0\x0B") === '' && // No visible text
                !$pTag->getElementsByTagName('*')->length // No child elements (like <span>, <img>, <br>)
            ) {
                $pTag->parentNode->removeChild($pTag);
            }
        }

        // Remove <font> tags
        $fonts = $doc->getElementsByTagName('font');
        // Loop backwards to avoid skipping elements after removal
        for ($i = $fonts->length - 1; $i >= 0; $i--) {
            $fontTag = $fonts->item($i);

            // Move all child nodes of <font> to its parent before removing it
            while ($fontTag->childNodes->length > 0) {
                $fontTag->parentNode->insertBefore($fontTag->childNodes->item(0), $fontTag);
            }

            // Remove the <font> tag itself
            $fontTag->parentNode->removeChild($fontTag);
        }

        // Look for elements with an `id` starting with a number and
        // prepend an underscore
        foreach ($doc->getElementsByTagName('*') as $element) {
            if ($element->hasAttribute('id')) {
                $idValue = $element->getAttribute('id');

                // If the ID starts with a number, prepend an underscore (_)
                if (preg_match('/^\d/', $idValue)) {
                    $newId = '_' . $idValue;
                    $element->setAttribute('id', $newId);
                }
            }
        }

        // Find any orphaned <li> elements and wrap them in a <ul>
        $xpath = new \DOMXPath($doc);
        $orphanedLis = $xpath->query('//li[not(parent::ul) and not(parent::ol)]');
        if ($orphanedLis->length > 0) {
            $ul = $doc->createElement('ul');
            foreach ($orphanedLis as $li) {
                if (!$ul->parentNode) {
                    $li->parentNode->insertBefore($ul, $li);
                }
                $ul->appendChild($li);
            }
        }

        // Find any <blockquote> elements and replace with a <div> as the lib doesn't support it
        // We put the <blockquote> back after XML is generated.
        $blockquoteNodes = $doc->getElementsByTagName('blockquote');
        $blockquotes = iterator_to_array($blockquoteNodes);
        foreach ($blockquotes as $blockquote) {
            $div = $doc->createElement('div');
            while ($blockquote->hasChildNodes()) {
                $div->appendChild($blockquote->firstChild);
            }
            $div->setAttribute('class', 'lrn-replace-blockquote');
            $blockquote->parentNode->replaceChild($div, $blockquote);
        }

        /***************** End processing the HTML ****************/

        // Find the <div> wrapper
        $wrapper = $doc->getElementsByTagName('div')->item(0);

        // Extract only the modified content inside the <div>
        $processedHtml = '';
        foreach ($wrapper->childNodes as $node) {
            $processedHtml .= $doc->saveHTML($node);
        }

        // Ensure all elements are properly closed
        $processedHtml = tidy_repair_string($processedHtml, [
            'output-xhtml' => true,
            'show-body-only' => true,
            'wrap' => 0
        ]);

        $processedHtml = str_replace(['__LT__', '__GT__'], ['&lt;', '&gt;'], $processedHtml);
        return $processedHtml;
    }

    /**
     * Do any necessary process on the API generated `content` string.
     */
    private function processContentPostProcessing($content)
    {
        if (empty($content)) return $content;

        $map = [0x80, 0x10FFFF, 0, 0xFFFF]; // UTF-8 character range
        $content = mb_encode_numericentity($content, $map, 'UTF-8');

        $doc = new \DOMDocument('1.0', 'UTF-8');
        $htmlWrapped = "<!DOCTYPE html><html><body><div>$content</div></body></html>";
        $doc->loadHTML($htmlWrapped, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);

        // Find the first <div class="tabs">
        $xpath = new \DOMXPath($doc);
        $tabsNode = $xpath->query('//div[contains(@class, "tabs")]')->item(0);
        if ($tabsNode) {
            $widgets = [];
            $featureNodes = $xpath->query('.//div[@class="learnosity-feature"] | .//span[@class="learnosity-feature"]', $tabsNode);

            foreach ($featureNodes as $featureElement) {
                $widgets[] = $featureElement;
            }

            $parent = $tabsNode->parentNode;
            $parent->removeChild($tabsNode);

            // Append passage elements inside the outer div
            foreach ($widgets as $widget) {
                $parent->appendChild($widget);
            }
        }

        // Find the <div> wrapper
        $wrapper = $doc->getElementsByTagName('div')->item(0);

        // Extract only the modified content inside the <div>
        $processedHtml = '';
        foreach ($wrapper->childNodes as $node) {
            $processedHtml .= $doc->saveHTML($node);
        }

        // Ensure all elements are properly closed
        $processedHtml = tidy_repair_string($processedHtml, [
            'indent'                => true,
            'output-xhtml'          => true,
            'drop-empty-elements'   => false, // Prevents removing empty <span> (aka Learnosity widgets)
            'show-body-only'        => true,
            'wrap'                  => 0
        ]);

        return $processedHtml;
    }

    private function processWidget($type, $json)
    {
        switch ($type) {
            case 'clozeassociation':
            case 'imageclozeassociationV2':
                if (array_key_exists('possible_response_groups', $json['data'])) {
                    $possibleResponses = [];
                    foreach ($json['data']['possible_response_groups'] as $group) {
                        $possibleResponses = array_merge($possibleResponses, $group['responses']);
                    }
                    $json['data']['possible_responses'] = $possibleResponses;
                    unset($json['data']['possible_response_groups']);
                }
                break;

            default:
                break;
        }

        return $json;
    }

    private function getFeatureReplacementString($node)
    {
        // Process inline feature
        if (isset($node->attr['data-type']) && isset($node->attr['data-src'])) {
            $src = trim($node->attr['data-src']);
            $type = trim($node->attr['data-type']);
            if ($type === 'audioplayer' || $type === 'videoplayer') {
                $src = $this->getQtiMediaSrcFromLearnositySrc($src);
                return QtiMarshallerUtil::marshallValidQti(new ObjectElement($src, MimeUtil::guessMimeType(basename($src))));
            }
        // Process regular question feature
        } else {
            $nodeClassAttribute = $node->attr['class'];
            $featureReference = $this->getFeatureReferenceFromClassName($nodeClassAttribute);
            if (isset($this->widgets[$featureReference])) {
                $feature = $this->widgets[$featureReference];
                $type = isset($feature['data']['type']) ? $feature['data']['type'] : '';
                $src = isset($feature['data']['src']) ? $feature['data']['src'] : '';
            } else {
                $feature = $this->widgets;
                $type = isset($feature[1]['type']) ? $feature[1]['type'] : '';
                $src = isset($feature[1]['src']) ? $feature[1]['src'] : '';
            }
            if ($type === 'audioplayer' || $type === 'videoplayer') {
                return;
            } elseif ($type === 'sharedpassage') {
                $flowCollection = new FlowCollection();
                $object = new ObjectElement(LearnosityExportConstant::SHARED_PASSAGE_FOLDER_NAME . '/' . LearnosityExportConstant::PASSAGE_ID_PREFIX . $featureReference . '.html', 'text/html');
                $object->setLabel($featureReference);
                // $div = $this->createDivForSharedPassage();
                // $flowCollection->attach($object);
                // $div->setContent($flowCollection);
                return QtiMarshallerUtil::marshallValidQti($object);
            } else {
                LogService::log($type . 'feature not supported');
                throw new MappingException($type . 'feature not supported');
            }
        }
        LogService::log($type . ' not supported');
        throw new MappingException($type . ' not supported');
    }

    private function createDivForSharedPassage()
    {
        $div = new Div();
        $div->setClass(LearnosityExportConstant::SHARED_PASSAGE_DIV_CLASS);
        return $div;
    }

    private function getFeatureReferenceFromClassName($classname)
    {
        // Parse classname, ie `learnosity-feature feature-DEMOFEATURE123`
        // Then, return `DEMOFEATURE123`
        $parts = preg_split('/\s+/', $classname);
        foreach ($parts as $part) {
            if (StringUtil::startsWith(strtolower($part), 'feature-')) {
                return str_replace('feature-', '', $parts[1]);
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
                $href = LearnosityExportConstant::DIRNAME_VIDEO . '/' . $fileName;
            } elseif ($mediaFormat == 'audio') {
                $href = LearnosityExportConstant::DIRNAME_AUDIO . '/' . $fileName;
            } elseif ($mediaFormat == 'image') {
                $href = LearnosityExportConstant::DIRNAME_IMAGES . '/' . $fileName;
            } else {
                $href = $src;
            }
        }
        return $href;
    }

    private function recursiveArrayWalk(array &$array, callable $callback, $parentKey = null) {
        foreach ($array as $key => &$value) {
            // Call the callback function with key, value, and parent key
            $callback($key, $value, $parentKey);

            // If the value is an array, recurse deeper
            if (is_array($value)) {
                $this->recursiveArrayWalk($value, $callback, $key);
            }
        }
    }
}
