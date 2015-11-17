<?php

namespace LearnosityQti\Processors\IMSCP\Out\MetadataEngines;

use LearnosityQti\Processors\IMSCP\Entities\Metadata;

class FlatMetadataEngine
{
    public function flatten(Metadata $metadata)
    {
        $flatTags = [];
        foreach ($metadata->getFlattenedMetadata() as $key => $value) {
            $flatTags[] = $key . ':' . $value;
        }
        return $flatTags;
    }
}
