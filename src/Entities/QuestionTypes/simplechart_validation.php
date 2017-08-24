<?php

namespace LearnosityQti\Entities\QuestionTypes;

use LearnosityQti\Entities\BaseQuestionTypeAttribute;

/**
* This class is auto-generated based on Schemas API and you should not modify its content
* Metadata: {"responses":"v2.108.0","feedback":"v2.71.0","features":"v2.107.0"}
*/
class simplechart_validation extends BaseQuestionTypeAttribute {
    protected $allow_negative_scores;
    protected $penalty;
    protected $min_score_if_attempted;
    protected $scoring_type;
    protected $unscored;
    protected $valid_response;
    protected $threshold;
    protected $ignore_order;
    protected $rounding;
    
    public function __construct(
            )
    {
            }

    /**
    * Get Allow negative scores \
    * Negative scores will be normalised to zero by default. Allowing negative scores, on the other hand, means that the score
	 can drop below zero when penalties are applied. \
    * @return boolean $allow_negative_scores \
    */
    public function get_allow_negative_scores() {
        return $this->allow_negative_scores;
    }

    /**
    * Set Allow negative scores \
    * Negative scores will be normalised to zero by default. Allowing negative scores, on the other hand, means that the score
	 can drop below zero when penalties are applied. \
    * @param boolean $allow_negative_scores \
    */
    public function set_allow_negative_scores ($allow_negative_scores) {
        $this->allow_negative_scores = $allow_negative_scores;
    }

    /**
    * Get Penalty point(s) \
    * Value indicating the marks deducted for an incorrect response. \
    * @return number $penalty \
    */
    public function get_penalty() {
        return $this->penalty;
    }

    /**
    * Set Penalty point(s) \
    * Value indicating the marks deducted for an incorrect response. \
    * @param number $penalty \
    */
    public function set_penalty ($penalty) {
        $this->penalty = $penalty;
    }

    /**
    * Get Minimum score if attempted \
    * Positive value indicating the minimum score if a student attempted the question. \
    * @return number $min_score_if_attempted \
    */
    public function get_min_score_if_attempted() {
        return $this->min_score_if_attempted;
    }

    /**
    * Set Minimum score if attempted \
    * Positive value indicating the minimum score if a student attempted the question. \
    * @param number $min_score_if_attempted \
    */
    public function set_min_score_if_attempted ($min_score_if_attempted) {
        $this->min_score_if_attempted = $min_score_if_attempted;
    }

    /**
    * Get Scoring type \
    * The way in which marks are distributed for the question. <ul><li><strong>Exact Match</strong> - All parts of the questio
	n must be answered correctly to receive a mark.</li><li><strong>Partial Match Per Response</strong> - Each correct respo
	nse element will be awarded an individual score.</li><li><strong>Partial Match</strong> - Each correct response element 
	will be scored individually, and the overall question score will be divided between responses.</li></ul> \
    * @return string $scoring_type ie. exactMatch  \
    */
    public function get_scoring_type() {
        return $this->scoring_type;
    }

    /**
    * Set Scoring type \
    * The way in which marks are distributed for the question. <ul><li><strong>Exact Match</strong> - All parts of the questio
	n must be answered correctly to receive a mark.</li><li><strong>Partial Match Per Response</strong> - Each correct respo
	nse element will be awarded an individual score.</li><li><strong>Partial Match</strong> - Each correct response element 
	will be scored individually, and the overall question score will be divided between responses.</li></ul> \
    * @param string $scoring_type ie. exactMatch  \
    */
    public function set_scoring_type ($scoring_type) {
        $this->scoring_type = $scoring_type;
    }

    /**
    * Get Unscored/Practice usage \
    * When enabled, this option will remove all scoring from the question. This is useful for creating practice questions. \
    * @return boolean $unscored \
    */
    public function get_unscored() {
        return $this->unscored;
    }

    /**
    * Set Unscored/Practice usage \
    * When enabled, this option will remove all scoring from the question. This is useful for creating practice questions. \
    * @param boolean $unscored \
    */
    public function set_unscored ($unscored) {
        $this->unscored = $unscored;
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
    * Get Ignore order \
    * Boolean value indicating whether the points order should be ignored or not. \
    * @return boolean $ignore_order \
    */
    public function get_ignore_order() {
        return $this->ignore_order;
    }

    /**
    * Set Ignore order \
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

