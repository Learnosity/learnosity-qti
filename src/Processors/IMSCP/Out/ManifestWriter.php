<?php

namespace LearnosityQti\Processors\IMSCP\Out;

use LearnosityQti\Processors\IMSCP\Entities\Manifest;

class ManifestWriter
{
    public function convert(Manifest $manifest) {

        // Let's map package metadata as activity tags
        // We can write custom replacer or remover to fix the messy `identifier:catalog:` afterwards
        $metadata = $manifest->getMetadata();
        $activities = []; // TODO: Yes this need to be done
        $itemsTags = []; // TODO: Yes this need to be done

        return [
            'activities' => $activities,
            'activityTags' => $metadata,
            'itemTags' => $itemsTags,
        ];
    }
}
