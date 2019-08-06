<?php

namespace LearnosityQti\Processors\QtiV2\In\ItemBuilders;

use \DOMDocument;
use \DOMXPath;
use \DOMElement;
use LearnosityQti\Entities\Question;
use LearnosityQti\Processors\QtiV2\In\RubricBlockMapper;
use LearnosityQti\Processors\QtiV2\In\ResponseProcessingTemplate;
use LearnosityQti\Utils\QtiMarshallerUtil;
use LearnosityQti\Services\LogService;
use LearnosityQti\Utils\SimpleHtmlDom\SimpleHtmlDom;
use LearnosityQti\Exceptions\MappingException;
use qtism\data\content\interactions\Interaction;
use qtism\data\content\RubricBlock;
use qtism\data\content\BlockCollection;
use qtism\data\content\ItemBody;
use qtism\data\QtiComponentCollection;
use qtism\data\state\ResponseDeclaration;

class RegularItemBuilder extends AbstractItemBuilder
{
    const MAPPER_CLASS_BASE = 'LearnosityQti\Processors\QtiV2\In\Interactions\\';

    public function map(
        $itemReference,
        ItemBody $itemBody,
        QtiComponentCollection $interactionComponents,
        QtiComponentCollection $responseDeclarations = null,
        ResponseProcessingTemplate $responseProcessingTemplate = null,
        QtiComponentCollection $rubricBlockComponents = null
    ) {
        $this->itemReference = $itemReference;
        
        $questionsXmls = [];
        $responseDeclarationsMap = [];
        
        if ($responseDeclarations) {
            /** @var ResponseDeclaration $responseDeclaration */
            foreach ($responseDeclarations as $responseDeclaration) {
                $responseDeclarationsMap[$responseDeclaration->getIdentifier()] = $responseDeclaration;
            }
        }
        
        foreach ($interactionComponents as $component) {

            /* @var $component Interaction */
            $questionReference = $this->itemReference . '_' . $component->getResponseIdentifier();
            // Process <responseDeclaration>
            $responseDeclaration = isset($responseDeclarationsMap[$component->getResponseIdentifier()]) ?
                $responseDeclarationsMap[$component->getResponseIdentifier()] : null;
            $outcomeDeclaration = $this->assessmentItem->getOutcomeDeclarations();
            $mapper = $this->getMapperInstance(
                $component->getQtiClassName(),
                [$component, $responseDeclaration, $responseProcessingTemplate, $outcomeDeclaration, $this->organisationId]
            );

            $question = $mapper->getQuestionType();

            $this->questions[$questionReference] = new Question($question->get_type(), $questionReference, $question);
            $questionsXmls[$questionReference] = [
                'qtiClassName' => $component->getQtiClassName(),
                'responseIdentifier' => $component->getResponseIdentifier()
            ];
        }
        
        if (empty($this->questions)) {
            LogService::log('Item contains no valid, supported questions');
        }
        // Build item's HTML content
        $extraContentHtml = new SimpleHtmlDom();
        if (!$extraContentHtml->load(QtiMarshallerUtil::marshallCollection($itemBody->getComponents()), false)) {
            throw new \Exception('Issues with the content for itemBody, it might not be valid');
        }

        // Extra stimulus for each question.
        // HACK: Process whole DOM structure per interaction.
        $dom = new DOMDocument();
        $dom->preserveWhitespace = false;
        // NOTE: Make sure we wrap in an <itemBody> so we get the correct DOM structure (and documentElement)
        $dom->loadHTML('<?xml version="1.0" encoding="UTF-8"><itemBody>' . $extraContentHtml->save() . '</itemBody>', LIBXML_HTML_NODEFDTD | LIBXML_HTML_NOIMPLIED);
        $xpath = new DOMXPath($dom);

        $questionHtmlContents = [];
        $contentList = '';
        $previousContent = '';
        foreach ($questionsXmls as $questionReference => $interactionData) {

            // Append this question span to our `item` content as it is
            $this->content .= '<span class="learnosity-response question-' . $questionReference . '"></span>';
            $qtiClassName = $interactionData['qtiClassName'];
            $responseIdentifier = $interactionData['responseIdentifier'];
            $toQuery = '//' . strtolower($qtiClassName) . '[@responseidentifier="' . $responseIdentifier . '"]';
            // Clean up interaction HTML content
            //fetch each interaction content to get the stimulus 
            $appnodes = $xpath->query('/itembody' . $toQuery . '/preceding-sibling::*');
            for ($j = 0; $j < $appnodes->length; $j++) {
                if ($appnodes->item($j)->nodeName == strtolower($qtiClassName)) {
                    continue;
                }
                $contentList .= $dom->saveHTML($appnodes->item($j));
            }

            //replace the previous interaction content from this question stimulus
            $stimulus = '';
            if($contentList != '') {
                $stimulus = '<div>';
                $stimulus .= str_replace($previousContent, '', $contentList);
                $stimulus .= '</div>';
            }

            //store the previous interaction stimulus
            $previousContent = $contentList;

            // Inject item content into stimulus per question
            $existingStimulus = $this->questions[$questionReference]->get_data()->get_stimulus();
            $stimulus_content = $stimulus . $existingStimulus;
            $this->questions[$questionReference]->get_data()->set_stimulus($stimulus_content);
        }

        // TODO: Confirm that calling processRubricBlock after generating the item content won't break anything
        // Process <rubricBlock> elements
        // NOTE: This step needs to be done after questions are generated
        foreach ($rubricBlockComponents as $rubricBlock) {
            /** @var RubricBlock $rubricBlock */
            try {
                $this->processRubricBlock($rubricBlock);
            } catch (MappingException $e) {
                // Just log unsupported <rubricBlock> elements
                LogService::log($e->getMessage());
            }
        }
        return true;
    }

    private function getElementHierarchyRoot(DOMElement $element, \DOMNode $relativeContext = null)
    {
        $rootElement = $element;
        // Find the correct root element
        while (!is_null($rootElement->parentNode) && ($rootElement->parentNode !== $relativeContext)) {
            $rootElement = $rootElement->parentNode;
        }
        return $rootElement;
    }
}
