<?php

namespace Learnosity\Entities\QuestionTypes;

use Learnosity\Entities\BaseQuestionType;

/**
* This class is auto-generated based on Schemas API and you should not modify its content
* Metadata: {"responses":"v2.72.0","feedback":"v2.71.0","features":"v2.72.0"}
*/
class imageclozeformula extends BaseQuestionType {
    protected $image;
    protected $response_positions;
    protected $response_container;
    protected $response_containers;
    protected $handwriting_recognises;
    protected $is_math;
    protected $metadata;
    protected $stimulus;
    protected $stimulus_review;
    protected $type;
    protected $ui_style;
    protected $feedback_attempts;
    protected $instant_feedback;
    protected $validation;
    protected $text_blocks;
    protected $showHints;
    protected $symbols;
    
    public function __construct(
                    imageclozeformula_image $image,
                                array $response_positions,
                                array $response_containers,
                                $type
                        )
    {
                $this->image = $image;
                $this->response_positions = $response_positions;
                $this->response_containers = $response_containers;
                $this->type = $type;
            }

    /**
    * Get Image parameters \
    * Defines the attributes/metadata for the image \
    * @return imageclozeformula_image $image \
    */
    public function get_image() {
        return $this->image;
    }

    /**
    * Set Image parameters \
    * Defines the attributes/metadata for the image \
    * @param imageclozeformula_image $image \
    */
    public function set_image (imageclozeformula_image $image) {
        $this->image = $image;
    }

    /**
    * Get Response Positions \
    * Array of responsePosition objects (x and y) indicating the distance of the top left corner of the response field from th
	e top left corner of the image. \
    * @return array $response_positions \
    */
    public function get_response_positions() {
        return $this->response_positions;
    }

    /**
    * Set Response Positions \
    * Array of responsePosition objects (x and y) indicating the distance of the top left corner of the response field from th
	e top left corner of the image. \
    * @param array $response_positions \
    */
    public function set_response_positions (array $response_positions) {
        $this->response_positions = $response_positions;
    }

    /**
    * Get Response Container (global) \
    * Define the template and dimensions for all response containers \
    * @return imageclozeformula_response_container $response_container \
    */
    public function get_response_container() {
        return $this->response_container;
    }

    /**
    * Set Response Container (global) \
    * Define the template and dimensions for all response containers \
    * @param imageclozeformula_response_container $response_container \
    */
    public function set_response_container (imageclozeformula_response_container $response_container) {
        $this->response_container = $response_container;
    }

    /**
    * Get Response Containers (individual) \
    * Nest response containers within the question template's response areas \
    * @return array $response_containers \
    */
    public function get_response_containers() {
        return $this->response_containers;
    }

    /**
    * Set Response Containers (individual) \
    * Nest response containers within the question template's response areas \
    * @param array $response_containers \
    */
    public function set_response_containers (array $response_containers) {
        $this->response_containers = $response_containers;
    }

    /**
    * Get Handwriting Recognises \
    * A string with the name of one of the available math grammar sets. \
    * @return string $handwriting_recognises ie. standard, mathbasic  \
    */
    public function get_handwriting_recognises() {
        return $this->handwriting_recognises;
    }

    /**
    * Set Handwriting Recognises \
    * A string with the name of one of the available math grammar sets. \
    * @param string $handwriting_recognises ie. standard, mathbasic  \
    */
    public function set_handwriting_recognises ($handwriting_recognises) {
        $this->handwriting_recognises = $handwriting_recognises;
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
    * @return imageclozeformula_validation $validation \
    */
    public function get_validation() {
        return $this->validation;
    }

    /**
    * Set validation \
    * Validation object that includes options on how this question will be automarked \
    * @param imageclozeformula_validation $validation \
    */
    public function set_validation (imageclozeformula_validation $validation) {
        $this->validation = $validation;
    }

    /**
    * Get Text blocks \
    * List of custom text blocks. Maximum length 9 characters. \
    * @return array $text_blocks \
    */
    public function get_text_blocks() {
        return $this->text_blocks;
    }

    /**
    * Set Text blocks \
    * List of custom text blocks. Maximum length 9 characters. \
    * @param array $text_blocks \
    */
    public function set_text_blocks (array $text_blocks) {
        $this->text_blocks = $text_blocks;
    }

    /**
    * Get Show Hints \
    * Disables hint, including keyboard shortcuts and group titles, shown on the keyboard's top left corner when hovering over
	 a symbol group key. \
    * @return boolean $showHints \
    */
    public function get_showHints() {
        return $this->showHints;
    }

    /**
    * Set Show Hints \
    * Disables hint, including keyboard shortcuts and group titles, shown on the keyboard's top left corner when hovering over
	 a symbol group key. \
    * @param boolean $showHints \
    */
    public function set_showHints ($showHints) {
        $this->showHints = $showHints;
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

