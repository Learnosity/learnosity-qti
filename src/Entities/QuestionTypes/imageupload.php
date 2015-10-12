<?php

namespace Learnosity\Entities\QuestionTypes;

use Learnosity\Entities\BaseQuestionType;

/**
* This class is auto-generated based on Schemas API and you should not modify its content
* Metadata: {"responses":"v2.72.0","feedback":"v2.71.0","features":"v2.72.0"}
*/
class imageupload extends BaseQuestionType {
    protected $is_math;
    protected $metadata;
    protected $stimulus;
    protected $stimulus_review;
    protected $type;
    protected $ui_style;
    protected $feedback_attempts;
    protected $instant_feedback;
    protected $validation;
    protected $image;
    protected $imageValidationAreas;
    protected $max_width;
    protected $case_sensitive;
    
    public function __construct(
                    $type
                        )
    {
                $this->type = $type;
            }

    /**
    * Get Has Mathematical Formulas \
    * Set to <strong>true</strong> to have LaTeX or MathML contents to be rendered with mathjax. \
    * @return boolean $is_math \
    */
    public function get_is_math() {
        return $this->is_math;
    }

    /**
    * Set Has Mathematical Formulas \
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
    * <a data-toggle="modal" href="#supportedTags">HTML</a>/Text content displayed in all states (initial, resume, review) ren
	dered <strong>above</strong> the response area. Supports embedded <a href="http://docs.learnosity.com/questionsapi/featu
	retypes.php" target="_blank">Feature &lt;span&gt; tags</a>. \
    * @return string $stimulus \
    */
    public function get_stimulus() {
        return $this->stimulus;
    }

    /**
    * Set Stimulus \
    * <a data-toggle="modal" href="#supportedTags">HTML</a>/Text content displayed in all states (initial, resume, review) ren
	dered <strong>above</strong> the response area. Supports embedded <a href="http://docs.learnosity.com/questionsapi/featu
	retypes.php" target="_blank">Feature &lt;span&gt; tags</a>. \
    * @param string $stimulus \
    */
    public function set_stimulus ($stimulus) {
        $this->stimulus = $stimulus;
    }

    /**
    * Get Stimulus in review \
    * <a data-toggle="modal" href="#supportedTags">HTML</a>/Text content displayed <strong>only</strong> in review state rende
	red <strong>above</strong> the response area. Supports embedded <a href="http://docs.learnosity.com/questionsapi/feature
	types.php" target="_blank">Feature &lt;span&gt; tags</a>. Will override stimulus in review state. \
    * @return string $stimulus_review \
    */
    public function get_stimulus_review() {
        return $this->stimulus_review;
    }

    /**
    * Set Stimulus in review \
    * <a data-toggle="modal" href="#supportedTags">HTML</a>/Text content displayed <strong>only</strong> in review state rende
	red <strong>above</strong> the response area. Supports embedded <a href="http://docs.learnosity.com/questionsapi/feature
	types.php" target="_blank">Feature &lt;span&gt; tags</a>. Will override stimulus in review state. \
    * @param string $stimulus_review \
    */
    public function set_stimulus_review ($stimulus_review) {
        $this->stimulus_review = $stimulus_review;
    }

    /**
    * Get Question Type \
    *  \
    * @return string $type \
    */
    public function get_type() {
        return $this->type;
    }

    /**
    * Set Question Type \
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
    * Get Number of feedback attempts allowed \
    * If instant_feedback is true, this field determines how many times the user can click on the 'Check Answer' button, with 
	0 being unlimited. \
    * @return number $feedback_attempts \
    */
    public function get_feedback_attempts() {
        return $this->feedback_attempts;
    }

    /**
    * Set Number of feedback attempts allowed \
    * If instant_feedback is true, this field determines how many times the user can click on the 'Check Answer' button, with 
	0 being unlimited. \
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
    * Get validation \
    * Validation object that includes options on how this question will be automarked \
    * @return imageupload_validation $validation \
    */
    public function get_validation() {
        return $this->validation;
    }

    /**
    * Set validation \
    * Validation object that includes options on how this question will be automarked \
    * @param imageupload_validation $validation \
    */
    public function set_validation (imageupload_validation $validation) {
        $this->validation = $validation;
    }

    /**
    * Get Stimulus Image \
    * Define an image to be annotated. \
    * @return imageupload_image $image \
    */
    public function get_image() {
        return $this->image;
    }

    /**
    * Set Stimulus Image \
    * Define an image to be annotated. \
    * @param imageupload_image $image \
    */
    public function set_image (imageupload_image $image) {
        $this->image = $image;
    }

    /**
    * Get Image Validation Areas \
    * An array of validation areas for an author defined image \
    * @return array $imageValidationAreas \
    */
    public function get_imageValidationAreas() {
        return $this->imageValidationAreas;
    }

    /**
    * Set Image Validation Areas \
    * An array of validation areas for an author defined image \
    * @param array $imageValidationAreas \
    */
    public function set_imageValidationAreas (array $imageValidationAreas) {
        $this->imageValidationAreas = $imageValidationAreas;
    }

    /**
    * Get Max Width \
    * Max width of response area. Define in em, px; or set to 'none' to stretch to full width of container. \
    * @return string $max_width \
    */
    public function get_max_width() {
        return $this->max_width;
    }

    /**
    * Set Max Width \
    * Max width of response area. Define in em, px; or set to 'none' to stretch to full width of container. \
    * @param string $max_width \
    */
    public function set_max_width ($max_width) {
        $this->max_width = $max_width;
    }

    /**
    * Get Case Sensitive? \
    * If true, responses will be compared against valid_responses considering the letters' case. \
    * @return boolean $case_sensitive \
    */
    public function get_case_sensitive() {
        return $this->case_sensitive;
    }

    /**
    * Set Case Sensitive? \
    * If true, responses will be compared against valid_responses considering the letters' case. \
    * @param boolean $case_sensitive \
    */
    public function set_case_sensitive ($case_sensitive) {
        $this->case_sensitive = $case_sensitive;
    }

    
    public function get_widget_type() {
    return 'response';
    }
}

