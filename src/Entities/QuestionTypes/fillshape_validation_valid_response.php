<?php

namespace LearnosityQti\Entities\QuestionTypes;

use LearnosityQti\Entities\BaseQuestionTypeAttribute;

/**
* This class is auto-generated based on Schemas API and you should not modify its content
* Metadata: {"responses":"v2.107.0","feedback":"v2.71.0","features":"v2.107.0"}
*/
class fillshape_validation_valid_response extends BaseQuestionTypeAttribute {
    protected $score;
    protected $method;
    
    public function __construct(
            )
    {
            }

    /**
    * Get Score \
    * Score awarded for the correct response(s). \
    * @return number $score \
    */
    public function get_score() {
        return $this->score;
    }

    /**
    * Set Score \
    * Score awarded for the correct response(s). \
    * @param number $score \
    */
    public function set_score ($score) {
        $this->score = $score;
    }

    /**
    * Get Method \
    * Define the scoring method. \
    * @return string $method ie. countByValue, countByResponse  \
    */
    public function get_method() {
        return $this->method;
    }

    /**
    * Set Method \
    * Define the scoring method. \
    * @param string $method ie. countByValue, countByResponse  \
    */
    public function set_method ($method) {
        $this->method = $method;
    }

    
}

