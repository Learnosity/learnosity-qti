<?php

namespace LearnosityQti\Processors\QtiV2\Out;

use DOMDocument;
use DOMXpath;
use LearnosityQti\Services\ConvertToQtiService;
use LearnosityQti\Services\LogService;
use LearnosityQti\Utils\QtiMarshallerUtil;
use LearnosityQti\Utils\SimpleHtmlDom\SimpleHtmlDom;
use LearnosityQti\Utils\StringUtil;
use qtism\data\content\BlockCollection;
use qtism\data\content\FlowCollection;
use qtism\data\content\ItemBody;
use qtism\data\content\xhtml\text\Div;
use qtism\data\content\xhtml\text\Span;
use qtism\data\QtiComponentCollection;

class ItemBodyBuilder
{
    public function buildItemBody(array $interactions, $content = '', $questionType)
    {
        // Try to build the <itemBody> according to items` content if exists
        if (empty($content)) {
            return $this->buildItemBodySimple($interactions);
        }
        try {
            return $this->buildItemBodyWithItemContent($interactions, $content, $questionType);
            // If anything fails, <itemBody> can't be mapped due to whatever reasons
            // Probably simply due to its being wrapped in a tag which only accept inline content
            // Simply build it without considering items` content and put the content on the top
        } catch (\Exception $e) {
            $itemBody = $this->buildItemBodySimple($interactions);
            $itemBodyContent = new BlockCollection();

            // Build the div bundle that contains all the item`s content
            // minus those questions and features `span`
            $html = new SimpleHtmlDom();
            $html->load($content);
            foreach ($html->find('span.learnosity-response') as &$span) {
                $span->outertext = '';
            }
            $div = new Div();
            $contentCollection = QtiMarshallerUtil::unmarshallElement($html->save());
            $div->setContent(ContentCollectionBuilder::buildFlowCollectionContent($contentCollection));

            $itemBodyContent->attach($div);
            $itemBodyContent->merge($itemBody->getComponents());
            $itemBody->setContent($itemBodyContent);
            LogService::log(
                'Interactions are failed to be mapped with `item` content: ' . $e->getMessage()
                . '. Thus, interactions are separated from its actual `item` content and appended in the bottom'
            );
            return $itemBody;
        }
    }

    private function buildItemBodyWithItemContent(array $interactions, $content, $questionType)
    {
        // Map <itemBody>
        // TODO: Wrap these `content` stuff in a div
        // TODO: to avoid QtiComponentIterator bug ignoring 2nd element with empty content
        $learnosityService = ConvertToQtiService::getInstance();
        $format = $learnosityService->getFormat();
        $content = $this->removeUnusedSpanFromContent($interactions, $content);
		if ($format == 'canvas') {
            $content = strip_tags($content, "<span><object>");
		}

        $contentCollection = QtiMarshallerUtil::unmarshallElement($content);
        $wrapperCollection = new FlowCollection();
        foreach ($contentCollection as $component) {
            $wrapperCollection->attach($component);
        }
        $divWrapper = new Div();
        $divWrapper->setContent($wrapperCollection);

        // Iterate through these elements and try to replace every single question `span` with its interaction equivalent
        $iterator = $divWrapper->getIterator();
        foreach ($iterator as $component) {
            if ($component instanceof Span && StringUtil::contains($component->getClass(), 'learnosity-feature')) {
                $currentContainer = $iterator->getCurrentContainer();
                $featureReference = trim(str_replace('learnosity-feature', '', $component->getClass()));
                $featureReference = trim(str_replace('feature-', '', $featureReference));

                // Build the media interaction
                $interaction = $interactions[$featureReference]['interaction'];
                if ($format == "canvas") {
                    $interactionContent = new FlowCollection();
                    $interactionContent->attach($interaction);
                    $divCol = new Div();
                    $divCol->setClass('col-xs-12');
                    $divCol->setContent($interactionContent);
                    $divRow = new Div();
                    $divRow->setClass('row');
                    $divRowContent = new FlowCollection();
                    $divRowContent->attach($divCol);
                    $divRow->setContent($divRowContent);
                    $content = new FlowCollection();
                    $content->attach($divRow);
                } else {
                    $content = new FlowCollection();
                    $content->attach($interaction);
                }

                $replacement = ContentCollectionBuilder::buildContent($currentContainer, $content)->current();
                $currentContainer->getComponents()->replace($component, $replacement);
            }

            if ($component instanceof Span && StringUtil::contains($component->getClass(), 'learnosity-response')) {
                $content = new FlowCollection();
                $currentContainer = $iterator->getCurrentContainer();
                $questionReference = trim(str_replace('learnosity-response', '', $component->getClass()));
                $questionReference = trim(str_replace('question-', '', $questionReference));

                // Build the actual interaction
                $interaction = $interactions[$questionReference]['interaction'];
                if (isset($interactions[$questionReference]['extraContent'])) {
                    // In case of shorttext and clozetext its throwing error and closing div tag above the interaction
                    $questionTypeArr = ['shorttext', 'clozetext', 'clozedropdown'];
                    if (!in_array($questionType, $questionTypeArr)) {
                        $content->attach($interactions[$questionReference]['extraContent']);
                    }
                }

                $content->attach($interaction);
				$replacement = ContentCollectionBuilder::buildContent($currentContainer, $content)->current();
				$currentContainer->getComponents()->replace($component, $replacement);
            }
        }

        // Extract the actual content from the div wrapper and add that to our <itemBody>
        $componentsWithinDiv = $divWrapper->getComponents();
        $itemBody = new ItemBody();
        $itemBody->setContent(ContentCollectionBuilder::buildBlockCollectionContent($componentsWithinDiv));

        return $itemBody;
    }

    private function buildItemBodySimple(array $interactions)
    {
        $interactions = array_values($interactions);
        $contentCollection = new QtiComponentCollection();

        // Append the extra contents belong to an interaction before the interaction itself
        foreach ($interactions as $data) {
            if (isset($data['extraContent'])) {
                $content = QtiMarshallerUtil::unmarshallElement($data['extraContent']);
                $contentCollection->merge($content);
            }
            $contentCollection->attach($data['interaction']);
        }

        $itemBody = new ItemBody();
        $itemBody->setContent(ContentCollectionBuilder::buildBlockCollectionContent($contentCollection));
        return $itemBody;
    }
    
    private function removeUnusedSpanFromContent(array $interactions, $content)
    {

        $dom = new DOMDocument();
        $dom->loadHTML($content);
        $dom->formatOutput = true;

        $xpath = new DOMXpath($dom);
        $class = 'learnosity-response';
        $featureClass = 'learnosity-feature';
        $spanTag = $xpath->query("//span[contains(@class,'$class')]");
        $featureSpanTag = $xpath->query("//span[contains(@class,'$featureClass')]");

        foreach ($spanTag as $span) {
            $questionReference = trim(str_replace('learnosity-response question-', '', $span->getAttribute('class')));
            if (!isset($interactions[$questionReference])) {
                $span->parentNode->removeChild($span);
            }
        }

        foreach ($featureSpanTag as $span) {
            $featureReference = trim(str_replace('learnosity-feature feature-', '', $span->getAttribute('class')));
            if (!isset($interactions[$featureReference])) {
                $span->parentNode->removeChild($span);
            }
        }

        // remove doctype and html, body tag
        $dom->removeChild($dom->doctype);
        $dom->replaceChild($dom->firstChild->firstChild->firstChild, $dom->firstChild);
        $newHtml = $dom->saveHTML();
        return $newHtml;
    }
}
