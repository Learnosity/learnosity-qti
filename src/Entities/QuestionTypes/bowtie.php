<?php

namespace LearnosityQti\Entities\QuestionTypes;

use LearnosityQti\Entities\BaseQuestionType;

/**
 * This class is auto-generated based on Schemas API and you should not modify its content
 * Metadata: {"responses":"v2.228.0","feedback":"v2.71.0","features":"v2.208.0"}
 */
class bowtie extends BaseQuestionType {
    protected $is_math;
    protected $metadata;
    protected $stimulus;
    protected $stimulus_review;
    protected $instructor_stimulus;
    protected $type;
    protected $ui_style;
    protected $feedback_attempts;
    protected $instant_feedback;
    protected $validation;
    protected $group_possible_responses;
    protected $possible_responses;
    protected $possible_response_groups;
    
    public function __construct(
                    $type,
                                array $possible_responses
                        )
    {
                $this->type = $type;
                $this->possible_responses = $possible_responses;
            }

    /**
     * Get Contains math \
     * Set to <strong>true</strong> to have LaTeX or MathML contents to be rendered with mathjax. \
     * @return boolean $is_math \
     */
    public function get_is_math() {
        return $this->is_math;
    }

    /**
     * Set Contains math \
     * Set to <strong>true</strong> to have LaTeX or MathML contents to be rendered with mathjax. \
     * @param boolean $is_math \
     */
    public function set_is_math ($is_math) {
        $this->is_math = $is_math;
    }

    /**
     * Get metadata \
     *  \
     * @return bowtie_metadata $metadata \
     */
    public function get_metadata() {
        return $this->metadata;
    }

    /**
     * Set metadata \
     *  \
     * @param bowtie_metadata $metadata \
     */
    public function set_metadata (bowtie_metadata $metadata) {
        $this->metadata = $metadata;
    }

    /**
     * Get Stimulus \
     * The question stimulus. This can include text, tables, images, resources and LaTeX entered via the Math Editor. \
     * @return string $stimulus \
     */
    public function get_stimulus() {
        return $this->stimulus;
    }

    /**
     * Set Stimulus \
     * The question stimulus. This can include text, tables, images, resources and LaTeX entered via the Math Editor. \
     * @param string $stimulus \
     */
    public function set_stimulus ($stimulus) {
        $this->stimulus = $stimulus;
    }

    /**
     * Get Stimulus (review only) \
     * <a data-toggle="modal" href="#supportedTags">HTML</a>/Text content displayed <strong>only</strong> in review state rende
	 * red <strong>above</strong> the response area. Supports embedded <a href="https://docs.learnosity.com/assessment/question
	 * s/knowledgebase/customfeatures" target="_blank">Feature &lt;span&gt; tags</a>. Will override stimulus in review state. \
     * @return string $stimulus_review \
     */
    public function get_stimulus_review() {
        return $this->stimulus_review;
    }

    /**
     * Set Stimulus (review only) \
     * <a data-toggle="modal" href="#supportedTags">HTML</a>/Text content displayed <strong>only</strong> in review state rende
	 * red <strong>above</strong> the response area. Supports embedded <a href="https://docs.learnosity.com/assessment/question
	 * s/knowledgebase/customfeatures" target="_blank">Feature &lt;span&gt; tags</a>. Will override stimulus in review state. \
     * @param string $stimulus_review \
     */
    public function set_stimulus_review ($stimulus_review) {
        $this->stimulus_review = $stimulus_review;
    }

    /**
     * Get Instructor stimulus \
     * <a data-toggle="modal" href="#supportedTags">HTML</a>/Text content displayed when <code>showInstructorStimulus</code> is
	 *  set to <code>true</code> on the activity. Supports embedded <a href="https://docs.learnosity.com/assessment/questions/f
	 * eaturetypes" target="_blank">Feature &lt;span&gt; tags</a>. \
     * @return string $instructor_stimulus \
     */
    public function get_instructor_stimulus() {
        return $this->instructor_stimulus;
    }

