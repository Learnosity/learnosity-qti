<?php

namespace Learnosity\Processors\QtiV2\Out\ContentBuilders;

use qtism\data\QtiComponentCollection;

abstract class AbstractContentBuilder
{
    abstract public function buildContentCollection(QtiComponentCollection $contentCollection);
}
