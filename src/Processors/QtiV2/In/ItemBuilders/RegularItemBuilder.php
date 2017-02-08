<?php

namespace LearnosityQti\Processors\QtiV2\In\ItemBuilders;

use LearnosityQti\Entities\Question;
use LearnosityQti\Processors\QtiV2\In\ResponseProcessingTemplate;
use LearnosityQti\Utils\QtiMarshallerUtil;
use LearnosityQti\Services\LogService;
use LearnosityQti\Utils\SimpleHtmlDom\SimpleHtmlDom;
use qtism\data\content\interactions\Interaction;
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
        ResponseProcessingTemplate $responseProcessingTemplate = null
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
                [$component, $responseDeclaration, $responseProcessingTemplate, $outcomeDeclaration]
            );
            $question = $mapper->getQuestionType();

            $this->questions[$questionReference] = new Question($question->get_type(), $questionReference, $question);
            $questionsXmls[$questionReference] = [
                'qtiClassName' => $component->getQtiClassName(),
                'responseIdentifier' => $component->getResponseIdentifier()
            ];
        }

        // Build item's HTML content
        $extraContentHtml = new SimpleHtmlDom();
        if (!$extraContentHtml->load(QtiMarshallerUtil::marshallCollection($itemBody->getComponents()), false)) {
            throw new \Exception('Issues with the content for itemBody, it might not be valid');
        }

        foreach ($questionsXmls as $questionReference => $interactionData) {
            // Append this question span to our `item` content as it is
            $this->content .= '<span class="learnosity-response question-' . $questionReference . '"></span>';
            // Clean up interaction HTML content
            $qtiClassName = $interactionData['qtiClassName'];
            $responseIdentifier = $interactionData['responseIdentifier'];
            $toFind = $qtiClassName . '[responseIdentifier="' . $responseIdentifier. '"]';
            foreach ($extraContentHtml->find($toFind) as &$tag) {
                $tag->outertext = '';
            }
        }
        $extraContent = $extraContentHtml->save();

        // Making assumption question always has stimulus `right`?
        // So, prepend the extra content on the stimulus on the first question
        if (!empty(trim($extraContent))) {
            $firstQuestionReference = key($this->questions);
            $newStimulus = $extraContent . $this->questions[$firstQuestionReference]->get_data()->get_stimulus();
            $this->questions[$firstQuestionReference]->get_data()->set_stimulus($newStimulus);

            LogService::log('Extra <itemBody> content is prepended to question stimulus and please verify as this `might` break item content structure');
        }
        return true;
    }
}
