<?php

namespace Learnosity\Entities\QuestionTypes;

use Learnosity\Entities\BaseQuestionTypeAttribute;

/**
* This class is auto-generated based on Schemas API and you should not modify its content
* Metadata: {"responses":"v2.68.0","feedback":"v2.35.0","features":"v2.68.0"}
*/
class simplechart_validation_valid_response_value_item extends BaseQuestionTypeAttribute {
    protected $x;
    protected $y;
    
    public function __construct(
            )
    {
            }

    /**
    * Get X \
    *  \
    * @return string $x \
    */
    public function get_x() {
        return $this->x;
    }

    /**
    * Set X \
    *  \
    * @param string $x \
    */
    public function set_x ($x) {
        $this->x = $x;
    }

    /**
    * Get Y \
    *  \
    * @return number $y \
    */
    public function get_y() {
        return $this->y;
    }

    /**
    * Set Y \
    *  \
    * @param number $y \
    */
    public function set_y ($y) {
        $this->y = $y;
    }

    
}

