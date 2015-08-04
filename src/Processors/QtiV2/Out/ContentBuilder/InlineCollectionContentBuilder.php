<?php

namespace Learnosity\Processors\QtiV2\Out\ContentBuilder;

use qtism\data\content\Inline;
use qtism\data\QtiComponentCollection;

class InlineCollectionContentBuilder extends AbstractContentBuilder
{
    public function buildContentCollection(QtiComponentCollection $contentCollection)
    {
        // Check whether the content is already an InlineCollection
        $areInlineComponents = array_reduce($contentCollection->getArrayCopy(), function ($initial, $component) {
            return $initial && $component instanceof Inline;
        }, true);

        if ($areInlineComponents) {
            return $contentCollection;
        }

        // Otherwise, make it inline - impossible to make things inline if contents are block elements
        // TODO: Give up for now
        throw new \Exception('Fail to build inline content');
    }
}
