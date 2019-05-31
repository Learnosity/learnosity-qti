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
        $dom->loadHTML('<?xml version="1.0" encoding="UTF-8"><itemBody>'.$extraContentHtml->save().'</itemBody>', LIBXML_HTML_NODEFDTD | LIBXML_HTML_NOIMPLIED);
        $xpath = new DOMXPath($dom);

        $questionHtmlContents = [];
        foreach ($questionsXmls as $questionReference => $interactionData) {
            // Append this question span to our `item` content as it is
            $this->content .= '<span class="learnosity-response question-' . $questionReference . '"></span>';

            $qtiClassName = $interactionData['qtiClassName'];
            $responseIdentifier = $interactionData['responseIdentifier'];

            $toQuery = '//'.strtolower($qtiClassName).'[@responseidentifier="'.$responseIdentifier.'"]';
            // Clean up interaction HTML content
            foreach ($xpath->query('/itembody'.$toQuery) as $element) {
                $elementRoot = $this->getElementHierarchyRoot($element, $dom->documentElement);
                
                // Write out the hierarchy structure to a new DOM to get the content
                $newDom = new DOMDocument();
                $newDom->preserveWhitespace = false;
                $newXPath = new DOMXPath($newDom);
                $newDom->appendChild($newDom->importNode($elementRoot, true));
                foreach ($newXPath->query($toQuery) as $newElement) {
                    // HACK: Remove any trailing sibling nodes (as it won't look sensible with the elements gone).
                    $siblings = [];
                    $nextNode = $newElement;
                    while (!is_null($nextNode = $nextNode->nextSibling)) {
                        $siblings[] = $nextNode;
                    }
                    
                    foreach ($siblings as $siblingNode) {
                        $siblingNode->parentNode->removeChild($siblingNode);
                    }

                    // Remove the node itself from the stimulus content
                    // $newElement->parentNode->removeChild($newElement);
                    // HACK: Put a placeholder so we can pop prompts in place later on.
                    $placeholderNode = $newDom->createTextNode($questionReference);
                    $newElement->parentNode->replaceChild($placeholderNode, $newElement);

                    // TODO: When looking at siblings and ancestor relatives to keep, we should only keep text nodes and wrapping content
                    // HACK: Forcefully remove other interactions from this hierarchy
                    $extraInteractions = $newXPath->query('//'.strtolower($qtiClassName).'[@responseidentifier!="'.$responseIdentifier.'"]');
                    foreach ($extraInteractions as $interaction) {
                        $interaction->parentNode->removeChild($interaction);
                    }
                }
                // Remove the whole hierarchy from the remaining itemBody content
                // TODO: Check if removing interactions while iterating on DOMNodeList causes issues
                $elementRoot->parentNode->removeChild($elementRoot);

                if (!isset($questionHtmlContents[$questionReference])) {
                    $questionHtmlContents[$questionReference] = '';
                }
                $questionHtmlContents[$questionReference] .= $newDom->saveHTML();
            }
        }

        // Remove the wrapping <itemBody> before saving
        // HACK: Instead of replacing with a fragment, replace with a div;
        // this is so we have a document element to remove the XML declaration with
        /* @var DOMNode $fragment */
        $fragment = $dom->createElement('div');
        while ($dom->documentElement->childNodes->length > 0) {
            $fragment->appendChild($dom->documentElement->childNodes->item(0));
        }
        $dom->documentElement->parentNode->replaceChild($fragment, $dom->documentElement);
        $extraContent = '';
        if ($fragment->hasChildNodes()) {
            $extraContent = $dom->saveHTML($dom->documentElement);
        }

        // Inject item content into stimulus per question
        if (!empty($this->questions)) {
            foreach ($questionHtmlContents as $questionReference => $content) {
                $existingStimulus = $this->questions[$questionReference]->get_data()->get_stimulus();
                // HACK: Replace placeholders in item content with <prompt> stimulus, and inject the whole thing
                $newStimulus = str_replace($questionReference, $existingStimulus, $content);
                $this->questions[$questionReference]->get_data()->set_stimulus($newStimulus);

                LogService::log('Extra <itemBody> content is prepended to question stimulus and please verify as this `might` break item content structure');
            }

            // Making assumption question always has stimulus `right`?
            // So, prepend the extra content on the stimulus on the first question
            if (!empty(trim($extraContent))) {
                $firstQuestionReference = key($this->questions);
                $existingStimulus = $this->questions[$firstQuestionReference]->get_data()->get_stimulus();
                $newStimulus = $extraContent . $existingStimulus;
                $this->questions[$firstQuestionReference]->get_data()->set_stimulus($newStimulus);

                LogService::log('Extra <itemBody> content is prepended to question stimulus and please verify as this `might` break item content structure');
            }
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
