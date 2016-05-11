<?php

namespace LearnosityQti\Entities\QuestionTypes;

use LearnosityQti\Entities\BaseQuestionTypeAttribute;

/**
* This class is auto-generated based on Schemas API and you should not modify its content
* Metadata: {"responses":"v2.84.0","feedback":"v2.71.0","features":"v2.84.0"}
*/
class classification_validation_valid_response extends BaseQuestionTypeAttribute {
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
    * A 3 dimensional array that consists of: An array of different valid responses (in most cases there will only be one vali
	d response), each valid response is an array that contains an entry for each cell. each cell is an array of response ind
	ex's. The response index is based on the index value in the possible_responses attribute. \
    * @return array $value \
    */
    public function get_value() {
        return $this->value;
    }

    /**
    * Set Value \
    * A 3 dimensional array that consists of: An array of different valid responses (in most cases there will only be one vali
	d response), each valid response is an array that contains an entry for each cell. each cell is an array of response ind
	ex's. The response index is based on the index value in the possible_responses attribute. \
    * @param array $value \
    */
    public function set_value (array $value) {
        $this->value = $value;
    }

    
}

