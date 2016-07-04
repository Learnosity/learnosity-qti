<?php

namespace LearnosityQti\Entities\QuestionTypes;

use LearnosityQti\Entities\BaseQuestionTypeAttribute;

/**
 * This class is auto-generated based on Schemas API and you should not modify its content
 * Metadata: {"responses":"v2.86.0","feedback":"v2.71.0","features":"v2.84.0"}
 */
class numberline_validation_valid_response extends BaseQuestionTypeAttribute
{
    protected $score;
    protected $value;

    public function __construct()
    {
    }

    /**
     * Get Score \
     * Score for this valid response. \
     *
     * @return number $score \
     */
    public function get_score()
    {
        return $this->score;
    }

    /**
     * Set Score \
     * Score for this valid response. \
     *
     * @param number $score \
     */
    public function set_score($score)
    {
        $this->score = $score;
    }

    /**
     * Get Value \
     * An array containing objects defining the correct position for a given point, e.g. {point: '3', position: '3'} \
     *
     * @return array $value \
     */
    public function get_value()
    {
        return $this->value;
    }

    /**
     * Set Value \
     * An array containing objects defining the correct position for a given point, e.g. {point: '3', position: '3'} \
     *
     * @param array $value \
     */
    public function set_value(array $value)
    {
        $this->value = $value;
    }


}

