<?php

namespace LearnosityQti\Entities\QuestionTypes;

use LearnosityQti\Entities\BaseQuestionType;

/**
* This class is auto-generated based on Schemas API and you should not modify its content
* Metadata: {"responses":"v2.72.0","feedback":"v2.71.0","features":"v2.72.0"}
*/
class simpleshading extends BaseQuestionType {
    protected $is_math;
    protected $metadata;
    protected $stimulus;
    protected $stimulus_review;
    protected $type;
    protected $ui_style;
    protected $feedback_attempts;
    protected $instant_feedback;
    protected $validation;
    protected $max_selection;
    protected $border;
    protected $hover;
    protected $canvas;
    protected $background_image;
    
    public function __construct(
                    $type,
                                simpleshading_canvas $canvas
                        )
    {
                $this->type = $type;
                $this->canvas = $canvas;
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
    * @return simpleshading_metadata $metadata \
    */
    public function get_metadata() {
        return $this->metadata;
    }

    /**
    * Set metadata \
    *  \
    * @param simpleshading_metadata $metadata \
    */
    public function set_metadata (simpleshading_metadata $metadata) {
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
    * @return simpleshading_ui_style $ui_style \
    */
    public function get_ui_style() {
        return $this->ui_style;
    }

    /**
    * Set ui_style \
    *  \
    * @param simpleshading_ui_style $ui_style \
    */
    public function set_ui_style (simpleshading_ui_style $ui_style) {
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
    * @return simpleshading_validation $validation \
    */
    public function get_validation() {
        return $this->validation;
    }

    /**
    * Set validation \
    * Validation object that includes options on how this question will be automarked \
    * @param simpleshading_validation $validation \
    */
    public function set_validation (simpleshading_validation $validation) {
        $this->validation = $validation;
    }

    /**
    * Get Max Selection \
    * How many elements can user select? \
    * @return number $max_selection \
    */
    public function get_max_selection() {
        return $this->max_selection;
    }

    /**
    * Set Max Selection \
    * How many elements can user select? \
    * @param number $max_selection \
    */
    public function set_max_selection ($max_selection) {
        $this->max_selection = $max_selection;
    }

    /**
    * Get Border Type \
    * Defines how border displays. \
    * @return string $border ie. outer, full, none  \
    */
    public function get_border() {
        return $this->border;
    }

    /**
    * Set Border Type \
    * Defines how border displays. \
    * @param string $border ie. outer, full, none  \
    */
    public function set_border ($border) {
        $this->border = $border;
    }

    /**
    * Get Hover State \
    * Defines whether to have hover state effect. \
    * @return boolean $hover \
    */
    public function get_hover() {
        return $this->hover;
    }

    /**
    * Set Hover State \
    * Defines whether to have hover state effect. \
    * @param boolean $hover \
    */
    public function set_hover ($hover) {
        $this->hover = $hover;
    }

    /**
    * Get Canvas Options \
    *  \
    * @return simpleshading_canvas $canvas \
    */
    public function get_canvas() {
        return $this->canvas;
    }

    /**
    * Set Canvas Options \
    *  \
    * @param simpleshading_canvas $canvas \
    */
    public function set_canvas (simpleshading_canvas $canvas) {
        $this->canvas = $canvas;
    }

    /**
    * Get Background image \
    *  \
    * @return simpleshading_background_image $background_image \
    */
    public function get_background_image() {
        return $this->background_image;
    }

    /**
    * Set Background image \
    *  \
    * @param simpleshading_background_image $background_image \
    */
    public function set_background_image (simpleshading_background_image $background_image) {
        $this->background_image = $background_image;
    }

    
    public function get_widget_type() {
    return 'response';
    }
}

