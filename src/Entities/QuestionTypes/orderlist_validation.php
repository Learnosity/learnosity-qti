<?php

namespace LearnosityQti\Entities\QuestionTypes;

use LearnosityQti\Entities\BaseQuestionTypeAttribute;

/**
* This class is auto-generated based on Schemas API and you should not modify its content
* Metadata: {"responses":"v2.84.0","feedback":"v2.71.0","features":"v2.84.0"}
*/
class orderlist_validation extends BaseQuestionTypeAttribute {
    protected $penalty;
    protected $scoring_type;
    protected $valid_response;
    protected $alt_responses;
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
    * Defines the scoring approach used. Possible options:<br><strong>'exactMatch'</strong>: Exact Match - Student must answer
	 all parts of the question correctly to receive a score<br><strong>'partialMatchV2'</strong>: Partial Match - Each respo
	nse entity will be scored individually with marks awarded if responses are in the correct position. The overall question
	 score will be divided between the response items<br><strong>'partialMatch'</strong>: Partial Match Per Response - Each 
	response entity will be scored individually with marks awarded if responses are in the correct position. Each response e
	ntity will be awarded an individual score<br><strong>'partialMatchPairwise'</strong>: Partial Pairwise Per Response - En
	tities are compared in pairs to determine the score \
    * @return string $scoring_type ie. exactMatch, partialMatch, partialMatchV2, partialMatchPairwise  \
    */
    public function get_scoring_type() {
        return $this->scoring_type;
    }

    /**
    * Set Scoring type \
    * Defines the scoring approach used. Possible options:<br><strong>'exactMatch'</strong>: Exact Match - Student must answer
	 all parts of the question correctly to receive a score<br><strong>'partialMatchV2'</strong>: Partial Match - Each respo
	nse entity will be scored individually with marks awarded if responses are in the correct position. The overall question
	 score will be divided between the response items<br><strong>'partialMatch'</strong>: Partial Match Per Response - Each 
	response entity will be scored individually with marks awarded if responses are in the correct position. Each response e
	ntity will be awarded an individual score<br><strong>'partialMatchPairwise'</strong>: Partial Pairwise Per Response - En
	tities are compared in pairs to determine the score \
    * @param string $scoring_type ie. exactMatch, partialMatch, partialMatchV2, partialMatchPairwise  \
    */
    public function set_scoring_type ($scoring_type) {
        $this->scoring_type = $scoring_type;
    }

    /**
    * Get Valid response \
    * An object containing the valid response score and value. \
    * @return orderlist_validation_valid_response $valid_response \
    */
    public function get_valid_response() {
        return $this->valid_response;
    }

    /**
    * Set Valid response \
    * An object containing the valid response score and value. \
    * @param orderlist_validation_valid_response $valid_response \
    */
    public function set_valid_response (orderlist_validation_valid_response $valid_response) {
        $this->valid_response = $valid_response;
    }

    /**
    * Get Alternate responses \
    * An array of alternate response objects used for giving a supplementary value if the question was not valid \
    * @return array $alt_responses \
    */
    public function get_alt_responses() {
        return $this->alt_responses;
    }

    /**
    * Set Alternate responses \
    * An array of alternate response objects used for giving a supplementary value if the question was not valid \
    * @param array $alt_responses \
    */
    public function set_alt_responses (array $alt_responses) {
        $this->alt_responses = $alt_responses;
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

