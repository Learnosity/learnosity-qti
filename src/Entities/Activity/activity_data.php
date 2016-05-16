<?php

namespace LearnosityQti\Entities\Activity;

use LearnosityQti\Entities\BaseQuestionTypeAttribute;

/**
* This class is auto-generated based on Schemas API and you should not modify its content
* Metadata: {"responses":"v2.84.0","feedback":"v2.71.0","features":"v2.84.0"}
*/
class activity_data extends BaseQuestionTypeAttribute {
    protected $config;
    
    public function __construct(
            )
    {
            }

    /**
    * Get config \
    *  \
    * @return activity_data_config $config \
    */
    public function get_config() {
        return $this->config;
    }

    /**
    * Set config \
    *  \
    * @param activity_data_config $config \
    */
    public function set_config (activity_data_config $config) {
        $this->config = $config;
    }

    
}

