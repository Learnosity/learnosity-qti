<?php

namespace LearnosityQti\Processors\QtiV2\In\ItemBuilders;

use \LearnosityQti\Entities\Item\item;
use \LearnosityQti\Processors\QtiV2\In\ResponseProcessingTemplate;
use \LearnosityQti\Processors\QtiV2\In\Constants;
use \qtism\data\content\ItemBody;
use \qtism\data\QtiComponentCollection;
use \qtism\data\AssessmentItem;
use qtism\data\content\RubricBlock;
use LearnosityQti\Processors\QtiV2\In\RubricBlockMapper;
use LearnosityQti\Services\LogService;

abstract class AbstractItemBuilder
{
    protected $itemReference;
    protected $questions = [];
    protected $features = [];
    protected $metadata = [];
    protected $content = '';
    protected $assessmentItem;
    protected $sourceDirectoryPath = null;

    public function getItem()
    {
        $item = new item($this->itemReference, array_merge(array_keys($this->questions), array_keys($this->features)), $this->content);
        $item->set_status('published');
        $item->set_questions(array_map(function ($widgetReference) {
            return [ 'reference' => $widgetReference ];
        }, array_keys($this->questions)));
        $item->set_features(array_map(function ($widgetReference) {
            return [ 'reference' => $widgetReference ];
        }, array_keys($this->features)));
        $item->set_metadata($this->metadata);
        return $item;
    }

    public function getFeatures()
    {
        return array_values($this->features);
    }

    public function getQuestions()
    {
        return array_values($this->questions);
    }

    protected function getMapperInstance($interactionType, $params)
    {
        $reflectionClass = new \ReflectionClass(static::MAPPER_CLASS_BASE.  ucfirst($interactionType . 'Mapper'));
        return $reflectionClass->newInstanceArgs($params);
    }

    abstract public function map(
        $assessmentItemIdentifier,
        ItemBody $itemBody,
        QtiComponentCollection $interactionComponents,
        QtiComponentCollection $responseDeclarations = null,
        ResponseProcessingTemplate $responseProcessingTemplate = null,
        QtiComponentCollection $rubricBlockComponents = null
    );

    public function setAssessmentItem(AssessmentItem $assessmentItem)
    {
        $this->assessmentItem = $assessmentItem;
    }

    public function setSourceDirectoryPath($sourceDirectoryPath)
    {
        $this->sourceDirectoryPath = $sourceDirectoryPath;
    }

    protected function processRubricBlock(RubricBlock $rubricBlock)
    {
        $mapper = new RubricBlockMapper($this->sourceDirectoryPath);
        $result = $mapper->parseWithRubricBlockComponent($rubricBlock);

        if (!empty($result['features'])) {
            $features = $result['features'];

            // Set widget reference to something predictable so we don't create a different
            // feature reference every time. This is so that we can share features that are
            // imported with common content, and to avoid storing duplicate features when
            // importing the same QTI content multiple times.
            foreach ($features as $feature) {
                // Generate a hash for the contents of this feature to use in the reference
                $featureDataHash = sha1(json_encode($feature->to_array()['data']));
                // TODO: Review whether this needs to be deduped by item. Reason - while it
                // is desirable to share passages between items, it may not be desirable to
                // share all types of features between items.
                $feature->set_reference($featureDataHash);
            }
            $this->features = array_merge($this->features, $features);
        }

        if (!empty($result['stimulus'])) {
            // Prepend stimulus to the first question
            $firstQuestionReference = key($this->questions);
            $newStimulus = $result['stimulus'] . $this->questions[$firstQuestionReference]->get_data()->get_stimulus();
            $this->questions[$firstQuestionReference]->get_data()->set_stimulus($newStimulus);

            LogService::log('<rubricBlock> stimulus content is prepended to question stimulus; please verify as this "might" break item content structure');
        }

        if (!empty($result['metadata'])) {
            $this->metadata = array_merge_recursive($this->metadata, $result['metadata']);
        }
    }
}
