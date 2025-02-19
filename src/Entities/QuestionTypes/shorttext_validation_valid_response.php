<?php

namespace LearnosityQti\Entities\QuestionTypes;

use LearnosityQti\Entities\BaseQuestionTypeAttribute;

/**
 * This class is auto-generated based on Schemas API and you should not modify its content
 * Metadata: {"responses":"v2.228.0","feedback":"v2.71.0","features":"v2.208.0"}
 */
class shorttext_validation_valid_response extends BaseQuestionTypeAttribute {
    protected $score;
    protected $matching_rule;
    protected $value;
    
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
     * Get Allow \
     * Matching rule that will be used to evaluate student's input \
     * @return string $matching_rule \
     */
    public function get_matching_rule() {
        return $this->matching_rule;
    }

    /**
     * Set Allow \
     * Matching rule that will be used to evaluate student's input \
     * @param string $matching_rule \
     */
    public function set_matching_rule ($matching_rule) {
        $this->matching_rule = $matching_rule;
    }

    /**
     * Get Value \
     *  \
     * @return string $value \
     */
    public function get_value() {
        return $this->value;
    }

    /**
     * Set Value \
     *  \
     * @param string $value \
     */
    public function set_value ($value) {
        $this->value = $value;
    }

    
}

