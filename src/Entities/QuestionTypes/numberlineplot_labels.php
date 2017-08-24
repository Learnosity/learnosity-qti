<?php

namespace LearnosityQti\Entities\QuestionTypes;

use LearnosityQti\Entities\BaseQuestionTypeAttribute;

/**
* This class is auto-generated based on Schemas API and you should not modify its content
* Metadata: {"responses":"v2.108.0","feedback":"v2.71.0","features":"v2.107.0"}
*/
class numberlineplot_labels extends BaseQuestionTypeAttribute {
    protected $show;
    protected $show_min;
    protected $show_max;
    
    public function __construct(
            )
    {
            }

    /**
    * Get Show labels \
    * Whether to show labels on the line or not (only when tick is shown) \
    * @return boolean $show \
    */
    public function get_show() {
        return $this->show;
    }

    /**
    * Set Show labels \
    * Whether to show labels on the line or not (only when tick is shown) \
    * @param boolean $show \
    */
    public function set_show ($show) {
        $this->show = $show;
    }

    /**
    * Get Show min \
    * Whether to show min label on the line or not (only when tick is shown) \
    * @return boolean $show_min \
    */
    public function get_show_min() {
        return $this->show_min;
    }

    /**
    * Set Show min \
    * Whether to show min label on the line or not (only when tick is shown) \
    * @param boolean $show_min \
    */
    public function set_show_min ($show_min) {
        $this->show_min = $show_min;
    }

    /**
    * Get Show max \
    * Whether to show max label on the line or not (only when tick is shown) \
    * @return boolean $show_max \
    */
    public function get_show_max() {
        return $this->show_max;
    }

    /**
    * Set Show max \
    * Whether to show max label on the line or not (only when tick is shown) \
    * @param boolean $show_max \
    */
    public function set_show_max ($show_max) {
        $this->show_max = $show_max;
    }

    
}

