<?php

namespace LearnosityQti\Entities\QuestionTypes;

use LearnosityQti\Entities\BaseQuestionTypeAttribute;

/**
 * This class is auto-generated based on Schemas API and you should not modify its content
 * Metadata: {"responses":"v2.228.0","feedback":"v2.71.0","features":"v2.208.0"}
 */
class graphplotting_grid extends BaseQuestionTypeAttribute {
    protected $x_distance;
    protected $y_distance;
    
    public function __construct(
            )
    {
            }

    /**
     * Get X distance \
     * Distance between grid lines perpendicular to the X axis \
     * @return string/number $x_distance \
     */
    public function get_x_distance() {
        return $this->x_distance;
    }

    /**
     * Set X distance \
     * Distance between grid lines perpendicular to the X axis \
     * @param string/number $x_distance \
     */
    public function set_x_distance ($x_distance) {
        $this->x_distance = $x_distance;
    }

    /**
     * Get Y distance \
     * Distance between grid lines perpendicular to the Y axis \
     * @return string/number $y_distance \
     */
    public function get_y_distance() {
        return $this->y_distance;
    }

    /**
     * Set Y distance \
     * Distance between grid lines perpendicular to the Y axis \
     * @param string/number $y_distance \
     */
    public function set_y_distance ($y_distance) {
        $this->y_distance = $y_distance;
    }

    
}

