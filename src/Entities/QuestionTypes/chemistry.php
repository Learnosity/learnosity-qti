<?php

namespace LearnosityQti\Entities\QuestionTypes;

use LearnosityQti\Entities\BaseQuestionType;

/**
* This class is auto-generated based on Schemas API and you should not modify its content
* Metadata: {"responses":"v2.108.0","feedback":"v2.71.0","features":"v2.107.0"}
*/
class chemistry extends BaseQuestionType {
    protected $text_blocks;
    protected $is_math;
    protected $metadata;
    protected $stimulus;
    protected $stimulus_review;
    protected $instructor_stimulus;
    protected $type;
    protected $ui_style;
    protected $feedback_attempts;
    protected $instant_feedback;
    protected $validation;
    protected $handwriting_recognises;
    protected $template;
    protected $showHints;
    protected $numberPad;
    protected $symbols;
    protected $response_container;
    protected $response_containers;
    
    public function __construct(
                    $type,
                                chemistry_ui_style $ui_style
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
    * @return chemistry_metadata $metadata \
    */
    public function get_metadata() {
        return $this->metadata;
    }

    /**
    * Set metadata \
    *  \
    * @param chemistry_metadata $metadata \
    */
    public function set_metadata (chemistry_metadata $metadata) {
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
    * @return chemistry_ui_style $ui_style \
    */
    public function get_ui_style() {
        return $this->ui_style;
    }

    /**
    * Set ui_style \
    *  \
    * @param chemistry_ui_style $ui_style \
    */
    public function set_ui_style (chemistry_ui_style $ui_style) {
        $this->ui_style = $ui_style;
    }

    /**
    * Get Check answer attempts \
    * If instant_feedback is true, this field determines how many times the user can click on the 'Check Answer' button. 0 mea
	ns unlimited. \
    * @return number $feedback_attempts \
    */
    public function get_feedback_attempts() {
        return $this->feedback_attempts;
    }

    /**
    * Set Check answer attempts \
    * If instant_feedback is true, this field determines how many times the user can click on the 'Check Answer' button. 0 mea
	ns unlimited. \
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
    * Get Set correct answer(s) \
    * In this section, configure the correct answer(s) for the question. \
    * @return chemistry_validation $validation \
    */
    public function get_validation() {
        return $this->validation;
    }

    /**
    * Set Set correct answer(s) \
    * In this section, configure the correct answer(s) for the question. \
    * @param chemistry_validation $validation \
    */
    public function set_validation (chemistry_validation $validation) {
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
    * Get Template \
    * A string containing latex math to be rendered on initialization. The template markup tag {{response}} is also supported.
	 If present, only the {{response}} areas will be editable. \
    * @return string $template \
    */
    public function get_template() {
        return $this->template;
    }

    /**
    * Set Template \
    * A string containing latex math to be rendered on initialization. The template markup tag {{response}} is also supported.
	 If present, only the {{response}} areas will be editable. \
    * @param string $template \
    */
    public function set_template ($template) {
        $this->template = $template;
    }

    /**
    * Get Show keypad hints \
    * Disables hint, including keyboard shortcuts and group titles, shown on the keyboard's top left corner when hovering over
	 a symbol group key. \
    * @return boolean $showHints \
    */
    public function get_showHints() {
        return $this->showHints;
    }

    /**
    * Set Show keypad hints \
    * Disables hint, including keyboard shortcuts and group titles, shown on the keyboard's top left corner when hovering over
	 a symbol group key. \
    * @param boolean $showHints \
    */
    public function set_showHints ($showHints) {
        $this->showHints = $showHints;
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
    * Get Symbol group(s) \
    * An array containing either strings or a nested objects of symbol definitions. \
    * @return array $symbols \
    */
    public function get_symbols() {
        return $this->symbols;
    }

    /**
    * Set Symbol group(s) \
    * An array containing either strings or a nested objects of symbol definitions. \
    * @param array $symbols \
    */
    public function set_symbols (array $symbols) {
        $this->symbols = $symbols;
    }

    /**
    * Get Response container (global) \
    * Object that defines styles for the response container. \
    * @return chemistry_response_container $response_container \
    */
    public function get_response_container() {
        return $this->response_container;
    }

    /**
    * Set Response container (global) \
    * Object that defines styles for the response container. \
    * @param chemistry_response_container $response_container \
    */
    public function set_response_container (chemistry_response_container $response_container) {
        $this->response_container = $response_container;
    }

    /**
    * Get Response container (individual) \
    * Array containing objects defining each individual response container style. \
    * @return array $response_containers \
    */
    public function get_response_containers() {
        return $this->response_containers;
    }

    /**
    * Set Response container (individual) \
    * Array containing objects defining each individual response container style. \
    * @param array $response_containers \
    */
    public function set_response_containers (array $response_containers) {
        $this->response_containers = $response_containers;
    }

    
    public function get_widget_type() {
    return 'response';
    }
}

