<?php

namespace LearnosityQti\Entities\QuestionTypes;

use LearnosityQti\Entities\BaseQuestionTypeAttribute;

/**
* This class is auto-generated based on Schemas API and you should not modify its content
* Metadata: {"responses":"v2.108.0","feedback":"v2.71.0","features":"v2.107.0"}
*/
class mcq_metadata extends BaseQuestionTypeAttribute {
    protected $distractor_rationale;
    protected $distractor_rationale_scorer;
    protected $rubric_reference;
    protected $sample_answer;
    protected $acknowledgements;
    protected $distractor_rationale_response_level;
    protected $response_shuffle_seed;

    public function __construct(
            )
    {
            }

    /**
    * Get Distractor rationale \
    * Used to display individual response feedback/rationale to the student. \
    * @return string $distractor_rationale \
    */
    public function get_distractor_rationale() {
        return $this->distractor_rationale;
    }

    /**
    * Set Distractor rationale \
    * Used to display individual response feedback/rationale to the student. \
    * @param string $distractor_rationale \
    */
    public function set_distractor_rationale ($distractor_rationale) {
        $this->distractor_rationale = $distractor_rationale;
    }

    /**
    * Get Rubric reference \
    * A unique identifier for the rubric to be used with the question - defaults to course rubric if assigned in activity \
    * @return string $rubric_reference \
    */
    public function get_rubric_reference() {
        return $this->rubric_reference;
    }

    /**
    * Set Rubric reference \
    * A unique identifier for the rubric to be used with the question - defaults to course rubric if assigned in activity \
    * @param string $rubric_reference \
    */
    public function set_rubric_reference ($rubric_reference) {
        $this->rubric_reference = $rubric_reference;
    }

    /**
    * Get Sample answer \
    * A sample answer to be displayed on the Learnosity Reports API. HTML is supported. \
    * @return string $sample_answer \
    */
    public function get_sample_answer() {
        return $this->sample_answer;
    }

    /**
    * Set Sample answer \
    * A sample answer to be displayed on the Learnosity Reports API. HTML is supported. \
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
    * Get Distractors \
    * Used to display individual response feedback/rationale to the student. \
    * @return array $distractor_rationale_response_level \
    */
    public function get_distractor_rationale_response_level() {
        return $this->distractor_rationale_response_level;
    }

    /**
    * Set Distractors \
    * Used to display individual response feedback/rationale to the student. \
    * @param array $distractor_rationale_response_level \
    */
    public function set_distractor_rationale_response_level (array $distractor_rationale_response_level) {
        $this->distractor_rationale_response_level = $distractor_rationale_response_level;
    }

    /**
    * MPS: No idea what this is, adding getter and setter because it
    * throwing a dynamic property error.
    * @return string $distractor_rationale_scorer \
    */
    public function get_distractor_rationale_scorer() {
        return $this->distractor_rationale_scorer;
    }

    /**
    * MPS: No idea what this is, adding getter and setter because it
    * throwing a dynamic property error.
    * @param string $distractor_rationale_scorer \
    */
    public function set_distractor_rationale_scorer (string $distractor_rationale_scorer) {
        $this->distractor_rationale_scorer = $distractor_rationale_scorer;
    }

    /**
    * Get Response shuffle seed \
    * A seed to use to randomise the order of response options. If set, the responses will be shuffled according to this seed,
	 and all students will see the same randomised order. \
    * @return string $response_shuffle_seed \
    */
    public function get_response_shuffle_seed() {
        return $this->response_shuffle_seed;
    }

    /**
    * Set Response shuffle seed \
    * A seed to use to randomise the order of response options. If set, the responses will be shuffled according to this seed,
	 and all students will see the same randomised order. \
    * @param string $response_shuffle_seed \
    */
    public function set_response_shuffle_seed ($response_shuffle_seed) {
        $this->response_shuffle_seed = $response_shuffle_seed;
    }


}
