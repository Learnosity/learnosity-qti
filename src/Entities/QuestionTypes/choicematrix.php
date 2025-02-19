<?php

namespace LearnosityQti\Entities\QuestionTypes;

use LearnosityQti\Entities\BaseQuestionType;

/**
 * This class is auto-generated based on Schemas API and you should not modify its content
 * Metadata: {"responses":"v2.228.0","feedback":"v2.71.0","features":"v2.208.0"}
 */
class choicematrix extends BaseQuestionType {
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
    protected $shuffle_options;
    protected $options;
    protected $multiple_responses;
    protected $stems;
    protected $math_renderer;
    
    public function __construct(
                    $type,
                                array $options,
                                array $stems
                        )
    {
                $this->type = $type;
                $this->options = $options;
                $this->stems = $stems;
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
     * @return choicematrix_metadata $metadata \
     */
    public function get_metadata() {
        return $this->metadata;
    }

    /**
     * Set metadata \
     *  \
     * @param choicematrix_metadata $metadata \
     */
    public function set_metadata (choicematrix_metadata $metadata) {
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
     * @return choicematrix_ui_style $ui_style \
     */
    public function get_ui_style() {
        return $this->ui_style;
    }

    /**
     * Set ui_style \
     *  \
     * @param choicematrix_ui_style $ui_style \
     */
    public function set_ui_style (choicematrix_ui_style $ui_style) {
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
     * @return choicematrix_validation $validation \
     */
    public function get_validation() {
        return $this->validation;
    }

    /**
     * Set Set correct answer(s) \
     * In this section, configure the correct answer(s) for the question. \
     * @param choicematrix_validation $validation \
     */
    public function set_validation (choicematrix_validation $validation) {
        $this->validation = $validation;
    }

    /**
     * Get Shuffle options \
     *  \
     * @return boolean $shuffle_options \
     */
    public function get_shuffle_options() {
        return $this->shuffle_options;
    }

    /**
     * Set Shuffle options \
     *  \
     * @param boolean $shuffle_options \
     */
    public function set_shuffle_options ($shuffle_options) {
        $this->shuffle_options = $shuffle_options;
    }

    /**
     * Get Options \
     * Array of strings values presenting choice matrix options, for example, ['Yes', 'No']. \
     * @return array $options \
     */
    public function get_options() {
        return $this->options;
    }

    /**
     * Set Options \
     * Array of strings values presenting choice matrix options, for example, ['Yes', 'No']. \
     * @param array $options \
     */
    public function set_options (array $options) {
        $this->options = $options;
    }

    /**
     * Get Multiple responses \
     * If multiple_responses is true the user will be able to select multiple responses using a checkbox for each response. \
     * @return boolean $multiple_responses \
     */
    public function get_multiple_responses() {
        return $this->multiple_responses;
    }

    /**
     * Set Multiple responses \
     * If multiple_responses is true the user will be able to select multiple responses using a checkbox for each response. \
     * @param boolean $multiple_responses \
     */
    public function set_multiple_responses ($multiple_responses) {
        $this->multiple_responses = $multiple_responses;
    }

    /**
     * Get Stems \
     * Array of strings value presenting each row of question stem. \
     * @return array $stems \
     */
    public function get_stems() {
        return $this->stems;
    }

    /**
     * Set Stems \
     * Array of strings value presenting each row of question stem. \
     * @param array $stems \
     */
    public function set_stems (array $stems) {
        $this->stems = $stems;
    }

    /**
     * Get Math renderer \
     * When a question contains math, this setting allows you to select your preferred math renderer: MathJax or MathQuill. \
     * @return string $math_renderer \
     */
    public function get_math_renderer() {
        return $this->math_renderer;
    }

    /**
     * Set Math renderer \
     * When a question contains math, this setting allows you to select your preferred math renderer: MathJax or MathQuill. \
     * @param string $math_renderer \
     */
    public function set_math_renderer ($math_renderer) {
        $this->math_renderer = $math_renderer;
    }

    
    public function get_widget_type() {
    return 'response';
    }
}

