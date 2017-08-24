<?php

namespace LearnosityQti\Entities\QuestionTypes;

use LearnosityQti\Entities\BaseQuestionTypeAttribute;

/**
* This class is auto-generated based on Schemas API and you should not modify its content
* Metadata: {"responses":"v2.108.0","feedback":"v2.71.0","features":"v2.107.0"}
*/
class fillshape_possible_responses_item_image extends BaseQuestionTypeAttribute {
    protected $src;
    
    public function __construct(
            )
    {
            }

    /**
    * Get Add image \
    *  \
    * @return string $src \
    */
    public function get_src() {
        return $this->src;
    }

    /**
    * Set Add image \
    *  \
    * @param string $src \
    */
    public function set_src ($src) {
        $this->src = $src;
    }

    
}

