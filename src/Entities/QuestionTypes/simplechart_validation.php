<?php

namespace LearnosityQti\Entities\QuestionTypes;

use LearnosityQti\Entities\BaseQuestionTypeAttribute;

/**
* This class is auto-generated based on Schemas API and you should not modify its content
* Metadata: {"responses":"v2.84.0","feedback":"v2.71.0","features":"v2.84.0"}
*/
class simplechart_validation extends BaseQuestionTypeAttribute {
    protected $penalty;
    protected $scoring_type;
    protected $valid_response;
    protected $threshold;
    protected $ignore_order;
    protected $rounding;
    
    public function __construct(
            )
    {
            }

    /**
    * Get Penalty score \
    * Positive value indicating the marks deducted for an incorrect response. \
    * @return number $penalty \
    */
    public function get_penalty() {
        return $this->penalty;
    }

    /**
    * Set Penalty score \
    * Positive value indicating the marks deducted for an incorrect response. \
    * @param number $penalty \
    */
    public function set_penalty ($penalty) {
        $this->penalty = $penalty;
    }

    /**
    * Get Scoring type \
    * Defines the scoring approach used. Possible options:<br><strong>'exactMatch'</strong>: Exact Match - Entire response mus
	t match exactly \
    * @return string $scoring_type ie. exactMatch  \
    */
    public function get_scoring_type() {
        return $this->scoring_type;
    }

    /**
    * Set Scoring type \
    * Defines the scoring approach used. Possible options:<br><strong>'exactMatch'</strong>: Exact Match - Entire response mus
	t match exactly \
    * @param string $scoring_type ie. exactMatch  \
    */
    public function set_scoring_type ($scoring_type) {
        $this->scoring_type = $scoring_type;
    }

    /**
    * Get Valid response \
    * An object containing the valid response score and value. \
    * @return simplechart_validation_valid_response $valid_response \
    */
    public function get_valid_response() {
        return $this->valid_response;
    }

    /**
    * Set Valid response \
    * An object containing the valid response score and value. \
    * @param simplechart_validation_valid_response $valid_response \
    */
    public function set_valid_response (simplechart_validation_valid_response $valid_response) {
        $this->valid_response = $valid_response;
    }

    /**
    * Get Threshold \
    * Positive value indicating the correct value threshold. \
    * @return number $threshold \
    */
    public function get_threshold() {
        return $this->threshold;
    }

    /**
    * Set Threshold \
    * Positive value indicating the correct value threshold. \
    * @param number $threshold \
    */
    public function set_threshold ($threshold) {
        $this->threshold = $threshold;
    }

    /**
    * Get Ignore Order \
    * Boolean value indicating whether the points order should be ignored or not. \
    * @return boolean $ignore_order \
    */
    public function get_ignore_order() {
        return $this->ignore_order;
    }

    /**
    * Set Ignore Order \
    * Boolean value indicating whether the points order should be ignored or not. \
    * @param boolean $ignore_order \
    */
    public function set_ignore_order ($ignore_order) {
        $this->ignore_order = $ignore_order;
    }

    /**
    * Get Rounding \
    * Method for rounding the score after it has been calculated as a fraction of score. <strong>none</strong>: No rounding ap
	plied, <strong>floor</strong>: Rounded down to the nearest whole number. \
    * @return string $rounding \
    */
    public function get_rounding() {
        return $this->rounding;
    }

    /**
    * Set Rounding \
    * Method for rounding the score after it has been calculated as a fraction of score. <strong>none</strong>: No rounding ap
	plied, <strong>floor</strong>: Rounded down to the nearest whole number. \
    * @param string $rounding \
    */
    public function set_rounding ($rounding) {
        $this->rounding = $rounding;
    }

    
}

