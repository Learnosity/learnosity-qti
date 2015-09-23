<?php

namespace Learnosity\Entities\QuestionTypes;

use Learnosity\Entities\BaseQuestionTypeAttribute;

/**
 * This class is auto-generated based on Schemas API and you should not modify its content
 * Metadata: {"responses":"v2.72.0","feedback":"v2.71.0","features":"v2.72.0"}
 */
class numberlineplot_validation extends BaseQuestionTypeAttribute
{
    protected $penalty;
    protected $scoring_type;
    protected $valid_response;
    protected $alt_responses;
    protected $rounding;

    public function __construct()
    {
    }

    /**
     * Get Penalty score \
     * Positive value indicating the marks deducted for an incorrect response. \
     *
     * @return number $penalty \
     */
    public function get_penalty()
    {
        return $this->penalty;
    }

    /**
     * Set Penalty score \
     * Positive value indicating the marks deducted for an incorrect response. \
     *
     * @param number $penalty \
     */
    public function set_penalty($penalty)
    {
        $this->penalty = $penalty;
    }

    /**
     * Get Scoring type \
     * Defines the scoring approach used.<br><strong>Exact Match</strong>: Entire response must match exactly<br><strong>Partia
     * l Match</strong>: A relative part of the score will be given for each correct response area<br><strong>Partial Match per
     * response</strong>: Cumulative Score value will be given for each correct response area \
     *
     * @return string $scoring_type ie. exactMatch, partialMatch, partialMatchV2  \
     */
    public function get_scoring_type()
    {
        return $this->scoring_type;
    }

    /**
     * Set Scoring type \
     * Defines the scoring approach used.<br><strong>Exact Match</strong>: Entire response must match exactly<br><strong>Partia
     * l Match</strong>: A relative part of the score will be given for each correct response area<br><strong>Partial Match per
     * response</strong>: Cumulative Score value will be given for each correct response area \
     *
     * @param string $scoring_type ie. exactMatch, partialMatch, partialMatchV2  \
     */
    public function set_scoring_type($scoring_type)
    {
        $this->scoring_type = $scoring_type;
    }

    /**
     * Get Valid response \
     * An object containing the valid response score and value. \
     *
     * @return numberlineplot_validation_valid_response $valid_response \
     */
    public function get_valid_response()
    {
        return $this->valid_response;
    }

    /**
     * Set Valid response \
     * An object containing the valid response score and value. \
     *
     * @param numberlineplot_validation_valid_response $valid_response \
     */
    public function set_valid_response(numberlineplot_validation_valid_response $valid_response)
    {
        $this->valid_response = $valid_response;
    }

    /**
     * Get Alternate responses \
     * An array of alternate response objects used for giving a supplementary value if the question was not valid \
     *
     * @return array $alt_responses \
     */
    public function get_alt_responses()
    {
        return $this->alt_responses;
    }

    /**
     * Set Alternate responses \
     * An array of alternate response objects used for giving a supplementary value if the question was not valid \
     *
     * @param array $alt_responses \
     */
    public function set_alt_responses(array $alt_responses)
    {
        $this->alt_responses = $alt_responses;
    }

    /**
     * Get Rounding \
     * Method for rounding the score after it has been calculated as a fraction of score. <strong>none</strong>: No rounding ap
     * plied, <strong>floor</strong>: Rounded down to the nearest whole number. \
     *
     * @return string $rounding \
     */
    public function get_rounding()
    {
        return $this->rounding;
    }

    /**
     * Set Rounding \
     * Method for rounding the score after it has been calculated as a fraction of score. <strong>none</strong>: No rounding ap
     * plied, <strong>floor</strong>: Rounded down to the nearest whole number. \
     *
     * @param string $rounding \
     */
    public function set_rounding($rounding)
    {
        $this->rounding = $rounding;
    }


}

