<?php

namespace LearnosityQti\Processors\IMSCP\Out;

use LearnosityQti\Processors\IMSCP\Entities\Metadata;
use LearnosityQti\Processors\IMSCP\Out\MetadataEngines\FlatMetadataEngine;
use LearnosityQti\Processors\IMSCP\Out\MetadataEngines\SimpleMetadataEngine;
use LearnosityQti\Processors\IMSCP\Out\MetadataEngines\ValueMapEngine;

class TagsWriter
{
    public function convert(Metadata $metadata, $rules = [])
    {
        // Shall we trim?
        $trim = isset($rules['trim']) ? $rules['trim'] : true;
        if ($trim == true) {
            $metadata->trimValues();
        }

        // Shall we include unmapped metadata?
        $includeUnmapped = isset($rules['includeUnmapped']) ? $rules['includeUnmapped'] : true;

        // Lets do mapping!
        $convertedTags = [];

        // TODO: Some mapping hack, need proper test and tidy up also proper JSON validation
        // Do `simple` mapping
        if (!$metadata->isEmpty() && isset($rules['rules']['simple'])) {
            $simpleRules = $rules['rules']['simple'];
            $engine = new SimpleMetadataEngine();
            list($convertedTags, $metadata) = $engine->mapTagsSimple($metadata, $simpleRules);
        }

        // TODO: Some mapping hack, need proper test and tidy up also proper JSON validation
        // Do `valueMap` mapping
        if (!$metadata->isEmpty() && isset($rules['rules']['valueMap'])) {
            $valueMapRules = $rules['rules']['valueMap'];
            $engine = new ValueMapEngine();
            list($valueMapTags, $metadata) = $engine->mapValueMap($metadata, $valueMapRules);
            $convertedTags = array_merge_recursive($convertedTags, $valueMapTags);
        }

        // Fix the rest with dah default `flat` mapping
        if (!$metadata->isEmpty() && $includeUnmapped === true) {
            $engine = new FlatMetadataEngine();
            $otherTags = ['IMSMDMetadata' => $engine->flatten($metadata)];
            $convertedTags = array_merge_recursive($convertedTags, $otherTags);
        }

        // Avoid duplication
        foreach ($convertedTags as &$tags) {
            $tags = array_values(array_unique($tags));
        }
        return $convertedTags;
    }
}
