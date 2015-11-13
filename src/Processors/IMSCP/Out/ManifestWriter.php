<?php

namespace LearnosityQti\Processors\IMSCP\Out;

use LearnosityQti\Entities\Activity\activity_data;
use LearnosityQti\Processors\IMSCP\Entities\Manifest;

class ManifestWriter
{
    public function convert(Manifest $manifest) {
        $activityReference = $manifest->getIdentifier();

        // Let's map package metadata as activity tags
        // We can write custom replacer or remover to fix the messy `identifier:catalog:` afterwards
        $activityTags = [
            'reference' => $activityReference,
            'tags' => $manifest->getMetadata()
        ];

        // Build item reference and item tags JSON
        $itemReferences = [];
        $itemsTags = [];
        foreach ($manifest->getResources() as $resource) {
            /** @var Resource $resource */
            $itemReference   = $resource->getIdentifier();
            $itemReferences[] = $itemReference;
            $itemsTags[]     = [
                'reference' => $itemReference,
                'tags' => $resource->getMetadata()
            ];
        }

        // Build activity JSON
        $activity = [
            'reference' => $activityReference,
            'data' => [
                'items' => $itemReferences
            ],
            'status' => 'published'
        ];

        // Obvious there `items` hasn't been validated against
        return [$activity, $activityTags, $itemsTags];
    }
}
