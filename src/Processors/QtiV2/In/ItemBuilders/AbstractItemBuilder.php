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
use LearnosityQti\Entities\Question;

abstract class AbstractItemBuilder
{
    protected $itemReference;
    protected $questions = [];
    protected $features = [];
    protected $metadata = [];
    protected $questionsMetadata = [];
    protected $content = '';
    protected $assessmentItem;
    protected $rubricData;
    protected $sourceDirectoryPath = null;

    // Used to describe the maximum possible score (used for rubrics)
    protected $itemPointValue;

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

    public function getRubricItem()
    {
        $item = null;
        if (isset($this->rubricData)) {
            // Build the content for the new rubric item
            $rubricContent = '';
            foreach (array_filter($this->rubricData['widgets']) as $widget) {
                $widgetType = $widget->get_data()->get_widget_type();
                if ($widgetType === 'feedback') {
                    $widgetType = 'question';
                }
                $featureOrResponse = $widgetType === 'feature' ? $widgetType : 'response';
                $rubricContent .= "<span class=\"learnosity-{$featureOrResponse} {$widgetType}-{$widget->get_reference()}\"></span>";
            }

            $item = new item(
                $this->metadata['rubric_reference'],
                array_map(function ($widget) { return $widget->get_reference(); }, array_filter($this->rubricData['widgets'])),
                $rubricContent
            );
            $item->set_status('published');
            if (isset($this->rubricData['questionReferences'])) {
                $item->set_questions($this->rubricData['questionReferences']);
            }
            if (isset($this->rubricData['featureReferences'])) {
                $item->set_features($this->rubricData['featureReferences']);
            }
        }

        return $item;
    }

    public function getFeatures()
    {
        return array_merge(array_values($this->features), $this->getRubricFeatures());
    }

    public function getQuestions()
    {
        // FIXME: Should this be getting set every time on read?
        if (!empty($this->questionsMetadata)) {
            foreach ($this->questions as $question) {
                /** @var Question $question */
                $data = $question->get_data();
                $metadata = $data->get_metadata();

                if (!isset($metadata)) {
                    $qtype = $data->get_type();
                    // HACK: longtextV2 doesn't have a corresponding entity, so force it to use the similar longtext one
                    if ($qtype === 'longtextV2') {
                        $qtype = 'longtext';
                    }
                    $metadataClass = '\\LearnosityQti\\Entities\\QuestionTypes\\'.$qtype.'_metadata';
                    $metadata = new $metadataClass();
                    $data->set_metadata($metadata);
                }

                $this->populateQuestionMetadata($metadata, $this->questionsMetadata);
            }
        }
        return array_merge(array_values($this->questions), $this->getRubricQuestions());
    }

    protected function getRubricQuestions()
    {
        return $this->getRubricWidgetsWithType('question');
    }

    protected function getRubricFeatures()
    {
        return $this->getRubricWidgetsWithType('feature');
    }

    protected function getRubricWidgetsWithType($targetWidgetType)
    {
        $rubricQuestions = [];
        if (!empty($this->rubricData['widgets'])) {
            $rubricQuestions = array_filter(array_filter($this->rubricData['widgets']), function ($widget) use ($targetWidgetType) {
                $widgetType = $widget->get_data()->get_widget_type();
                if ($widgetType === 'feedback') {
                    $widgetType = 'question';
                }
                return $widgetType === $targetWidgetType;
            });
        }

        return $rubricQuestions;
    }

    protected function populateQuestionMetadata($metadata, array $metadataValues)
    {
        foreach ($metadataValues as $key => $value) {
            switch ($key) {
                case 'distractor_rationale_author':
                    usort($value, function ($a, $b) {
                        return strcmp($a['label'], $b['label']);
                    });
                    $metadata->distractor_rationale_author = join('', array_column($value, 'content'));
                    break;

                case 'rubric_reference':
                    $metadata->set_rubric_reference($value);
            }
        }
    }

    protected function addFeatures(array $features)
    {
        $this->features = array_merge($this->features, $features);
    }

