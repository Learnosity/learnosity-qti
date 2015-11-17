<?php

namespace LearnosityQti\Processors\IMSCP\Out\MetadataEngines;

use LearnosityQti\Processors\IMSCP\Entities\Metadata;

class ValueMapEngine
{
    public function mapValueMap(Metadata $metadata, array $rules = [])
    {
        $convertedTags = [];

        return [$convertedTags, $metadata];
    }
}
