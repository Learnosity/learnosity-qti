<?php

namespace LearnosityQti\Entities\QuestionTypes;

use LearnosityQti\Entities\BaseQuestionType;

/**
 * This class is auto-generated based on Schemas API and you should not modify its content
 * Metadata: {"responses":"v2.228.0","feedback":"v2.71.0","features":"v2.208.0"}
 */
class custom extends BaseQuestionType {
    protected $is_math;
    protected $metadata;
    protected $stimulus;
    protected $stimulus_review;
    protected $instructor_stimulus;
    protected $type;
    protected $ui_style;
    protected $custom_type;
    protected $js;
    protected $css;
    protected $version;
    protected $math_renderer;
    
    public function __construct(
                    $type,
                                $custom_type,
                                $js,
                                $version
                        )
    {
                $this->type = $type;
                $this->custom_type = $custom_type;
                $this->js = $js;
                $this->version = $version;
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
     * @return custom_metadata $metadata \
     */
    public function get_metadata() {
        return $this->metadata;
    }

    /**
     * Set metadata \
     *  \
     * @param custom_metadata $metadata \
     */
    public function set_metadata (custom_metadata $metadata) {
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
     * @return custom_ui_style $ui_style \
     */
    public function get_ui_style() {
        return $this->ui_style;
    }

    /**
     * Set ui_style \
     *  \
     * @param custom_ui_style $ui_style \
     */
    public function set_ui_style (custom_ui_style $ui_style) {
        $this->ui_style = $ui_style;
    }

    /**
     * Get Custom type \
     * A key that identifies this custom type. \
     * @return string $custom_type \
     */
    public function get_custom_type() {
        return $this->custom_type;
    }

    /**
     * Set Custom type \
     * A key that identifies this custom type. \
     * @param string $custom_type \
     */
    public function set_custom_type ($custom_type) {
        $this->custom_type = $custom_type;
    }

    /**
     * Get JavaScript file \
     * A URL to a JavaScript file which defines an AMD module for the question. See this <a href="https://docs.learnosity.com/a
	 * ssessment/questions/knowledgebase/customquestions">knowledgebase article</a> for more information. \
     * @return string $js \
     */
    public function get_js() {
        return $this->js;
    }

    /**
     * Set JavaScript file \
     * A URL to a JavaScript file which defines an AMD module for the question. See this <a href="https://docs.learnosity.com/a
	 * ssessment/questions/knowledgebase/customquestions">knowledgebase article</a> for more information. \
     * @param string $js \
     */
    public function set_js ($js) {
        $this->js = $js;
    }

    /**
     * Get CSS file \
     * A URL to a CSS file containing styles for the question. \
     * @return string $css \
     */
    public function get_css() {
        return $this->css;
    }

    /**
     * Set CSS file \
     * A URL to a CSS file containing styles for the question. \
     * @param string $css \
     */
    public function set_css ($css) {
        $this->css = $css;
    }

    /**
     * Get Version \
     * A number that identifies the version of the question e.g. v0.1.0. \
     * @return string $version \
     */
    public function get_version() {
        return $this->version;
    }

    /**
     * Set Version \
     * A number that identifies the version of the question e.g. v0.1.0. \
     * @param string $version \
     */
    public function set_version ($version) {
        $this->version = $version;
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

