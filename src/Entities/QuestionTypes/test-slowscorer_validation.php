<?php

namespace LearnosityQti\Entities\QuestionTypes;

use LearnosityQti\Entities\BaseQuestionTypeAttribute;

/**
* This class is auto-generated based on Schemas API and you should not modify its content
* Metadata: {"responses":"v2.108.0","feedback":"v2.71.0","features":"v2.107.0"}
*/
class test-slowscorer_validation extends BaseQuestionTypeAttribute {
    protected $max_score;
    protected $min_score_if_attempted;
    protected $unscored;
    
    public function __construct(
            )
    {
            }

    /**
    * Get Max score \
    * The highest score a marker can award to this question response. \
    * @return number $max_score \
    */
    public function get_max_score() {
        return $this->max_score;
    }

    /**
    * Set Max score \
    * The highest score a marker can award to this question response. \
    * @param number $max_score \
    */
    public function set_max_score ($max_score) {
        $this->max_score = $max_score;
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

    
}

