<?php

namespace Learnosity\Entities\QuestionTypes;

use Learnosity\Entities\BaseQuestionType;

/**
* This class is auto-generated based on Schemas API and you should not modify its content
* Metadata: {"responses":"v2.68.0","feedback":"v2.35.0","features":"v2.68.0"}
*/
class graphplotting extends BaseQuestionType {
    protected $is_math;
    protected $math_renderer;
    protected $metadata;
    protected $stimulus;
    protected $stimulus_review;
    protected $type;
    protected $ui_style;
    protected $feedback_attempts;
    protected $instant_feedback;
    protected $validation;
    protected $description;
    protected $mode;
    protected $axis_x;
    protected $axis_y;
    protected $canvas;
    protected $grid;
    protected $annotation;
    protected $toolbar;
    protected $draw_zero;
    protected $background_image;
    protected $display_points;
    protected $background_shapes;
    
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
    * Get Math renderer \
    * Choose the rendering engine for math content within a question. Default rendering engine is MathJax with MathQuill for i
	nput areas. If you change this option to "MathQuill", MathQuill will render all math content within a question. \
    * @return string $math_renderer \
    */
    public function get_math_renderer() {
        return $this->math_renderer;
    }

    /**
    * Set Math renderer \
    * Choose the rendering engine for math content within a question. Default rendering engine is MathJax with MathQuill for i
	nput areas. If you change this option to "MathQuill", MathQuill will render all math content within a question. \
    * @param string $math_renderer \
    */
    public function set_math_renderer ($math_renderer) {
        $this->math_renderer = $math_renderer;
    }

    /**
    * Get metadata \
    *  \
    * @return graphplotting_metadata $metadata \
    */
    public function get_metadata() {
        return $this->metadata;
    }

