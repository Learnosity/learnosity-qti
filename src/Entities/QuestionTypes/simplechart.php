<?php

namespace Learnosity\Entities\QuestionTypes;

use Learnosity\Entities\BaseQuestionType;

/**
* This class is auto-generated based on Schemas API and you should not modify its content
* Metadata: {"responses":"v2.72.0","feedback":"v2.71.0","features":"v2.72.0"}
*/
class simplechart extends BaseQuestionType {
    protected $is_math;
    protected $metadata;
    protected $stimulus;
    protected $stimulus_review;
    protected $type;
    protected $ui_style;
    protected $feedback_attempts;
    protected $instant_feedback;
    protected $validation;
    protected $max_y_value;
    protected $chart_data;
    protected $add_point;
    protected $resize_point;
    protected $edit_label;
    protected $delete_point;
    protected $x_axis_label;
    protected $y_axis_label;
    protected $snap_to_grid;
    protected $multicolour;
    protected $order_point;
    protected $show_gridlines;
    protected $new_point_name;
    
    public function __construct(
                    $type,
                                simplechart_chart_data $chart_data
                        )
    {
                $this->type = $type;
                $this->chart_data = $chart_data;
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
    * @return simplechart_metadata $metadata \
    */
    public function get_metadata() {
        return $this->metadata;
    }

    /**
    * Set metadata \
    *  \
    * @param simplechart_metadata $metadata \
    */
    public function set_metadata (simplechart_metadata $metadata) {
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
    * @return simplechart_ui_style $ui_style \
    */
    public function get_ui_style() {
        return $this->ui_style;
    }

    /**
    * Set ui_style \
    *  \
    * @param simplechart_ui_style $ui_style \
    */
    public function set_ui_style (simplechart_ui_style $ui_style) {
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
    * @return simplechart_validation $validation \
    */
    public function get_validation() {
        return $this->validation;
    }

    /**
    * Set validation \
    * Validation object that includes options on how this question will be automarked \
    * @param simplechart_validation $validation \
    */
    public function set_validation (simplechart_validation $validation) {
        $this->validation = $validation;
    }

    /**
    * Get Max Y Axis Value \
    * Defines the max value of the Y axis. \
    * @return number $max_y_value \
    */
    public function get_max_y_value() {
        return $this->max_y_value;
    }

    /**
    * Set Max Y Axis Value \
    * Defines the max value of the Y axis. \
    * @param number $max_y_value \
    */
    public function set_max_y_value ($max_y_value) {
        $this->max_y_value = $max_y_value;
    }

    /**
    * Get Chart Data \
    *  \
    * @return simplechart_chart_data $chart_data \
    */
    public function get_chart_data() {
        return $this->chart_data;
    }

    /**
    * Set Chart Data \
    *  \
    * @param simplechart_chart_data $chart_data \
    */
    public function set_chart_data (simplechart_chart_data $chart_data) {
        $this->chart_data = $chart_data;
    }

    /**
    * Get Add Point \
    * Enabled the ability to add points to the chart. \
    * @return boolean $add_point \
    */
    public function get_add_point() {
        return $this->add_point;
    }

    /**
    * Set Add Point \
    * Enabled the ability to add points to the chart. \
    * @param boolean $add_point \
    */
    public function set_add_point ($add_point) {
        $this->add_point = $add_point;
    }

    /**
    * Get Resize Point \
    * Enable the ability to resize points in the chart. \
    * @return boolean $resize_point \
    */
    public function get_resize_point() {
        return $this->resize_point;
    }

    /**
    * Set Resize Point \
    * Enable the ability to resize points in the chart. \
    * @param boolean $resize_point \
    */
    public function set_resize_point ($resize_point) {
        $this->resize_point = $resize_point;
    }

    /**
    * Get Edit point label \
    * Enables the ability to edit point labels \
    * @return boolean $edit_label \
    */
    public function get_edit_label() {
        return $this->edit_label;
    }

    /**
    * Set Edit point label \
    * Enables the ability to edit point labels \
    * @param boolean $edit_label \
    */
    public function set_edit_label ($edit_label) {
        $this->edit_label = $edit_label;
    }

    /**
    * Get Delete Point \
    * Enables the ability to delete points \
    * @return boolean $delete_point \
    */
    public function get_delete_point() {
        return $this->delete_point;
    }

    /**
    * Set Delete Point \
    * Enables the ability to delete points \
    * @param boolean $delete_point \
    */
    public function set_delete_point ($delete_point) {
        $this->delete_point = $delete_point;
    }

    /**
    * Get X axis label \
    * Set the x axis label name \
    * @return string $x_axis_label \
    */
    public function get_x_axis_label() {
        return $this->x_axis_label;
    }

    /**
    * Set X axis label \
    * Set the x axis label name \
    * @param string $x_axis_label \
    */
    public function set_x_axis_label ($x_axis_label) {
        $this->x_axis_label = $x_axis_label;
    }

    /**
    * Get Y axis label \
    * Set the Y axis label name \
    * @return string $y_axis_label \
    */
    public function get_y_axis_label() {
        return $this->y_axis_label;
    }

    /**
    * Set Y axis label \
    * Set the Y axis label name \
    * @param string $y_axis_label \
    */
    public function set_y_axis_label ($y_axis_label) {
        $this->y_axis_label = $y_axis_label;
    }

    /**
    * Get Snap To Grid \
    * Specify snap to grid threshold. Snap to grid is disabled if set to 0 or if the tick format is float. \
    * @return number $snap_to_grid \
    */
    public function get_snap_to_grid() {
        return $this->snap_to_grid;
    }

    /**
    * Set Snap To Grid \
    * Specify snap to grid threshold. Snap to grid is disabled if set to 0 or if the tick format is float. \
    * @param number $snap_to_grid \
    */
    public function set_snap_to_grid ($snap_to_grid) {
        $this->snap_to_grid = $snap_to_grid;
    }

    /**
    * Get Multicolor Bars \
    * Enables the ability to specify if the bars have different colors. Currently only supported by histogram. Order Point has
	 to be disabled. \
    * @return boolean $multicolour \
    */
    public function get_multicolour() {
        return $this->multicolour;
    }

    /**
    * Set Multicolor Bars \
    * Enables the ability to specify if the bars have different colors. Currently only supported by histogram. Order Point has
	 to be disabled. \
    * @param boolean $multicolour \
    */
    public function set_multicolour ($multicolour) {
        $this->multicolour = $multicolour;
    }

    /**
    * Get Order Point \
    * Enables ability to order points \
    * @return boolean $order_point \
    */
    public function get_order_point() {
        return $this->order_point;
    }

    /**
    * Set Order Point \
    * Enables ability to order points \
    * @param boolean $order_point \
    */
    public function set_order_point ($order_point) {
        $this->order_point = $order_point;
    }

    /**
    * Get Show Axes Gridlines \
    * Specify whether axes gridlines should be displayed for the chart. Currently only supported by histogram. \
    * @return string $show_gridlines ie. both, x_only, y_only, none  \
    */
    public function get_show_gridlines() {
        return $this->show_gridlines;
    }

    /**
    * Set Show Axes Gridlines \
    * Specify whether axes gridlines should be displayed for the chart. Currently only supported by histogram. \
    * @param string $show_gridlines ie. both, x_only, y_only, none  \
    */
    public function set_show_gridlines ($show_gridlines) {
        $this->show_gridlines = $show_gridlines;
    }

    /**
    * Get New Point Name \
    * Enables the ability to specify new points default name. \
    * @return string $new_point_name \
    */
    public function get_new_point_name() {
        return $this->new_point_name;
    }

    /**
    * Set New Point Name \
    * Enables the ability to specify new points default name. \
    * @param string $new_point_name \
    */
    public function set_new_point_name ($new_point_name) {
        $this->new_point_name = $new_point_name;
    }

    
    public function get_widget_type() {
    return 'response';
    }
}

