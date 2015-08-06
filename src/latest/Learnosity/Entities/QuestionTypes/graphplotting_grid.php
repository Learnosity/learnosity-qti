<?php

namespace Learnosity\Entities\QuestionTypes;

use Learnosity\Entities\BaseQuestionTypeAttribute;

/**
* This class is auto-generated based on Schemas API and you should not modify its content
* Metadata: {"responses":"v2.68.0","feedback":"v2.35.0","features":"v2.68.0"}
*/
class graphplotting_grid extends BaseQuestionTypeAttribute {
    protected $x_distance;
    protected $y_distance;
    
    public function __construct(
            )
    {
            }

    /**
    * Get x_distance \
    * Distance between grid lines perpendicular to the X axis \
    * @return number $x_distance \
    */
    public function get_x_distance() {
        return $this->x_distance;
    }

    /**
    * Set x_distance \
    * Distance between grid lines perpendicular to the X axis \
    * @param number $x_distance \
    */
    public function set_x_distance ($x_distance) {
        $this->x_distance = $x_distance;
    }

    /**
    * Get y_distance \
    * Distance between grid lines perpendicular to the Y axis \
    * @return number $y_distance \
    */
    public function get_y_distance() {
        return $this->y_distance;
    }

    /**
    * Set y_distance \
    * Distance between grid lines perpendicular to the Y axis \
    * @param number $y_distance \
    */
    public function set_y_distance ($y_distance) {
        $this->y_distance = $y_distance;
    }

    
}

