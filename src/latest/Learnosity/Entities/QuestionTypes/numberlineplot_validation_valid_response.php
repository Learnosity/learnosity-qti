<?php

namespace Learnosity\Entities\QuestionTypes;

use Learnosity\Entities\BaseQuestionTypeAttribute;

/**
* This class is auto-generated based on Schemas API and you should not modify its content
* Metadata: {"responses":"v2.68.0","feedback":"v2.35.0","features":"v2.68.0"}
*/
class numberlineplot_validation_valid_response extends BaseQuestionTypeAttribute {
    protected $score;
    protected $value;
    
    public function __construct(
            )
    {
            }

    /**
    * Get Score \
    * Score for this valid response. \
    * @return number $score \
    */
    public function get_score() {
        return $this->score;
    }

    /**
    * Set Score \
    * Score for this valid response. \
    * @param number $score \
    */
    public function set_score ($score) {
        $this->score = $score;
    }

    /**
    * Get Value \
    * An array containing objects defining the correct response for a given tool, e.g. response: { type: 'segment', point1: { 
	x: 0, y: 0 }, point2: { x: 0, y: 0 } } \
    * @return array $value \
    */
    public function get_value() {
        return $this->value;
    }

    /**
    * Set Value \
    * An array containing objects defining the correct response for a given tool, e.g. response: { type: 'segment', point1: { 
	x: 0, y: 0 }, point2: { x: 0, y: 0 } } \
    * @param array $value \
    */
    public function set_value (array $value) {
        $this->value = $value;
    }

    
}

