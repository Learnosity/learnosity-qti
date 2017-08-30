<?php

namespace LearnosityQti\Utils\Item;

class ItemService
{
    const ITEM_CONTENT_FEATURE_REF_PREFIX  = 'feature-';
    const ITEM_CONTENT_FEEDBACK_REF_PREFIX = 'feedback-';
    const ITEM_CONTENT_RESPONSE_REF_PREFIX = 'response-';

    public function getOrderedWidgetsListFromItem(array $item)
    {
        $widgets = [];

        if (isset($item['questionReferences'])) {
            $widgetReferences = $item['questionReferences'];
            $widgets = $this->formatReferencesForDefinition($widgetReferences);
        } else {
            if (isset($item['questions'])) {
                $widgets = array_merge($widgets, $item['questions']);
            }
            if (isset($item['features'])) {
                $widgets = array_merge($widgets, $item['features']);
            }
        }

        return $widgets;
    }

    public function getQuestionsListFromItem(array $item)
    {
        $questions = [];

        if (isset($item['questions'])) {
            $questions = $item['questions'];
        } elseif ($this->canFindTypedWidgetReferencesFromItem($item)) {
            // Filter only for valid question type references (response, feedback)
            $validQuestionPrefixes = [
                static::ITEM_CONTENT_FEEDBACK_REF_PREFIX,
                static::ITEM_CONTENT_RESPONSE_REF_PREFIX,
            ];
            $questionReferences = $this->findWidgetReferencesWithTypePrefixesFromItem($item, $validQuestionPrefixes);
            $questions = $this->formatReferencesForDefinition($questionReferences);
        }

        return $questions;
    }

    public function getFeaturesListFromItem(array $item)
    {
        $features = [];

        if (isset($item['features'])) {
            $features = $item['features'];
        } elseif ($this->canFindTypedWidgetReferencesFromItem($item)) {
            // Filter only for valid feature type references
            $validFeaturePrefixes = [
                static::ITEM_CONTENT_FEATURE_REF_PREFIX,
            ];
            $featureReferences = $this->findWidgetReferencesWithTypePrefixesFromItem($item, $validFeaturePrefixes);
            $features = $this->formatReferencesForDefinition($featureReferences);
        }

        return $features;
    }

    private function canFindTypedWidgetReferencesFromItem(array $item)
    {
        return isset($item['questionReferences']) && !empty($item['content']);
    }

    private function findWidgetReferencesWithTypePrefixesFromItem(array $item, array $validTypePrefixes)
    {
        // Extracting questions from questionReferences requires valid item.content to work
        $itemContent      = $item['content'];
        $widgetReferences = $item['questionReferences'];

        $references = array_filter($widgetReferences, function ($widgetRef) use ($itemContent, $validTypePrefixes) {
            foreach ($validTypePrefixes as $typePrefix) {
                if (strpos($itemContent, $typePrefix.$widgetRef) !== false) {
                    return true;
                }
            }
        });

        return $references;
    }

    private function formatReferencesForDefinition(array $widgetReferences)
    {
        return array_map(function ($widgetReference) {
            return [ 'reference' => $widgetReference ];
        }, $widgetReferences);
    }
}
