<?php

namespace LearnosityQti\Entities\QuestionTypes;

use LearnosityQti\Entities\BaseQuestionTypeAttribute;

/**
* This class is auto-generated based on Schemas API and you should not modify its content
* Metadata: {"responses":"v2.107.0","feedback":"v2.71.0","features":"v2.107.0"}
*/
class fillshape_shape_parts_item extends BaseQuestionTypeAttribute {
    protected $value;
    
    public function __construct(
            )
    {
            }

    /**
    * Get Value \
    *  \
    * @return string $value \
    */
    public function get_value() {
        return $this->value;
    }

    /**
    * Set Value \
    *  \
    * @param string $value \
    */
    public function set_value ($value) {
        $this->value = $value;
    }

    
}

