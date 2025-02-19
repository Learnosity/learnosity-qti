<?php

namespace LearnosityQti\Entities\QuestionTypes;

use LearnosityQti\Entities\BaseQuestionType;

/**
 * This class is auto-generated based on Schemas API and you should not modify its content
 * Metadata: {"responses":"v2.228.0","feedback":"v2.71.0","features":"v2.208.0"}
 */
class formulaessay extends BaseQuestionType {
    protected $text_blocks;
    protected $is_math;
    protected $metadata;
    protected $stimulus;
    protected $stimulus_review;
    protected $instructor_stimulus;
    protected $type;
    protected $ui_style;
    protected $validation;
    protected $handwriting_recognises;
    protected $showHints;
    protected $horizontal_layout;
    protected $spellcheck;
    protected $response_container;
    protected $math_renderer;
    protected $numberPad;
    protected $symbols;
    
    public function __construct(
                    $type,
                                formulaessay_ui_style $ui_style
                        )
    {
                $this->type = $type;
                $this->ui_style = $ui_style;
            }

    /**
     * Get Custom units \
     * List of custom text blocks. Maximum length 9 characters. \
     * @return array $text_blocks \
     */
    public function get_text_blocks() {
        return $this->text_blocks;
    }

    /**
     * Set Custom units \
     * List of custom text blocks. Maximum length 9 characters. \
     * @param array $text_blocks \
     */
    public function set_text_blocks (array $text_blocks) {
        $this->text_blocks = $text_blocks;
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
     * @return formulaessay_metadata $metadata \
     */
    public function get_metadata() {
        return $this->metadata;
    }

    /**
     * Set metadata \
     *  \
     * @param formulaessay_metadata $metadata \
     */
    public function set_metadata (formulaessay_metadata $metadata) {
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
     * @return formulaessay_ui_style $ui_style \
     */
    public function get_ui_style() {
        return $this->ui_style;
    }

    /**
     * Set ui_style \
     *  \
     * @param formulaessay_ui_style $ui_style \
     */
    public function set_ui_style (formulaessay_ui_style $ui_style) {
        $this->ui_style = $ui_style;
    }

    /**
     * Get Validation \
     * In this section, configure the correct answer(s) for the question. \
     * @return formulaessay_validation $validation \
     */
    public function get_validation() {
        return $this->validation;
    }

    /**
     * Set Validation \
     * In this section, configure the correct answer(s) for the question. \
     * @param formulaessay_validation $validation \
     */
    public function set_validation (formulaessay_validation $validation) {
        $this->validation = $validation;
    }

    /**
     * Get Handwriting recognises \
     * A string with the name of one of the available math grammar sets. \
     * @return string $handwriting_recognises ie. standard, mathbasic  \
     */
    public function get_handwriting_recognises() {
        return $this->handwriting_recognises;
    }

    /**
     * Set Handwriting recognises \
     * A string with the name of one of the available math grammar sets. \
     * @param string $handwriting_recognises ie. standard, mathbasic  \
     */
    public function set_handwriting_recognises ($handwriting_recognises) {
        $this->handwriting_recognises = $handwriting_recognises;
    }

    /**
     * Get Show keypad hints \
     * Disables hint, including keyboard shortcuts and group titles, shown on the keyboard's top left corner when hovering over
	 *  a symbol group key. \
     * @return boolean $showHints \
     */
    public function get_showHints() {
        return $this->showHints;
    }

    /**
     * Set Show keypad hints \
     * Disables hint, including keyboard shortcuts and group titles, shown on the keyboard's top left corner when hovering over
	 *  a symbol group key. \
     * @param boolean $showHints \
     */
    public function set_showHints ($showHints) {
        $this->showHints = $showHints;
    }

    /**
     * Get Enable horizontal keypad \
     * Enables a horizontal layout with ten columns and two rows. \
     * @return boolean $horizontal_layout \
     */
    public function get_horizontal_layout() {
        return $this->horizontal_layout;
    }

    /**
     * Set Enable horizontal keypad \
     * Enables a horizontal layout with ten columns and two rows. \
     * @param boolean $horizontal_layout \
     */
    public function set_horizontal_layout ($horizontal_layout) {
        $this->horizontal_layout = $horizontal_layout;
    }

    /**
     * Get Browser spellcheck \
     * Control the input/textarea attribute spellcheck. See <a href="http://dev.w3.org/html5/spec/single-page.html?utm_source=d
	 * lvr.it&utm_medium=feed#attr-spellcheck">"W3C article"</a>. Note this is a browser feature and may not always be availabl
	 * e. \
     * @return boolean $spellcheck \
     */
    public function get_spellcheck() {
        return $this->spellcheck;
    }

    /**
     * Set Browser spellcheck \
     * Control the input/textarea attribute spellcheck. See <a href="http://dev.w3.org/html5/spec/single-page.html?utm_source=d
	 * lvr.it&utm_medium=feed#attr-spellcheck">"W3C article"</a>. Note this is a browser feature and may not always be availabl
	 * e. \
     * @param boolean $spellcheck \
     */
    public function set_spellcheck ($spellcheck) {
        $this->spellcheck = $spellcheck;
    }

    /**
     * Get Response container (global) \
     * Object that defines styles for the response container. \
     * @return formulaessay_response_container $response_container \
     */
    public function get_response_container() {
        return $this->response_container;
    }

    /**
     * Set Response container (global) \
     * Object that defines styles for the response container. \
     * @param formulaessay_response_container $response_container \
     */
    public function set_response_container (formulaessay_response_container $response_container) {
        $this->response_container = $response_container;
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

    /**
     * Get Number pad \
     *  \
     * @return array $numberPad \
     */
    public function get_numberPad() {
        return $this->numberPad;
    }

    /**
     * Set Number pad \
     *  \
     * @param array $numberPad \
     */
    public function set_numberPad (array $numberPad) {
        $this->numberPad = $numberPad;
    }

    /**
     * Get Symbols \
     * An array containing either strings or a nested objects of symbol definitions. \
     * @return array $symbols \
     */
    public function get_symbols() {
        return $this->symbols;
    }

    /**
     * Set Symbols \
     * An array containing either strings or a nested objects of symbol definitions. \
     * @param array $symbols \
     */
    public function set_symbols (array $symbols) {
        $this->symbols = $symbols;
    }

    
    public function get_widget_type() {
    return 'response';
    }
}

