<?php

namespace LearnosityQti\Entities\QuestionTypes;

use LearnosityQti\Entities\BaseQuestionType;

/**
* This class is auto-generated based on Schemas API and you should not modify its content
* Metadata: {"responses":"v2.84.0","feedback":"v2.71.0","features":"v2.84.0"}
*/
class hotspot extends BaseQuestionType {
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
    protected $areas;
    protected $area_attributes;
    protected $max_width;
    protected $multiple_responses;
    
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
    * @return hotspot_metadata $metadata \
    */
    public function get_metadata() {
        return $this->metadata;
    }

    /**
    * Set metadata \
    *  \
    * @param hotspot_metadata $metadata \
    */
    public function set_metadata (hotspot_metadata $metadata) {
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
    * @return hotspot_ui_style $ui_style \
    */
    public function get_ui_style() {
        return $this->ui_style;
    }

    /**
    * Set ui_style \
    *  \
    * @param hotspot_ui_style $ui_style \
    */
    public function set_ui_style (hotspot_ui_style $ui_style) {
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
    * @return hotspot_validation $validation \
    */
    public function get_validation() {
        return $this->validation;
    }

    /**
    * Set validation \
    * Validation object that includes options on how this question will be automarked \
    * @param hotspot_validation $validation \
    */
    public function set_validation (hotspot_validation $validation) {
        $this->validation = $validation;
    }

    /**
    * Get Stimulus Image \
    * Define an image to be annotated. \
    * @return hotspot_image $image \
    */
    public function get_image() {
        return $this->image;
    }

    /**
    * Set Stimulus Image \
    * Define an image to be annotated. \
    * @param hotspot_image $image \
    */
    public function set_image (hotspot_image $image) {
        $this->image = $image;
    }

    /**
    * Get Areas \
    * An array of validation areas for the stimulus image. \
    * @return array $areas \
    */
    public function get_areas() {
        return $this->areas;
    }

    /**
    * Set Areas \
    * An array of validation areas for the stimulus image. \
    * @param array $areas \
    */
    public function set_areas (array $areas) {
        $this->areas = $areas;
    }

    /**
    * Get Area Attributes \
    *  \
    * @return hotspot_area_attributes $area_attributes \
    */
    public function get_area_attributes() {
        return $this->area_attributes;
    }

    /**
    * Set Area Attributes \
    *  \
    * @param hotspot_area_attributes $area_attributes \
    */
    public function set_area_attributes (hotspot_area_attributes $area_attributes) {
        $this->area_attributes = $area_attributes;
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
    * Get Multiple responses \
    * If multiple_responses is true the user will be able to select multiple hotspots. \
    * @return boolean $multiple_responses \
    */
    public function get_multiple_responses() {
        return $this->multiple_responses;
    }

    /**
    * Set Multiple responses \
    * If multiple_responses is true the user will be able to select multiple hotspots. \
    * @param boolean $multiple_responses \
    */
    public function set_multiple_responses ($multiple_responses) {
        $this->multiple_responses = $multiple_responses;
    }

    
    public function get_widget_type() {
    return 'response';
    }
}

