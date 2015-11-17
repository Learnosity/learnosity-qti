<?php

namespace LearnosityQti\Processors\IMSCP\Out;

use LearnosityQti\Processors\IMSCP\Entities\Manifest;

class ManifestWriter
{
    public function convert(Manifest $manifest, array $rules = []) {
        $activityReference = $manifest->getIdentifier();
        $tagsWriter = new TagsWriter();

        // Atm, we only have tags rules and this need to be validated and user shall be provided with a nice error message
        // TODO: Validation need to be done in future
        $tagRules = isset($rules['tags']) ? $rules['tags'] : [];

        // Let's map package metadatas as activity tags
        // We can write custom replacer or remover to fix the messy `identifier:catalog:` afterwards
        $activityTags = [];
        $metadatas = $manifest->getMetadata();
        if (count($metadatas) > 0) {
            $tags = $tagsWriter->convert($metadatas, $tagRules);
            if (!empty($tags)) {
                $activityTags = [
                    'reference' => $activityReference,
                    'tags' => $tags
                ];
            }
        }

        // Build item reference and item tags JSON
        $itemReferences = [];
        $itemsTags = [];
        foreach ($manifest->getResources() as $resource) {
            // Just add `item` resource as items, and leave css and any other resources alone
            if ($resource->getType() === 'imsqti_item_xmlv2p1') {
                /** @var Resource $resource */
                $itemReference    = $resource->getIdentifier();
                $itemReferences[] = $itemReference;
                $tags = $tagsWriter->convert($resource->getMetadata(), $tagRules);
                if (!empty($tags)) {
                    $itemsTags[]      = [
                        'reference' => $itemReference,
                        'tags' => $tags
                    ];
                }
            }
        }

        // Build activity JSON
        $activity = [
            'reference' => $activityReference,
            'data' => [
                'items' => $itemReferences
            ],
            'status' => 'published'
        ];

        // Obvious here that these `items` hasn't and wouldn't be validated against
        // Should do it later by the function that calls this
        return [$activity, $activityTags, $itemsTags];
    }
}
