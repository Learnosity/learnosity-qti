<?php

namespace LearnosityQti\Entities\QuestionTypes;

use LearnosityQti\Entities\BaseQuestionType;

/**
* This class is auto-generated based on Schemas API and you should not modify its content
* Metadata: {"responses":"v2.108.0","feedback":"v2.71.0","features":"v2.107.0"}
*/
class fileupload extends BaseQuestionType {
    protected $is_math;
    protected $metadata;
    protected $stimulus;
    protected $stimulus_review;
    protected $instructor_stimulus;
    protected $type;
    protected $ui_style;
    protected $validation;
    protected $max_width;
    protected $max_files;
    protected $allow_pdf;
    protected $allow_jpg;
    protected $allow_gif;
    protected $allow_png;
    
    public function __construct(
                    $type,
                                $max_files
                        )
    {
                $this->type = $type;
                $this->max_files = $max_files;
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
    * @return fileupload_metadata $metadata \
    */
    public function get_metadata() {
        return $this->metadata;
    }

    /**
    * Set metadata \
    *  \
    * @param fileupload_metadata $metadata \
    */
    public function set_metadata (fileupload_metadata $metadata) {
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
    * @return fileupload_ui_style $ui_style \
    */
    public function get_ui_style() {
        return $this->ui_style;
    }

    /**
    * Set ui_style \
    *  \
    * @param fileupload_ui_style $ui_style \
    */
    public function set_ui_style (fileupload_ui_style $ui_style) {
        $this->ui_style = $ui_style;
    }

    /**
    * Get Validation \
    * In this section, configure the correct answer(s) for the question. \
    * @return fileupload_validation $validation \
    */
    public function get_validation() {
        return $this->validation;
    }

    /**
    * Set Validation \
    * In this section, configure the correct answer(s) for the question. \
    * @param fileupload_validation $validation \
    */
    public function set_validation (fileupload_validation $validation) {
        $this->validation = $validation;
    }

    /**
    * Get Max Width \
    * Max width of the upload area. Define in em, px; or set to 'none' to stretch to full width of container. \
    * @return string $max_width \
    */
    public function get_max_width() {
        return $this->max_width;
    }

    /**
    * Set Max Width \
    * Max width of the upload area. Define in em, px; or set to 'none' to stretch to full width of container. \
    * @param string $max_width \
    */
    public function set_max_width ($max_width) {
        $this->max_width = $max_width;
    }

    /**
    * Get Max Files \
    * Max number of uploaded files. \
    * @return number $max_files ie. 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12  \
    */
    public function get_max_files() {
        return $this->max_files;
    }

    /**
    * Set Max Files \
    * Max number of uploaded files. \
    * @param number $max_files ie. 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12  \
    */
    public function set_max_files ($max_files) {
        $this->max_files = $max_files;
    }

    /**
    * Get PDF \
    * Allow uploading of .pdf files \
    * @return boolean $allow_pdf \
    */
    public function get_allow_pdf() {
        return $this->allow_pdf;
    }

    /**
    * Set PDF \
    * Allow uploading of .pdf files \
    * @param boolean $allow_pdf \
    */
    public function set_allow_pdf ($allow_pdf) {
        $this->allow_pdf = $allow_pdf;
    }

    /**
    * Get JPG \
    * Allow uploading of .jpg files \
    * @return boolean $allow_jpg \
    */
    public function get_allow_jpg() {
        return $this->allow_jpg;
    }

    /**
    * Set JPG \
    * Allow uploading of .jpg files \
    * @param boolean $allow_jpg \
    */
    public function set_allow_jpg ($allow_jpg) {
        $this->allow_jpg = $allow_jpg;
    }

    /**
    * Get GIF \
    * Allow uploading of .gif files \
    * @return boolean $allow_gif \
    */
    public function get_allow_gif() {
        return $this->allow_gif;
    }

    /**
    * Set GIF \
    * Allow uploading of .gif files \
    * @param boolean $allow_gif \
    */
    public function set_allow_gif ($allow_gif) {
        $this->allow_gif = $allow_gif;
    }

    /**
    * Get PNG \
    * Allow uploading of .png files \
    * @return boolean $allow_png \
    */
    public function get_allow_png() {
        return $this->allow_png;
    }

    /**
    * Set PNG \
    * Allow uploading of .png files \
    * @param boolean $allow_png \
    */
    public function set_allow_png ($allow_png) {
        $this->allow_png = $allow_png;
    }

    
    public function get_widget_type() {
    return 'response';
    }
}

