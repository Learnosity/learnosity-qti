<?php

namespace Learnosity\Processors\QtiV2\In\ItemBuilders;

use Learnosity\Entities\BaseQuestionType;
use Learnosity\Entities\Question;
use Learnosity\Processors\QtiV2\In\MergedInteractions\AbstractMergedInteraction;
use Learnosity\Processors\QtiV2\In\ResponseProcessingTemplate;
use qtism\data\content\interactions\Interaction;
use qtism\data\content\ItemBody;
use qtism\data\QtiComponentCollection;
use qtism\data\state\ResponseDeclaration;

class MergedItemBuilder extends AbstractItemBuilder
{
    const MAPPER_CLASS_BASE = 'Learnosity\Processors\QtiV2\In\MergedInteractions\\Merged';

    public function map(
        $assessmentItemIdentifier,
        ItemBody $itemBody,
        QtiComponentCollection $interactionComponents,
        QtiComponentCollection $responseDeclarations = null,
        ResponseProcessingTemplate $responseProcessingTemplate = null
    ) {

        $mergedInteractionType = $this->getMergedInteractionType($interactionComponents);
        if (!$mergedInteractionType) {
            return false;
        }
        $this->assessmentItemIdentifier = $assessmentItemIdentifier;

        $questionReference = $this->buildMergedQuestionReference($interactionComponents);
        /** @var AbstractMergedInteraction $mapper */
        $mapper = $this->getMapperInstance(
            $mergedInteractionType,
            [$questionReference, $itemBody, $responseDeclarations, $responseProcessingTemplate]
        );

        /** @var BaseQuestionType $question */
        $question = $mapper->getQuestionType();
        $this->questions[$questionReference] =
            new Question($question->get_type(), $questionReference, $question);
        $this->content = $mapper->getItemContent();
        $this->exceptions = array_merge($this->exceptions, $mapper->getExceptions());
        return true;
    }

    protected function buildMergedQuestionReference(QtiComponentCollection $interactionComponents)
    {
        $questionReference = $this->assessmentItemIdentifier;
        foreach ($interactionComponents as $component) {
            /* @var $component Interaction */
            $questionReference .= '_' . $component->getResponseIdentifier();
            /** @var ResponseDeclaration $responseDeclaration */
            // TODO: Need checking if merged exists, maybe again?
        }
        return $questionReference;
    }

    protected function getMergedInteractionType(QtiComponentCollection $interactionComponents)
    {
        // Decide whether we shall merge interaction
        $interactionTypes = array_unique(array_map(function ($component) {
            /* @var $component Interaction */
            return $component->getQtiClassName();
        }, $interactionComponents->getArrayCopy()));
        $possibleMergedInteractionTypes = ['textEntryInteraction', 'inlineChoiceInteraction'];

        if (count($interactionTypes) === 1 && in_array($interactionTypes[0], $possibleMergedInteractionTypes)) {
            return $interactionTypes[0];
        } else {
            return false;
        }
    }
}
