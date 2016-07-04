<?php

namespace LearnosityQti\Processors\Learnosity\Out;

use LearnosityQti\Entities\Activity\activity;

class ActivityWriter
{
    public function convert(activity $activity)
    {
        $json = $activity->to_array();

        // Check whether `items` empty or not first
        if (!empty($json['items'])) {
            unset($json['sections']);
        } else {
            unset($json['items']);
        }
        
        return $json;
    }
}