    /**
    * Set metadata \
    *  \
    * @param graphplotting_metadata $metadata \
    */
    public function set_metadata (graphplotting_metadata $metadata) {
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
    * @return graphplotting_ui_style $ui_style \
    */
    public function get_ui_style() {
        return $this->ui_style;
    }

    /**
    * Set ui_style \
    *  \
    * @param graphplotting_ui_style $ui_style \
    */
    public function set_ui_style (graphplotting_ui_style $ui_style) {
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
    * @return graphplotting_validation $validation \
    */
    public function get_validation() {
        return $this->validation;
    }

    /**
    * Set validation \
    * Validation object that includes options on how this question will be automarked \
    * @param graphplotting_validation $validation \
    */
    public function set_validation (graphplotting_validation $validation) {
        $this->validation = $validation;
    }

    /**
    * Get Description (deprecated) \
    * <span class="label label-danger">Deprecated</span> See <em>stimulus_review</em>. <br />
Description of the question and
	 its context to be displayed in the teacher and student areas. 
It <a data-toggle="modal" href="#supportedTags">support
	s HTML entities</a>. \
    * @return string $description \
    */
    public function get_description() {
        return $this->description;
    }

    /**
    * Set Description (deprecated) \
    * <span class="label label-danger">Deprecated</span> See <em>stimulus_review</em>. <br />
Description of the question and
	 its context to be displayed in the teacher and student areas. 
It <a data-toggle="modal" href="#supportedTags">support
	s HTML entities</a>. \
    * @param string $description \
    */
    public function set_description ($description) {
        $this->description = $description;
    }

    /**
    * Get Mode [Deprecated] \
    * Defines the plotting mode to load. Possible values are: "all", "point", "line" \
    * @return string $mode \
    */
    public function get_mode() {
        return $this->mode;
    }

    /**
    * Set Mode [Deprecated] \
    * Defines the plotting mode to load. Possible values are: "all", "point", "line" \
    * @param string $mode \
    */
    public function set_mode ($mode) {
        $this->mode = $mode;
    }

    /**
    * Get Axis X \
    * Defines if Axis should be shown in the Cartesian plane \
    * @return graphplotting_axis_x $axis_x \
    */
    public function get_axis_x() {
        return $this->axis_x;
    }

    /**
    * Set Axis X \
    * Defines if Axis should be shown in the Cartesian plane \
    * @param graphplotting_axis_x $axis_x \
    */
    public function set_axis_x (graphplotting_axis_x $axis_x) {
        $this->axis_x = $axis_x;
    }

    /**
    * Get Axis Y \
    * Defines if Axis X should be shown in the Cartesian plane \
    * @return graphplotting_axis_y $axis_y \
    */
    public function get_axis_y() {
        return $this->axis_y;
    }

    /**
    * Set Axis Y \
    * Defines if Axis X should be shown in the Cartesian plane \
    * @param graphplotting_axis_y $axis_y \
    */
    public function set_axis_y (graphplotting_axis_y $axis_y) {
        $this->axis_y = $axis_y;
    }

    /**
    * Get Canvas \
    * Specifies the canvas representing the Cartesian plane in which the user will be plotting \
    * @return graphplotting_canvas $canvas \
    */
    public function get_canvas() {
        return $this->canvas;
    }

    /**
    * Set Canvas \
    * Specifies the canvas representing the Cartesian plane in which the user will be plotting \
    * @param graphplotting_canvas $canvas \
    */
    public function set_canvas (graphplotting_canvas $canvas) {
        $this->canvas = $canvas;
    }

    /**
    * Get Grid \
    * Defines the Grid to be drawn in the Cartesian plane \
    * @return graphplotting_grid $grid \
    */
    public function get_grid() {
        return $this->grid;
    }

    /**
    * Set Grid \
    * Defines the Grid to be drawn in the Cartesian plane \
    * @param graphplotting_grid $grid \
    */
    public function set_grid (graphplotting_grid $grid) {
        $this->grid = $grid;
    }

    /**
    * Get Annotation \
    * Object used to add annotations to the question. Eg, labels, comments, titles \
    * @return graphplotting_annotation $annotation \
    */
    public function get_annotation() {
        return $this->annotation;
    }

    /**
    * Set Annotation \
    * Object used to add annotations to the question. Eg, labels, comments, titles \
    * @param graphplotting_annotation $annotation \
    */
    public function set_annotation (graphplotting_annotation $annotation) {
        $this->annotation = $annotation;
    }

    /**
    * Get Toolbar \
    * Object that defines which tools are available and other options for the toolbar. \
    * @return graphplotting_toolbar $toolbar \
    */
    public function get_toolbar() {
        return $this->toolbar;
    }

    /**
    * Set Toolbar \
    * Object that defines which tools are available and other options for the toolbar. \
    * @param graphplotting_toolbar $toolbar \
    */
    public function set_toolbar (graphplotting_toolbar $toolbar) {
        $this->toolbar = $toolbar;
    }

    /**
    * Get Draw Label Zero \
    * Defines whether to draw '0' label \
    * @return boolean $draw_zero \
    */
    public function get_draw_zero() {
        return $this->draw_zero;
    }

    /**
    * Set Draw Label Zero \
    * Defines whether to draw '0' label \
    * @param boolean $draw_zero \
    */
    public function set_draw_zero ($draw_zero) {
        $this->draw_zero = $draw_zero;
    }

    /**
    * Get Background image \
    *  \
    * @return graphplotting_background_image $background_image \
    */
    public function get_background_image() {
        return $this->background_image;
    }

    /**
    * Set Background image \
    *  \
    * @param graphplotting_background_image $background_image \
    */
    public function set_background_image (graphplotting_background_image $background_image) {
        $this->background_image = $background_image;
    }

    /**
    * Get Show Background Shape Points \
    * Defines whether to show points for background shapes or not \
    * @return boolean $display_points \
    */
    public function get_display_points() {
        return $this->display_points;
    }

    /**
    * Set Show Background Shape Points \
    * Defines whether to show points for background shapes or not \
    * @param boolean $display_points \
    */
    public function set_display_points ($display_points) {
        $this->display_points = $display_points;
    }

    /**
    * Get Background Shapes \
    * Shapes that will be displayed as stimulus in the the background of the Canvas. Users will be able to see them but not be
	 able to interact with them. \
    * @return  $background_shapes \
    */
    public function get_background_shapes() {
        return $this->background_shapes;
    }

    /**
    * Set Background Shapes \
    * Shapes that will be displayed as stimulus in the the background of the Canvas. Users will be able to see them but not be
	 able to interact with them. \
    * @param  $background_shapes \
    */
    public function set_background_shapes ($background_shapes) {
        $this->background_shapes = $background_shapes;
    }

    
    public function get_widget_type() {
    return 'response';
    }
}
