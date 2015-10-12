<?php

namespace LearnosityQti\Entities\QuestionTypes;

use LearnosityQti\Entities\BaseQuestionTypeAttribute;

/**
* This class is auto-generated based on Schemas API and you should not modify its content
* Metadata: {"responses":"v2.72.0","feedback":"v2.71.0","features":"v2.72.0"}
*/
class simplechart_chart_data_data_item extends BaseQuestionTypeAttribute {
    protected $x;
    protected $y;
    protected $interactive;
    
    public function __construct(
            )
    {
            }

    /**
    * Get Label \
    * Point label \
    * @return string $x \
    */
    public function get_x() {
        return $this->x;
    }

    /**
    * Set Label \
    * Point label \
    * @param string $x \
    */
    public function set_x ($x) {
        $this->x = $x;
    }

    /**
    * Get Value \
    * Point value \
    * @return number $y \
    */
    public function get_y() {
        return $this->y;
    }

    /**
    * Set Value \
    * Point value \
    * @param number $y \
    */
    public function set_y ($y) {
        $this->y = $y;
    }

    /**
    * Get Interactive \
    * Interactive Point \
    * @return boolean $interactive \
    */
    public function get_interactive() {
        return $this->interactive;
    }

    /**
    * Set Interactive \
    * Interactive Point \
    * @param boolean $interactive \
    */
    public function set_interactive ($interactive) {
        $this->interactive = $interactive;
    }

    
}

