<?php

namespace LearnosityQti\Entities\QuestionTypes;

use LearnosityQti\Entities\BaseQuestionTypeAttribute;

/**
* This class is auto-generated based on Schemas API and you should not modify its content
* Metadata: {"responses":"v2.108.0","feedback":"v2.71.0","features":"v2.107.0"}
*/
class simplechart_validation_valid_response_value_item extends BaseQuestionTypeAttribute {
    protected $x;
    protected $y;
    
    public function __construct(
            )
    {
            }

    /**
    * Get X axis image position \
    *  \
    * @return string $x \
    */
    public function get_x() {
        return $this->x;
    }

    /**
    * Set X axis image position \
    *  \
    * @param string $x \
    */
    public function set_x ($x) {
        $this->x = $x;
    }

    /**
    * Get Y axis image position \
    *  \
    * @return number $y \
    */
    public function get_y() {
        return $this->y;
    }

    /**
    * Set Y axis image position \
    *  \
    * @param number $y \
    */
    public function set_y ($y) {
        $this->y = $y;
    }

    
}

