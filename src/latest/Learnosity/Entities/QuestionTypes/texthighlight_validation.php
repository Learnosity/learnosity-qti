<?php

namespace Learnosity\Entities\QuestionTypes;

use Learnosity\Entities\BaseQuestionTypeAttribute;

/**
 * This class is auto-generated based on Schemas API and you should not modify its content
 * Metadata: {"responses":"v2.72.0","feedback":"v2.71.0","features":"v2.72.0"}
 */
class texthighlight_validation extends BaseQuestionTypeAttribute
{
    protected $partial_scoring;
    protected $show_partial_ui;
    protected $valid_score;
    protected $penalty_score;

    public function __construct()
    {
    }

    /**
     * Get Partial scoring \
     * Determines if a partial score will be rewarded when not all responses are correct. \
     *
     * @return boolean $partial_scoring \
     */
    public function get_partial_scoring()
    {
        return $this->partial_scoring;
    }

    /**
     * Set Partial scoring \
     * Determines if a partial score will be rewarded when not all responses are correct. \
     *
     * @param boolean $partial_scoring \
     */
    public function set_partial_scoring($partial_scoring)
    {
        $this->partial_scoring = $partial_scoring;
    }

    /**
     * Get Show partial UI \
     * When partial_scoring is false this determines if the valid UI is shown for each response or only for the whole question \
     *
     * @return boolean $show_partial_ui \
     */
    public function get_show_partial_ui()
    {
        return $this->show_partial_ui;
    }

    /**
     * Set Show partial UI \
     * When partial_scoring is false this determines if the valid UI is shown for each response or only for the whole question \
     *
     * @param boolean $show_partial_ui \
     */
    public function set_show_partial_ui($show_partial_ui)
    {
        $this->show_partial_ui = $show_partial_ui;
    }

    /**
     * Get Valid score \
     * The score for a single correct selection. \
     *
     * @return number $valid_score \
     */
    public function get_valid_score()
    {
        return $this->valid_score;
    }

    /**
     * Set Valid score \
     * The score for a single correct selection. \
     *
     * @param number $valid_score \
     */
    public function set_valid_score($valid_score)
    {
        $this->valid_score = $valid_score;
    }

    /**
     * Get Penalty score \
     * Negative value indicating the marks deducted for an incorrect response. \
     *
     * @return number $penalty_score \
     */
    public function get_penalty_score()
    {
        return $this->penalty_score;
    }

    /**
     * Set Penalty score \
     * Negative value indicating the marks deducted for an incorrect response. \
     *
     * @param number $penalty_score \
     */
    public function set_penalty_score($penalty_score)
    {
        $this->penalty_score = $penalty_score;
    }


}

