<?php

namespace LearnosityQti\Entities\QuestionTypes;

use LearnosityQti\Entities\BaseQuestionType;

/**
 * This class is auto-generated based on Schemas API and you should not modify its content
 * Metadata: {"responses":"v2.228.0","feedback":"v2.71.0","features":"v2.208.0"}
 */
class drawing extends BaseQuestionType {
    protected $is_math;
    protected $metadata;
    protected $stimulus;
    protected $stimulus_review;
    protected $instructor_stimulus;
    protected $type;
    protected $ui_style;
    protected $validation;
    protected $character_map;
    protected $image;
    protected $drawing_tools;
    protected $text_formatting_options;
    protected $line_color;
    protected $line_width;
    protected $spellcheck;
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
     * @return drawing_metadata $metadata \
     */
    public function get_metadata() {
        return $this->metadata;
    }

    /**
     * Set metadata \
     *  \
     * @param drawing_metadata $metadata \
     */
    public function set_metadata (drawing_metadata $metadata) {
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
     * @return drawing_ui_style $ui_style \
     */
    public function get_ui_style() {
        return $this->ui_style;
    }

    /**
     * Set ui_style \
     *  \
     * @param drawing_ui_style $ui_style \
     */
    public function set_ui_style (drawing_ui_style $ui_style) {
        $this->ui_style = $ui_style;
    }

    /**
     * Get Validation \
     * In this section, configure the correct answer(s) for the question. \
     * @return drawing_validation $validation \
     */
    public function get_validation() {
        return $this->validation;
    }

    /**
     * Set Validation \
     * In this section, configure the correct answer(s) for the question. \
     * @param drawing_validation $validation \
     */
    public function set_validation (drawing_validation $validation) {
        $this->validation = $validation;
    }

    /**
     * Get Special characters \
     * The character map will display the <a data-toggle="modal" href="#charMapDefault">default set of special characters</a>.<
	 * br/>
If an Array, the character map button will show and display only the array of characters.<br><span class="label la
	 * bel-important">IMPORTANT</span>The HTML document will require a charset of utf-8: <code>&lt;meta charset="utf-8"&gt;</co
	 * de> \
     * @return charmap $character_map \
     */
    public function get_character_map() {
        return $this->character_map;
    }

    /**
     * Set Special characters \
     * The character map will display the <a data-toggle="modal" href="#charMapDefault">default set of special characters</a>.<
	 * br/>
If an Array, the character map button will show and display only the array of characters.<br><span class="label la
	 * bel-important">IMPORTANT</span>The HTML document will require a charset of utf-8: <code>&lt;meta charset="utf-8"&gt;</co
	 * de> \
     * @param charmap $character_map \
     */
    public function set_character_map ($character_map) {
        $this->character_map = $character_map;
    }

    /**
     * Get Add image \
     * The absolute URL of the background image. \
     * @return drawing_image $image \
     */
    public function get_image() {
        return $this->image;
    }

    /**
     * Set Add image \
     * The absolute URL of the background image. \
     * @param drawing_image $image \
     */
    public function set_image (drawing_image $image) {
        $this->image = $image;
    }

    /**
     * Get Toggle toolbar options \
     * Select tools to toggle them on or off. Drag them to rearrange toolbar order \
     * @return array $drawing_tools \
     */
    public function get_drawing_tools() {
        return $this->drawing_tools;
    }

    /**
     * Set Toggle toolbar options \
     * Select tools to toggle them on or off. Drag them to rearrange toolbar order \
     * @param array $drawing_tools \
     */
    public function set_drawing_tools (array $drawing_tools) {
        $this->drawing_tools = $drawing_tools;
    }

    /**
     * Get Text formatting options \
     * Select Text formating tools under the Text tool. \
     * @return array $text_formatting_options \
     */
    public function get_text_formatting_options() {
        return $this->text_formatting_options;
    }

    /**
     * Set Text formatting options \
     * Select Text formating tools under the Text tool. \
     * @param array $text_formatting_options \
     */
    public function set_text_formatting_options (array $text_formatting_options) {
        $this->text_formatting_options = $text_formatting_options;
    }

    /**
     * Get Drawing line and text color \
     * Defines the color and opacity of the drawn line. \
     * @return array $line_color \
     */
    public function get_line_color() {
        return $this->line_color;
    }

    /**
     * Set Drawing line and text color \
     * Defines the color and opacity of the drawn line. \
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

