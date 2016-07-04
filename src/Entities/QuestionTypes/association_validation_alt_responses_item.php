<?php

namespace LearnosityQti\Entities\QuestionTypes;

use LearnosityQti\Entities\BaseQuestionTypeAttribute;

/**
 * This class is auto-generated based on Schemas API and you should not modify its content
 * Metadata: {"responses":"v2.86.0","feedback":"v2.71.0","features":"v2.84.0"}
 */
class association_validation_alt_responses_item extends BaseQuestionTypeAttribute
{
    protected $score;
    protected $value;

    public function __construct()
    {
    }

    /**
     * Get Score \
     * Score for this alternate response. \
     *
     * @return number $score \
     */
    public function get_score()
    {
        return $this->score;
    }

    /**
     * Set Score \
     * Score for this alternate response. \
     *
     * @param number $score \
     */
    public function set_score($score)
    {
        $this->score = $score;
    }

    /**
     * Get value \
     * Alternate response. \
     *
     * @return array $value \
     */
    public function get_value()
    {
        return $this->value;
    }

    /**
     * Set value \
     * Alternate response. \
     *
     * @param array $value \
     */
    public function set_value(array $value)
    {
        $this->value = $value;
    }


}

