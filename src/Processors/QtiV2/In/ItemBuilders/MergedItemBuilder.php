<?php

namespace LearnosityQti\Processors\QtiV2\In\ItemBuilders;

use LearnosityQti\Entities\BaseQuestionType;
use LearnosityQti\Entities\Question;
use LearnosityQti\Processors\QtiV2\In\Constants;
use LearnosityQti\Processors\QtiV2\In\MergedInteractions\AbstractMergedInteractionMapper;
use LearnosityQti\Processors\QtiV2\In\ResponseProcessingTemplate;
use LearnosityQti\Services\LogService;
use LearnosityQti\Exceptions\MappingException;
use qtism\data\content\interactions\Interaction;
use qtism\data\content\ItemBody;
use qtism\data\QtiComponentCollection;
use qtism\data\state\ResponseDeclaration;

class MergedItemBuilder extends AbstractItemBuilder
{
    const MAPPER_CLASS_BASE = 'LearnosityQti\Processors\QtiV2\In\MergedInteractions\\Merged';

    public function map(
        $itemReference,
        ItemBody $itemBody,
        QtiComponentCollection $interactionComponents,
        QtiComponentCollection $responseDeclarations = null,
        ResponseProcessingTemplate $responseProcessingTemplate = null,
        QtiComponentCollection $rubricBlockComponents = null
    ) {

        $mergedInteractionType = $this->getMergedInteractionType($interactionComponents);
        if (!$mergedInteractionType) {
            return false;
        }
        $this->itemReference = $itemReference;

        $questionReference = $this->buildMergedQuestionReference($interactionComponents);
        $outcomeDeclaration = $this->assessmentItem->getOutcomeDeclarations();
        /** @var AbstractMergedInteractionMapper $mapper */
        $mapper = $this->getMapperInstance(
            $mergedInteractionType,
            [$questionReference, $itemBody, $responseDeclarations, $responseProcessingTemplate, $outcomeDeclaration]
        );

        /** @var BaseQuestionType $question */
        $question = $mapper->getQuestionType();
        $this->questions[$questionReference] =
            new Question($question->get_type(), $questionReference, $question);
        $this->content = $mapper->getItemContent();

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

    protected function buildMergedQuestionReference(QtiComponentCollection $interactionComponents)
    {
        $questionReference = $this->itemReference;
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

        if (count($interactionTypes) === 1 && in_array($interactionTypes[0], Constants::$needMergeInteractionTypes)) {
            return $interactionTypes[0];
        } else {
            return false;
        }
    }
}
