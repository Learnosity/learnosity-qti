<?php

namespace LearnosityQti\Entities\QuestionTypes;

use LearnosityQti\Entities\BaseQuestionType;

/**
* This class is auto-generated based on Schemas API and you should not modify its content
* Metadata: {"responses":"v2.107.0","feedback":"v2.71.0","features":"v2.107.0"}
*/
class imageclozeformula extends BaseQuestionType {
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
    protected $numberPad;
    protected $equiv_literal_legacy;
    protected $response_containers;
    protected $response_container;
    protected $showHints;
    protected $symbols;
    protected $image;
    protected $response_positions;
    
    public function __construct(
                    $type,
                                array $response_containers,
                                imageclozeformula_image $image,
                                array $response_positions
                        )
    {
                $this->type = $type;
                $this->response_containers = $response_containers;
                $this->image = $image;
                $this->response_positions = $response_positions;
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
    * @return imageclozeformula_metadata $metadata \
    */
    public function get_metadata() {
        return $this->metadata;
    }

    /**
    * Set metadata \
    *  \
    * @param imageclozeformula_metadata $metadata \
    */
    public function set_metadata (imageclozeformula_metadata $metadata) {
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
    * @return imageclozeformula_ui_style $ui_style \
    */
    public function get_ui_style() {
        return $this->ui_style;
    }

    /**
    * Set ui_style \
    *  \
    * @param imageclozeformula_ui_style $ui_style \
    */
    public function set_ui_style (imageclozeformula_ui_style $ui_style) {
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
    * @return imageclozeformula_validation $validation \
    */
    public function get_validation() {
        return $this->validation;
    }

    /**
    * Set Set correct answer(s) \
    * In this section, configure the correct answer(s) for the question. \
    * @param imageclozeformula_validation $validation \
    */
    public function set_validation (imageclozeformula_validation $validation) {
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
    * Get Use legacy equivLiteral rules \
    * Allows equivLiteral (2)(x) evaluate to true. \
    * @return boolean $equiv_literal_legacy \
    */
    public function get_equiv_literal_legacy() {
        return $this->equiv_literal_legacy;
    }

    /**
    * Set Use legacy equivLiteral rules \
    * Allows equivLiteral (2)(x) evaluate to true. \
    * @param boolean $equiv_literal_legacy \
    */
    public function set_equiv_literal_legacy ($equiv_literal_legacy) {
        $this->equiv_literal_legacy = $equiv_literal_legacy;
    }

    /**
    * Get Response Containers (individual) \
    * Configure attributes, such as Height and Width, for each response container individually. \
    * @return array $response_containers \
    */
    public function get_response_containers() {
        return $this->response_containers;
    }

    /**
    * Set Response Containers (individual) \
    * Configure attributes, such as Height and Width, for each response container individually. \
    * @param array $response_containers \
    */
    public function set_response_containers (array $response_containers) {
        $this->response_containers = $response_containers;
    }

    /**
    * Get Response containers \
    * Define the template and dimensions for all response containers \
    * @return imageclozeformula_response_container $response_container \
    */
    public function get_response_container() {
        return $this->response_container;
    }

    /**
    * Set Response containers \
    * Define the template and dimensions for all response containers \
    * @param imageclozeformula_response_container $response_container \
    */
    public function set_response_container (imageclozeformula_response_container $response_container) {
        $this->response_container = $response_container;
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
    * Get Image settings \
    * Image settings are defined here. \
    * @return imageclozeformula_image $image \
    */
    public function get_image() {
        return $this->image;
    }

    /**
    * Set Image settings \
    * Image settings are defined here. \
    * @param imageclozeformula_image $image \
    */
    public function set_image (imageclozeformula_image $image) {
        $this->image = $image;
    }

    /**
    * Get Possible responses \
    * Array of responsePosition objects (x and y) indicating the distance of the top left corner of the response field from th
	e top left corner of the image. \
    * @return array $response_positions \
    */
    public function get_response_positions() {
        return $this->response_positions;
    }

    /**
    * Set Possible responses \
    * Array of responsePosition objects (x and y) indicating the distance of the top left corner of the response field from th
	e top left corner of the image. \
    * @param array $response_positions \
    */
    public function set_response_positions (array $response_positions) {
        $this->response_positions = $response_positions;
    }

    
    public function get_widget_type() {
    return 'response';
    }
}

