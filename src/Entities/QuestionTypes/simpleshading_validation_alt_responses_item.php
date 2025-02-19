<?php

namespace LearnosityQti\Entities\QuestionTypes;

use LearnosityQti\Entities\BaseQuestionTypeAttribute;

/**
 * This class is auto-generated based on Schemas API and you should not modify its content
 * Metadata: {"responses":"v2.228.0","feedback":"v2.71.0","features":"v2.208.0"}
 */
class simpleshading_validation_alt_responses_item extends BaseQuestionTypeAttribute {
    protected $score;
    protected $value;
    
    public function __construct(
            )
    {
            }

    /**
     * Get Score \
     * Score if the alternative response is correct. \
     * @return number $score \
     */
    public function get_score() {
        return $this->score;
    }

    /**
     * Set Score \
     * Score if the alternative response is correct. \
     * @param number $score \
     */
    public function set_score ($score) {
        $this->score = $score;
    }

    /**
     * Get Valid response \
     *  \
     * @return simpleshading_validation_alt_responses_item_value $value \
     */
    public function get_value() {
        return $this->value;
    }

    /**
     * Set Valid response \
     *  \
     * @param simpleshading_validation_alt_responses_item_value $value \
     */
    public function set_value (simpleshading_validation_alt_responses_item_value $value) {
        $this->value = $value;
    }

    
}

