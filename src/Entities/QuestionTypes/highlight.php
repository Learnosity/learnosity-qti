<?php

namespace LearnosityQti\Entities\QuestionTypes;

use LearnosityQti\Entities\BaseQuestionType;

/**
* This class is auto-generated based on Schemas API and you should not modify its content
* Metadata: {"responses":"v2.108.0","feedback":"v2.71.0","features":"v2.107.0"}
*/
class highlight extends BaseQuestionType {
    protected $is_math;
    protected $metadata;
    protected $stimulus;
    protected $stimulus_review;
    protected $instructor_stimulus;
    protected $type;
    protected $ui_style;
    protected $validation;
    protected $description;
    protected $image;
    protected $img_src;
    protected $line_color;
    protected $line_width;
    
    public function __construct(
                    $type,
                                $img_src
                        )
    {
                $this->type = $type;
                $this->img_src = $img_src;
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
    * @return highlight_metadata $metadata \
    */
    public function get_metadata() {
        return $this->metadata;
    }

    /**
    * Set metadata \
    *  \
    * @param highlight_metadata $metadata \
    */
    public function set_metadata (highlight_metadata $metadata) {
        $this->metadata = $metadata;
    }

    /**
    * Get Stimulus \
    * The question stimulus. Can include text, tables, images. \
    * @return string $stimulus \
    */
    public function get_stimulus() {
        return $this->stimulus;
    }

    /**
    * Set Stimulus \
    * The question stimulus. Can include text, tables, images. \
    * @param string $stimulus \
    */
    public function set_stimulus ($stimulus) {
        $this->stimulus = $stimulus;
    }

    /**
    * Get Stimulus (review only) \
    * <a data-toggle="modal" href="#supportedTags">HTML</a>/Text content displayed <strong>only</strong> in review state rende
	red <strong>above</strong> the response area. Supports embedded <a href="http://docs.learnosity.com/questionsapi/feature
	types.php" target="_blank">Feature &lt;span&gt; tags</a>. Will override stimulus in review state. \
    * @return string $stimulus_review \
    */
    public function get_stimulus_review() {
        return $this->stimulus_review;
    }

    /**
    * Set Stimulus (review only) \
    * <a data-toggle="modal" href="#supportedTags">HTML</a>/Text content displayed <strong>only</strong> in review state rende
	red <strong>above</strong> the response area. Supports embedded <a href="http://docs.learnosity.com/questionsapi/feature
	types.php" target="_blank">Feature &lt;span&gt; tags</a>. Will override stimulus in review state. \
    * @param string $stimulus_review \
    */
    public function set_stimulus_review ($stimulus_review) {
        $this->stimulus_review = $stimulus_review;
    }

    /**
    * Get Instructor stimulus \
    * <a data-toggle="modal" href="#supportedTags">HTML</a>/Text content displayed when <code>showInstructorStimulus</code> is
	 set to <code>true</code> on the activity. Supports embedded <a href="http://docs.learnosity.com/questionsapi/featuretyp
	es.php" target="_blank">Feature &lt;span&gt; tags</a>. \
    * @return string $instructor_stimulus \
    */
    public function get_instructor_stimulus() {
        return $this->instructor_stimulus;
    }

    /**
    * Set Instructor stimulus \
    * <a data-toggle="modal" href="#supportedTags">HTML</a>/Text content displayed when <code>showInstructorStimulus</code> is
	 set to <code>true</code> on the activity. Supports embedded <a href="http://docs.learnosity.com/questionsapi/featuretyp
	es.php" target="_blank">Feature &lt;span&gt; tags</a>. \
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
    * @return highlight_ui_style $ui_style \
    */
    public function get_ui_style() {
        return $this->ui_style;
    }

    /**
    * Set ui_style \
    *  \
    * @param highlight_ui_style $ui_style \
    */
    public function set_ui_style (highlight_ui_style $ui_style) {
        $this->ui_style = $ui_style;
    }

    /**
    * Get Validation \
    * In this section, configure the correct answer(s) for the question. \
    * @return highlight_validation $validation \
    */
    public function get_validation() {
        return $this->validation;
    }

    /**
    * Set Validation \
    * In this section, configure the correct answer(s) for the question. \
    * @param highlight_validation $validation \
    */
    public function set_validation (highlight_validation $validation) {
        $this->validation = $validation;
    }

    /**
    * Get Description (deprecated) \
    * <span class="label label-danger">Deprecated</span> See <em>stimulus_review</em>. <br />
Description of the question and
	 its context to be displayed. 
It <a data-toggle="modal" href="#supportedTags">supports HTML entities</a>. \
    * @return string $description \
    */
    public function get_description() {
        return $this->description;
    }

    /**
    * Set Description (deprecated) \
    * <span class="label label-danger">Deprecated</span> See <em>stimulus_review</em>. <br />
Description of the question and
	 its context to be displayed. 
It <a data-toggle="modal" href="#supportedTags">supports HTML entities</a>. \
    * @param string $description \
    */
    public function set_description ($description) {
        $this->description = $description;
    }

    /**
    * Get Add image \
    * The absolute URL of the background image. \
    * @return highlight_image $image \
    */
    public function get_image() {
        return $this->image;
    }

    /**
    * Set Add image \
    * The absolute URL of the background image. \
    * @param highlight_image $image \
    */
    public function set_image (highlight_image $image) {
        $this->image = $image;
    }

    /**
    * Get Add image \
    * The absolute URL of the background image. \
    * @return string $img_src \
    */
    public function get_img_src() {
        return $this->img_src;
    }

    /**
    * Set Add image \
    * The absolute URL of the background image. \
    * @param string $img_src \
    */
    public function set_img_src ($img_src) {
        $this->img_src = $img_src;
    }

    /**
    * Get Line color options \
    * Color of the painted line, expressed as a HTML color code. Examples of acceptable formats: '#FFFFFF', 'white', 'rgb(255,
	 255, 255)', 'rgba(255, 0, 0, 0.8)'. \
    * @return array $line_color \
    */
    public function get_line_color() {
        return $this->line_color;
    }

    /**
    * Set Line color options \
    * Color of the painted line, expressed as a HTML color code. Examples of acceptable formats: '#FFFFFF', 'white', 'rgb(255,
	 255, 255)', 'rgba(255, 0, 0, 0.8)'. \
    * @param array $line_color \
    */
    public function set_line_color (array $line_color) {
        $this->line_color = $line_color;
    }

    /**
    * Get Line width (px) \
    * Width of the painted line, in pixels. \
    * @return number $line_width \
    */
    public function get_line_width() {
        return $this->line_width;
    }

    /**
    * Set Line width (px) \
    * Width of the painted line, in pixels. \
    * @param number $line_width \
    */
    public function set_line_width ($line_width) {
        $this->line_width = $line_width;
    }

    
    public function get_widget_type() {
    return 'response';
    }
}