    protected function addQuestions(array $questions)
    {
        $this->questions = array_merge($this->questions, $questions);
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

    protected function setItemMetadata(array $itemMetadata)
    {
        $this->metadata = array_merge_recursive($this->metadata, $itemMetadata);
    }

    public function setItemPointValue($itemPointValue)
    {
        $this->itemPointValue = $itemPointValue;
    }

    public function setQuestionMetadata(array $questionsMetadata)
    {
        $this->questionsMetadata = array_merge_recursive($this->questionsMetadata, $questionsMetadata);
    }

    public function setSourceDirectoryPath($sourceDirectoryPath)
    {
        $this->sourceDirectoryPath = $sourceDirectoryPath;
    }

    protected function processRubricBlock(RubricBlock $rubricBlock)
    {
        $mapper = new RubricBlockMapper($this->sourceDirectoryPath);
        $mapper->setRubricPointValue($this->itemPointValue);
        $result = $mapper->parseWithRubricBlockComponent($rubricBlock);

        if (isset($result['type']) && $result['type'] === 'ScoringGuidance') {
            $this->processScoringGuidanceContent($result);
            return;
        }
        if (!empty($result['features'])) {
            $updatedFeatures = $this->processAdditionalFeatures($result['features']);
            $this->addFeatures($updatedFeatures);
        }
        if (!empty($result['questions'])) {
            $updatedQuestions = $this->processAdditionalQuestions($result['questions']);
            $this->addQuestions($updatedQuestions);
        }
        if (!empty($result['stimulus'])) {
            $this->processAdditionalStimulus($result['stimulus']);
        }
        if (!empty($result['metadata'])) {
            $this->setItemMetadata($result['metadata']);
        }
        if (!empty($result['question_metadata'])) {
            $this->setQuestionMetadata($result['question_metadata']);
        }
    }

    private function processScoringGuidanceContent(array $result)
    {
        // TODO: Support creation of another item for the case where we get back scoring guidance
        if (!isset($this->rubricData)) {
            $this->rubricData = [
                'widgets' => [],
            ];
            $this->setQuestionMetadata([
                'rubric_reference' => $this->itemReference.'_rubric',
            ]);
        }

        $widgetOffset = $this->rubricData['widgets']->length;
        if (isset($result['label']) && (int)($result['label']) == $result['label']) {
            $widgetOffset = (int)$result['label'] - 1;
        }

        $widgetsToInsert = [];
        if (!empty($result['questions'])) {
            $questions = $this->processAdditionalQuestions($result['questions']);
            $widgetsToInsert = array_merge($widgetsToInsert, $questions);
            foreach ($questions as $questionReference => $question) {
                $this->rubricData['questionReferences'][] = [ 'reference' => $question->get_reference() ];
            }
        }
        if (!empty($result['features'])) {
            $features = $this->processAdditionalFeatures($result['features']);
            $widgetsToInsert = array_merge($widgetsToInsert, $features);
            foreach ($features as $featureReference => $feature) {
                $this->rubricData['featureReferences'][] = [ 'reference' => $feature->get_reference() ];
            }
        }

        // HACK: make sure we can insert the arrays in the correct order
        if ($widgetOffset > $this->rubricData['widgets']->length) {
            $this->rubricData['widgets'] = array_pad($this->rubricData['widgets'], $widgetOffset, null);
        }
        // FIXME: This implementation is buggy; it can't handle when there are multiple widgets to insert.
        // TODO: Implement it as a multidimensional array of collections that is flattened at the end instead.
        array_splice($this->rubricData['widgets'], $widgetOffset, 0, $widgetsToInsert);
    }

    private function processAdditionalStimulus($additionalStimulus)
    {
        // APPEND stimulus content to the first question stimulus
        $firstQuestionReference = key($this->questions);
        $newStimulus = $this->questions[$firstQuestionReference]->get_data()->get_stimulus() . $additionalStimulus;
        $this->questions[$firstQuestionReference]->get_data()->set_stimulus($newStimulus);

        LogService::log('<rubricBlock> stimulus content is prepended to question stimulus; please verify as this "might" break item content structure');
    }

    private function processAdditionalQuestions(array $questions)
    {
        // Set widget reference to something predictable so we don't create a different
        // feature reference every time. This is to avoid storing duplicate questions when
        // importing the same QTI content multiple times.
        $updatedQuestions = [];
        foreach ($questions as $question) {
            $questionsArray = $question->to_array();
            unset($questionsArray['reference']);
            $questionHash = sha1(json_encode($questionsArray));
            $question->set_reference($this->itemReference.'_'.$questionHash);
            $updatedQuestions[$question->get_reference()] = $question;
        }

        return $updatedQuestions;
    }

    private function processAdditionalFeatures(array $features)
    {
        // Set widget reference to something predictable so we don't create a different
        // feature reference every time. This is so that we can share features that are
        // imported with common content, and to avoid storing duplicate features when
        // importing the same QTI content multiple times.
        $updatedFeatures = [];
        foreach ($features as $feature) {
            $featureDataHash = sha1(json_encode($feature->to_array()['data']));

            // TODO: Review whether refs need to be deduped by item. Reason - while it
            // is desirable to share passages between items, it may not be desirable to
            // share all types of features between items.
            $feature->set_reference($featureDataHash);
            $updatedFeatures[$feature->get_reference()] = $feature;
        }

        return $updatedFeatures;
    }
}
