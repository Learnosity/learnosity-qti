<?php

namespace Learnosity\Entities\QuestionTypes;

use Learnosity\Entities\BaseQuestionTypeAttribute;

/**
* This class is auto-generated based on Schemas API and you should not modify its content
* Metadata: {"responses":"v2.68.0","feedback":"v2.35.0","features":"v2.68.0"}
*/
class tokenhighlight_metadata extends BaseQuestionTypeAttribute {
    protected $distractor_rationale;
    protected $rubric_reference;
    protected $sample_answer;
    protected $acknowledgements;
    protected $distractor_rationale_response_level;
    
    public function __construct(
            )
    {
            }

    /**
    * Get Distractor Rationale \
    * Used for storing the distractor rationales that map to responses in the same order for use/rendering by the host environ
	ment. \
    * @return string $distractor_rationale \
    */
    public function get_distractor_rationale() {
        return $this->distractor_rationale;
    }

    /**
    * Set Distractor Rationale \
    * Used for storing the distractor rationales that map to responses in the same order for use/rendering by the host environ
	ment. \
    * @param string $distractor_rationale \
    */
    public function set_distractor_rationale ($distractor_rationale) {
        $this->distractor_rationale = $distractor_rationale;
    }

    /**
    * Get Rubric Reference \
    * A unique identifier for the rubric to be used with the question - defaults to course rubric if assigned in activity \
    * @return string $rubric_reference \
    */
    public function get_rubric_reference() {
        return $this->rubric_reference;
    }

    /**
    * Set Rubric Reference \
    * A unique identifier for the rubric to be used with the question - defaults to course rubric if assigned in activity \
    * @param string $rubric_reference \
    */
    public function set_rubric_reference ($rubric_reference) {
        $this->rubric_reference = $rubric_reference;
    }

    /**
    * Get Sample Answer \
    * A sample answer to be displayed on the Learnosity dashboard. HTML is supported. \
    * @return string $sample_answer \
    */
    public function get_sample_answer() {
        return $this->sample_answer;
    }

    /**
    * Set Sample Answer \
    * A sample answer to be displayed on the Learnosity dashboard. HTML is supported. \
    * @param string $sample_answer \
    */
    public function set_sample_answer ($sample_answer) {
        $this->sample_answer = $sample_answer;
    }

    /**
    * Get Acknowledgements \
    * References for any text passages, documents, images etc. used in the question. \
    * @return string $acknowledgements \
    */
    public function get_acknowledgements() {
        return $this->acknowledgements;
    }

    /**
    * Set Acknowledgements \
    * References for any text passages, documents, images etc. used in the question. \
    * @param string $acknowledgements \
    */
    public function set_acknowledgements ($acknowledgements) {
        $this->acknowledgements = $acknowledgements;
    }

    /**
    * Get Distractor Rationale per response \
    * Used for storing the distractor rationales that map to responses in the same order for use/rendering by the host environ
	ment. \
    * @return string/number $distractor_rationale_response_level \
    */
    public function get_distractor_rationale_response_level() {
        return $this->distractor_rationale_response_level;
    }

    /**
    * Set Distractor Rationale per response \
    * Used for storing the distractor rationales that map to responses in the same order for use/rendering by the host environ
	ment. \
    * @param string/number $distractor_rationale_response_level \
    */
    public function set_distractor_rationale_response_level ($distractor_rationale_response_level) {
        $this->distractor_rationale_response_level = $distractor_rationale_response_level;
    }

    
}

