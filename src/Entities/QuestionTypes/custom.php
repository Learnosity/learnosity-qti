<?php

namespace LearnosityQti\Entities\QuestionTypes;

use LearnosityQti\Entities\BaseQuestionType;

/**
 * This class is auto-generated based on Schemas API and you should not modify its content
 * Metadata: {"responses":"v2.86.0","feedback":"v2.71.0","features":"v2.84.0"}
 */
class custom extends BaseQuestionType
{
    protected $is_math;
    protected $metadata;
    protected $stimulus;
    protected $stimulus_review;
    protected $type;
    protected $ui_style;
    protected $custom_type;
    protected $js;
    protected $css;
    protected $version;

    public function __construct(
        $type,
        $custom_type,
        $js,
        $version
    ) {
        $this->type        = $type;
        $this->custom_type = $custom_type;
        $this->js          = $js;
        $this->version     = $version;
    }

    /**
     * Get Has Mathematical Formulas \
     * Set to <strong>true</strong> to have LaTeX or MathML contents to be rendered with mathjax. \
     *
     * @return boolean $is_math \
     */
    public function get_is_math()
    {
        return $this->is_math;
    }

    /**
     * Set Has Mathematical Formulas \
     * Set to <strong>true</strong> to have LaTeX or MathML contents to be rendered with mathjax. \
     *
     * @param boolean $is_math \
     */
    public function set_is_math($is_math)
    {
        $this->is_math = $is_math;
    }

    /**
     * Get metadata \
     *  \
     *
     * @return custom_metadata $metadata \
     */
    public function get_metadata()
    {
        return $this->metadata;
    }

    /**
     * Set metadata \
     *  \
     *
     * @param custom_metadata $metadata \
     */
    public function set_metadata(custom_metadata $metadata)
    {
        $this->metadata = $metadata;
    }

    /**
     * Get Stimulus \
     * <a data-toggle="modal" href="#supportedTags">HTML</a>/Text content displayed in all states (initial, resume, review) ren
     * dered <strong>above</strong> the response area. Supports embedded <a href="http://docs.learnosity.com/questionsapi/featu
     * retypes.php" target="_blank">Feature &lt;span&gt; tags</a>. \
     *
     * @return string $stimulus \
     */
    public function get_stimulus()
    {
        return $this->stimulus;
    }

    /**
     * Set Stimulus \
     * <a data-toggle="modal" href="#supportedTags">HTML</a>/Text content displayed in all states (initial, resume, review) ren
     * dered <strong>above</strong> the response area. Supports embedded <a href="http://docs.learnosity.com/questionsapi/featu
     * retypes.php" target="_blank">Feature &lt;span&gt; tags</a>. \
     *
     * @param string $stimulus \
     */
    public function set_stimulus($stimulus)
    {
        $this->stimulus = $stimulus;
    }

    /**
     * Get Stimulus in review \
     * <a data-toggle="modal" href="#supportedTags">HTML</a>/Text content displayed <strong>only</strong> in review state rende
     * red <strong>above</strong> the response area. Supports embedded <a href="http://docs.learnosity.com/questionsapi/feature
     * types.php" target="_blank">Feature &lt;span&gt; tags</a>. Will override stimulus in review state. \
     *
     * @return string $stimulus_review \
     */
    public function get_stimulus_review()
    {
        return $this->stimulus_review;
    }

    /**
     * Set Stimulus in review \
     * <a data-toggle="modal" href="#supportedTags">HTML</a>/Text content displayed <strong>only</strong> in review state rende
     * red <strong>above</strong> the response area. Supports embedded <a href="http://docs.learnosity.com/questionsapi/feature
     * types.php" target="_blank">Feature &lt;span&gt; tags</a>. Will override stimulus in review state. \
     *
     * @param string $stimulus_review \
     */
    public function set_stimulus_review($stimulus_review)
    {
        $this->stimulus_review = $stimulus_review;
    }

    /**
     * Get Question Type \
     *  \
     *
     * @return string $type \
     */
    public function get_type()
    {
        return $this->type;
    }

    /**
     * Set Question Type \
     *  \
     *
     * @param string $type \
     */
    public function set_type($type)
    {
        $this->type = $type;
    }

    /**
     * Get ui_style \
     *  \
     *
     * @return custom_ui_style $ui_style \
     */
    public function get_ui_style()
    {
        return $this->ui_style;
    }

    /**
     * Set ui_style \
     *  \
     *
     * @param custom_ui_style $ui_style \
     */
    public function set_ui_style(custom_ui_style $ui_style)
    {
        $this->ui_style = $ui_style;
    }

    /**
     * Get Custom type \
     * A key that identifies this custom type. \
     *
     * @return string $custom_type \
     */
    public function get_custom_type()
    {
        return $this->custom_type;
    }

    /**
     * Set Custom type \
     * A key that identifies this custom type. \
     *
     * @param string $custom_type \
     */
    public function set_custom_type($custom_type)
    {
        $this->custom_type = $custom_type;
    }

    /**
     * Get JavaScript file \
     * A URL to a JavaScript file which defines an AMD module for the question. See this <a href="//docs.learnosity.com/questio
     * nsapi/knowledgebase/customquestions.php">knowledgebase article</a> for more information. \
     *
     * @return string $js \
     */
    public function get_js()
    {
        return $this->js;
    }

    /**
     * Set JavaScript file \
     * A URL to a JavaScript file which defines an AMD module for the question. See this <a href="//docs.learnosity.com/questio
     * nsapi/knowledgebase/customquestions.php">knowledgebase article</a> for more information. \
     *
     * @param string $js \
     */
    public function set_js($js)
    {
        $this->js = $js;
    }

    /**
     * Get CSS file \
     * A URL to a CSS file containing styles for the question. \
     *
     * @return string $css \
     */
    public function get_css()
    {
        return $this->css;
    }

    /**
     * Set CSS file \
     * A URL to a CSS file containing styles for the question. \
     *
     * @param string $css \
     */
    public function set_css($css)
    {
        $this->css = $css;
    }

    /**
     * Get Version \
     * A number that identifies the version of the question e.g. v0.1.0. \
     *
     * @return string $version \
     */
    public function get_version()
    {
        return $this->version;
    }

    /**
     * Set Version \
     * A number that identifies the version of the question e.g. v0.1.0. \
     *
     * @param string $version \
     */
    public function set_version($version)
    {
        $this->version = $version;
    }


    public function get_widget_type()
    {
        return 'response';
    }
}

