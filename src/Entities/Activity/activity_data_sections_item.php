<?php

namespace LearnosityQti\Entities\Activity;

use LearnosityQti\Entities\BaseQuestionTypeAttribute;

/**
* This class is auto-generated based on Schemas API and you should not modify its content
* Metadata: {"responses":"v2.107.0","feedback":"v2.71.0","features":"v2.107.0"}
*/
class activity_data_sections_item extends BaseQuestionTypeAttribute {
    protected $items;
    protected $config;
    
    public function __construct(
            )
    {
            }

    /**
    * Get items \
    *  \
    * @return array $items \
    */
    public function get_items() {
        return $this->items;
    }

    /**
    * Set items \
    *  \
    * @param array $items \
    */
    public function set_items (array $items) {
        $this->items = $items;
    }

    /**
    * Get config \
    *  \
    * @return activity_data_sections_item_config $config \
    */
    public function get_config() {
        return $this->config;
    }

    /**
    * Set config \
    *  \
    * @param activity_data_sections_item_config $config \
    */
    public function set_config (activity_data_sections_item_config $config) {
        $this->config = $config;
    }

    
}