    /**
     * Set Instructor stimulus \
     * <a data-toggle="modal" href="#supportedTags">HTML</a>/Text content displayed when <code>showInstructorStimulus</code> is
	 *  set to <code>true</code> on the activity. Supports embedded <a href="https://docs.learnosity.com/assessment/questions/f
	 * eaturetypes" target="_blank">Feature &lt;span&gt; tags</a>. \
     * @param string $instructor_stimulus \
     */
    public function set_instructor_stimulus ($instructor_stimulus) {
        $this->instructor_stimulus = $instructor_stimulus;
    }

    /**
     * Get Question type \
     *  \
     * @return string $type \
     */
    public function get_type() {
        return $this->type;
    }

    /**
     * Set Question type \
     *  \
     * @param string $type \
     */
    public function set_type ($type) {
        $this->type = $type;
    }

    /**
     * Get ui_style \
     *  \
     * @return bowtie_ui_style $ui_style \
     */
    public function get_ui_style() {
        return $this->ui_style;
    }

    /**
     * Set ui_style \
     *  \
     * @param bowtie_ui_style $ui_style \
     */
    public function set_ui_style (bowtie_ui_style $ui_style) {
        $this->ui_style = $ui_style;
    }

    /**
     * Get Check answer attempts \
     * If instant_feedback is true, this field determines how many times the user can click on the 'Check Answer' button. 0 mea
	 * ns unlimited. \
     * @return number $feedback_attempts \
     */
    public function get_feedback_attempts() {
        return $this->feedback_attempts;
    }

    /**
     * Set Check answer attempts \
     * If instant_feedback is true, this field determines how many times the user can click on the 'Check Answer' button. 0 mea
	 * ns unlimited. \
     * @param number $feedback_attempts \
     */
    public function set_feedback_attempts ($feedback_attempts) {
        $this->feedback_attempts = $feedback_attempts;
    }

    /**
     * Get Provide instant feedback \
     * Flag to determine whether to display a 'Check Answer' button to provide instant feedback to the user. \
     * @return boolean $instant_feedback \
     */
    public function get_instant_feedback() {
        return $this->instant_feedback;
    }

    /**
     * Set Provide instant feedback \
     * Flag to determine whether to display a 'Check Answer' button to provide instant feedback to the user. \
     * @param boolean $instant_feedback \
     */
    public function set_instant_feedback ($instant_feedback) {
        $this->instant_feedback = $instant_feedback;
    }

    /**
     * Get Set correct answer(s) \
     * In this section, configure the correct answer(s) for the question. \
     * @return bowtie_validation $validation \
     */
    public function get_validation() {
        return $this->validation;
    }

    /**
     * Set Set correct answer(s) \
     * In this section, configure the correct answer(s) for the question. \
     * @param bowtie_validation $validation \
     */
    public function set_validation (bowtie_validation $validation) {
        $this->validation = $validation;
    }

    /**
     * Get Group possible responses \
     * Categorise possible responses into different groups, with each group having its own heading. \
     * @return groupPossibleResponses $group_possible_responses \
     */
    public function get_group_possible_responses() {
        return $this->group_possible_responses;
    }

    /**
     * Set Group possible responses \
     * Categorise possible responses into different groups, with each group having its own heading. \
     * @param groupPossibleResponses $group_possible_responses \
     */
    public function set_group_possible_responses ($group_possible_responses) {
        $this->group_possible_responses = $group_possible_responses;
    }

    /**
     * Get Possible responses \
     * Array of strings values that need to be dragged to the actual response position. \
     * @return array $possible_responses \
     */
    public function get_possible_responses() {
        return $this->possible_responses;
    }

    /**
     * Set Possible responses \
     * Array of strings values that need to be dragged to the actual response position. \
     * @param array $possible_responses \
     */
    public function set_possible_responses (array $possible_responses) {
        $this->possible_responses = $possible_responses;
    }

    /**
     * Get Possible response groups \
     * . \
     * @return array $possible_response_groups \
     */
    public function get_possible_response_groups() {
        return $this->possible_response_groups;
    }

    /**
     * Set Possible response groups \
     * . \
     * @param array $possible_response_groups \
     */
    public function set_possible_response_groups (array $possible_response_groups) {
        $this->possible_response_groups = $possible_response_groups;
    }

    
    public function get_widget_type() {
    return 'response';
    }
}

