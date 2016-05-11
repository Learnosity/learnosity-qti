<?php

namespace LearnosityQti\Entities\QuestionTypes;

use LearnosityQti\Entities\BaseQuestionTypeAttribute;

/**
* This class is auto-generated based on Schemas API and you should not modify its content
* Metadata: {"responses":"v2.84.0","feedback":"v2.71.0","features":"v2.84.0"}
*/
class formulaessay_validation extends BaseQuestionTypeAttribute {
    protected $max_score;
    
    public function __construct(
            )
    {
            }

    /**
    * Get Max Score \
    * The highest score a marker can award to this question response. \
    * @return number $max_score \
    */
    public function get_max_score() {
        return $this->max_score;
    }

    /**
    * Set Max Score \
    * The highest score a marker can award to this question response. \
    * @param number $max_score \
    */
    public function set_max_score ($max_score) {
        $this->max_score = $max_score;
    }

    
}

