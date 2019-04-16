<?php

namespace LearnosityQti\Processors\QtiV2\Out\ContentBuilders;

use qtism\data\content\Block;
use qtism\data\content\BlockCollection;
use qtism\data\content\FlowCollection;
use qtism\data\content\xhtml\text\Div;
use qtism\data\QtiComponentCollection;

class BlockCollectionContentBuilder extends AbstractContentBuilder
{
    public function buildContentCollection(QtiComponentCollection $contentCollection)
    {
        $areBlockComponents = array_reduce($contentCollection->getArrayCopy(), function ($initial, $component) {
            return $initial && $component instanceof Block;
        }, true);

        // Check whether the content could all be attached as is
        if ($areBlockComponents) {
             
            $blockCollection = new BlockCollection();
            foreach ($contentCollection as $component) {
                $blockCollection->attach($component);
            }
            return $blockCollection;
        }

        // Otherwise, build a `div` wrapper around it
        $divCollection = new FlowCollection();
        foreach ($contentCollection as $component) {
            $divCollection->attach($component);
        }
        $div = new Div();
        $div->setContent($divCollection);

        $blockCollection = new BlockCollection();
        $blockCollection->attach($div);
        return $blockCollection;
    }
}
