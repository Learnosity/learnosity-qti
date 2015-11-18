<?php

namespace LearnosityQti\Processors\IMSCP\Out;

use LearnosityQti\Processors\IMSCP\Entities\Manifest;
use LearnosityQti\Services\LogService;
use LearnosityQti\Utils\StringUtil;

class ManifestWriter
{
    public function convert(Manifest $manifest, array $rules = []) {
        $activityReference = $manifest->getIdentifier();
        $tagsWriter = new TagsWriter();

        // Does not handle submanifest tyvm!
        if (!empty($manifest->getManifest())) {
            LogService::log('Does not handle sub-manifest element thus it is ignored');
        }

        // Atm, we only have tags rules and this need to be validated and user shall be provided with a nice error message
        // TODO: Validation need to be done in future
        $tagRules = isset($rules['tags']) ? $rules['tags'] : [];

        // Let's map package metadatas as activity tags
        // We can write custom replacer or remover to fix the messy `identifier:catalog:` afterwards
        $activityTags = [];
        $metadatas = $manifest->getMetadata();
        if (!empty($metadatas)) {
            $tags = $tagsWriter->convert($metadatas, $tagRules);
            if (!empty($tags)) {
                $activityTags = [
                    'reference' => $activityReference,
                    'tags' => $tags
                ];
            }
        }

        $itemReferences = [];
        $itemsTags = [];


        // Build item reference and item tags JSON
        $organisations = $manifest->getOrganizations();
        if (!empty($organisations)) {
            foreach ($organisations as $organisation) {
                foreach ($organisation->getItems() as $item) {
                    $itemReferences[] = $item->getIdentifier();
                }
            }
        }
        // Build item reference and item tags JSON
        $resources = $manifest->getResources();
        if (!empty($resources)) {
            foreach ($resources as $resource) {
                // Just add `item` resource as items, and leave css and any other resources alone
                if (StringUtil::startsWith($resource->getType(), 'imsqti_item')) {
                    /** @var Resource $resource */
                    $itemReference = $resource->getIdentifier();
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
        }

        // Build activity JSON
        $activity = [
            'reference' => $activityReference,
            'data' => [
                'items' => array_values(array_unique($itemReferences)) // Just mash them up together
            ],
            'status' => 'published'
        ];

        // Obvious here that these `items` hasn't and wouldn't be validated against
        // Should do it later by the function that calls this
        return [$activity, $activityTags, $itemsTags];
    }
}
