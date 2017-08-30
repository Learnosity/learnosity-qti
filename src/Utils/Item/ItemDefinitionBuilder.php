<?php

namespace LearnosityQti\Utils\Item;

use \LearnosityQti\Utils\Item\ItemService;

class ItemDefinitionBuilder
{
    private $item;
    private $widgetsJson;
    private $preferTwoColumnLayout;

    /**
     * @param array $item
     * @param array $widgetsJson
     */
    public function __construct(array $item, array $widgetsJson = [], $preferTwoColumnLayout = false)
    {
        $this->item = $item;
        $this->widgetsJson = $widgetsJson;
        $this->preferTwoColumnLayout = $preferTwoColumnLayout;
    }

    /**
     * Builds an item definition.
     *
     * @return array
     */
    public function buildItemDefinition()
    {
        $definition = [];
        $definition = $this->addScrollingToRegion($definition);

        if ($this->isOneColumnLayout()) {
            $itemService = new ItemService();
            $definition['widgets'] = $itemService->getOrderedWidgetsListFromItem($this->item);
        } elseif ($this->isTwoColumnLayout()) {
            $definition['type'] = 'root';
            $definition['regions'] = [];

            // Add the left-side column (for shared passages only)
            $passages = $this->findSharedPassagesFromItem($this->item, $this->widgetsJson);
            $definition['regions'][] = $this->buildColumnDefinition($passages, true);

            // Add the right-side column (for questions)
            $definition['regions'][] = $this->buildColumnDefinition($this->findQuestionsFromItem($this->item));
        }

        return $definition;
    }

    /**
     * Builds a column definition.
     *
     * @param  array   $widgets
     * @param  boolean $shouldUseTabs
     *
     * @return array
     */
    protected function buildColumnDefinition(array $widgets = [], $shouldUseTabs = false)
    {
        $column = [
            'type'    => 'column',
            'width'   => 50,
        ];

        $canUseTabs = (count($widgets) === 2);

        if ($shouldUseTabs && $canUseTabs) {
            $tabs = [];
            $tabs['type'] = 'tabs';
            $tabs['regions'] = [];

            // Create a tab for each widget
            // TODO: Allow the label to be more specific/passed in somehow
            // HACK: Since this is only used for passages atm, we'll hardcode the template
            $tabLabelTemplate = 'Passage';
            foreach ($widgets as $index => $widget) {
                $tabs['regions'][] = $this->buildTabDefinition([$widget], $tabLabelTemplate.' '.($index + 1));
            }

            // Add the tabs to the column definition
            $column['regions'] = [];
            $column['regions'][] = $tabs;

        } elseif (!empty($widgets)) {
            $column['widgets'] = $widgets;
        }

        return $column;
    }

    /**
     * @param  array  $widgets
     * @param  string $label
     *
     * @return array
     */
    protected function buildTabDefinition(array $widgets = [], $label = '')
    {
        $tab = [
            'type'    => 'tab',
            'label'   => $label,
            'widgets' => $widgets,
        ];

        return $tab;
    }

    /**
     * @param  array $regionDefinition
     *
     * @return array
     */
    protected function addScrollingToRegion(array $regionDefinition)
    {
        $regionDefinition['scroll'] = [
            'enabled' => true,
        ];

        return $regionDefinition;
    }

    private function findQuestionsFromItem(array $item)
    {
        $itemService = new ItemService();
        return $itemService->getQuestionsListFromItem($item);
    }

    private function findFeaturesFromItem(array $item)
    {
        $itemService = new ItemService();
        return $itemService->getFeaturesListFromItem($item);
    }

    private function findSharedPassagesFromItem(array $item, array $widgetsJson = [])
    {
        $sharedPassages = [];
        $features = $this->findFeaturesFromItem($item);

        if (!empty($features)) {
            // Filter only features that have type: sharedpassage
            $sharedPassages = array_filter($features, function ($feature) use ($widgetsJson) {
                $foundSharedPassage = false;
                foreach ($widgetsJson as $widgetJson) {
                    if ($feature['reference'] === $widgetJson['reference']) {
                        $foundSharedPassage = $widgetJson['type'] === 'sharedpassage';
                    }
                    if ($foundSharedPassage) {
                        break;
                    }
                }
                return $foundSharedPassage;
            });
        }

        return $sharedPassages;
    }

    private function hasSharedPassages()
    {
        return count($this->findSharedPassagesFromItem($this->item, $this->widgetsJson));
    }

    private function isOneColumnLayout()
    {
        return !$this->isTwoColumnLayout($this->item);
    }

    private function isTwoColumnLayout()
    {
        return $this->preferTwoColumnLayout && $this->hasSharedPassages($this->item);
    }
}
