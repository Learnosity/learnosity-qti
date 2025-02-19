<?php

namespace LearnosityQti\Entities\QuestionTypes;

use LearnosityQti\Entities\BaseQuestionType;

/**
 * This class is auto-generated based on Schemas API and you should not modify its content
 * Metadata: {"responses":"v2.228.0","feedback":"v2.71.0","features":"v2.208.0"}
 */
class imageupload extends BaseQuestionType {
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
    protected $image;
    protected $imageValidationAreas;
    protected $max_width;
    protected $spellcheck;
    protected $case_sensitive;
    protected $math_renderer;
    
    public function __construct(
                    $type
                        )
    {
                $this->type = $type;
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
     * @return imageupload_metadata $metadata \
     */
    public function get_metadata() {
        return $this->metadata;
    }

    /**
     * Set metadata \
     *  \
     * @param imageupload_metadata $metadata \
     */
    public function set_metadata (imageupload_metadata $metadata) {
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
     * @return imageupload_ui_style $ui_style \
     */
    public function get_ui_style() {
        return $this->ui_style;
    }

    /**
     * Set ui_style \
     *  \
     * @param imageupload_ui_style $ui_style \
     */
    public function set_ui_style (imageupload_ui_style $ui_style) {
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
     * @return imageupload_validation $validation \
     */
    public function get_validation() {
        return $this->validation;
    }

    /**
     * Set Set correct answer(s) \
     * In this section, configure the correct answer(s) for the question. \
     * @param imageupload_validation $validation \
     */
    public function set_validation (imageupload_validation $validation) {
        $this->validation = $validation;
    }

    /**
     * Get Stimulus image \
     * Define an image to be annotated. \
     * @return imageupload_image $image \
     */
    public function get_image() {
        return $this->image;
    }

    /**
     * Set Stimulus image \
     * Define an image to be annotated. \
     * @param imageupload_image $image \
     */
    public function set_image (imageupload_image $image) {
        $this->image = $image;
    }

    /**
     * Get Image validation areas \
     * Highlight the areas you want the student to place the text boxes inside of, using the hotspot tool. \
     * @return array $imageValidationAreas \
     */
    public function get_imageValidationAreas() {
        return $this->imageValidationAreas;
    }

    /**
     * Set Image validation areas \
     * Highlight the areas you want the student to place the text boxes inside of, using the hotspot tool. \
     * @param array $imageValidationAreas \
     */
    public function set_imageValidationAreas (array $imageValidationAreas) {
        $this->imageValidationAreas = $imageValidationAreas;
    }

    /**
     * Get Maximum width \
     * Max width of response area. Default units are "px" and will be appended if no units provided. For units other than "px",
	 *  provide them in the field. E.g. "10em", or set to 'none' to stretch to full width of container. \
     * @return stringUnits $max_width \
     */
    public function get_max_width() {
        return $this->max_width;
    }

    /**
     * Set Maximum width \
     * Max width of response area. Default units are "px" and will be appended if no units provided. For units other than "px",
	 *  provide them in the field. E.g. "10em", or set to 'none' to stretch to full width of container. \
     * @param stringUnits $max_width \
     */
    public function set_max_width ($max_width) {
        $this->max_width = $max_width;
    }

    /**
     * Get Spellcheck and text correction \
     * Enable or disable browser spellcheck, autocapitalize, autocomplete and autocorrect attributes on the user response area. \
     * @return boolean $spellcheck \
     */
    public function get_spellcheck() {
        return $this->spellcheck;
    }

    /**
     * Set Spellcheck and text correction \
     * Enable or disable browser spellcheck, autocapitalize, autocomplete and autocorrect attributes on the user response area. \
     * @param boolean $spellcheck \
     */
    public function set_spellcheck ($spellcheck) {
        $this->spellcheck = $spellcheck;
    }

    /**
     * Get Case sensitive \
     * If true, responses will be compared against valid_responses considering the letters' case. \
     * @return boolean $case_sensitive \
     */
    public function get_case_sensitive() {
        return $this->case_sensitive;
    }

    /**
     * Set Case sensitive \
     * If true, responses will be compared against valid_responses considering the letters' case. \
     * @param boolean $case_sensitive \
     */
    public function set_case_sensitive ($case_sensitive) {
        $this->case_sensitive = $case_sensitive;
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

