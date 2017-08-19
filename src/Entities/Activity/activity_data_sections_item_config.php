<?php

namespace LearnosityQti\Entities\Activity;

use LearnosityQti\Entities\BaseQuestionTypeAttribute;

/**
* This class is auto-generated based on Schemas API and you should not modify its content
* Metadata: {"responses":"v2.107.0","feedback":"v2.71.0","features":"v2.107.0"}
*/
class activity_data_sections_item_config extends BaseQuestionTypeAttribute {
    protected $subtitle;
    
    public function __construct(
            )
    {
            }

    /**
    * Get subtitle \
    *  \
    * @return string $subtitle \
    */
    public function get_subtitle() {
        return $this->subtitle;
    }

    /**
    * Set subtitle \
    *  \
    * @param string $subtitle \
    */
    public function set_subtitle ($subtitle) {
        $this->subtitle = $subtitle;
    }

    
}

