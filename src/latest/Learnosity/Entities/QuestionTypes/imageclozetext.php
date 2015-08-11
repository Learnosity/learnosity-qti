<?php

namespace Learnosity\Entities\QuestionTypes;

use Learnosity\Entities\BaseQuestionType;

/**
* This class is auto-generated based on Schemas API and you should not modify its content
* Metadata: {"responses":"v2.68.0","feedback":"v2.35.0","features":"v2.68.0"}
*/
class imageclozetext extends BaseQuestionType {
    protected $image;
    protected $response_positions;
    protected $response_container;
    protected $response_containers;
    protected $is_math;
    protected $math_renderer;
    protected $metadata;
    protected $stimulus;
    protected $stimulus_review;
    protected $type;
    protected $ui_style;
    protected $feedback_attempts;
    protected $instant_feedback;
    protected $validation;
    protected $img_src;
    protected $max_length;
    protected $character_map;
    protected $multiple_line;
    protected $spellcheck;
    protected $case_sensitive;
    
    public function __construct(
                    imageclozetext_image $image,
                                array $response_positions,
                                $type
                        )
    {
                $this->image = $image;
                $this->response_positions = $response_positions;
                $this->type = $type;
            }

    /**
    * Get Image parameters \
    * Defines the attributes/metadata for the image \
    * @return imageclozetext_image $image \
    */
    public function get_image() {
        return $this->image;
    }

    /**
    * Set Image parameters \
    * Defines the attributes/metadata for the image \
    * @param imageclozetext_image $image \
    */
    public function set_image (imageclozetext_image $image) {
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
    * Object that defines styles for the response container. \
    * @return imageclozetext_response_container $response_container \
    */
    public function get_response_container() {
        return $this->response_container;
    }

    /**
    * Set Response Container (global) \
    * Object that defines styles for the response container. \
    * @param imageclozetext_response_container $response_container \
    */
    public function set_response_container (imageclozetext_response_container $response_container) {
        $this->response_container = $response_container;
    }

    /**
    * Get Response Container (individual) \
    * Array containing objects defining each individual response container style. \
    * @return array $response_containers \
    */
    public function get_response_containers() {
        return $this->response_containers;
    }

    /**
    * Set Response Container (individual) \
    * Array containing objects defining each individual response container style. \
    * @param array $response_containers \
    */
    public function set_response_containers (array $response_containers) {
        $this->response_containers = $response_containers;
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
    * Get Math renderer \
    * Choose the rendering engine for math content within a question. Default rendering engine is MathJax with MathQuill for i
	nput areas. If you change this option to "MathQuill", MathQuill will render all math content within a question. \
    * @return string $math_renderer \
    */
    public function get_math_renderer() {
        return $this->math_renderer;
    }

    /**
    * Set Math renderer \
    * Choose the rendering engine for math content within a question. Default rendering engine is MathJax with MathQuill for i
	nput areas. If you change this option to "MathQuill", MathQuill will render all math content within a question. \
    * @param string $math_renderer \
    */
    public function set_math_renderer ($math_renderer) {
        $this->math_renderer = $math_renderer;
    }

    /**
    * Get metadata \
    *  \
    * @return imageclozetext_metadata $metadata \
    */
    public function get_metadata() {
        return $this->metadata;
    }

    /**
    * Set metadata \
    *  \
    * @param imageclozetext_metadata $metadata \
    */
    public function set_metadata (imageclozetext_metadata $metadata) {
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
    * @return imageclozetext_ui_style $ui_style \
    */
    public function get_ui_style() {
        return $this->ui_style;
    }

    /**
    * Set ui_style \
    *  \
    * @param imageclozetext_ui_style $ui_style \
    */
    public function set_ui_style (imageclozetext_ui_style $ui_style) {
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
    * @return imageclozetext_validation $validation \
    */
    public function get_validation() {
        return $this->validation;
    }

    /**
    * Set validation \
    * Validation object that includes options on how this question will be automarked \
    * @param imageclozetext_validation $validation \
    */
    public function set_validation (imageclozetext_validation $validation) {
        $this->validation = $validation;
    }

    /**
    * Get Image URI \
    * Absolute URI for the background image. \
    * @return string $img_src \
    */
    public function get_img_src() {
        return $this->img_src;
    }

    /**
    * Set Image URI \
    * Absolute URI for the background image. \
    * @param string $img_src \
    */
    public function set_img_src ($img_src) {
        $this->img_src = $img_src;
    }

    /**
    * Get Maximum Length (characters) \
    * Maximum number of characters that can be entered in the field. Maximum value is 250. \
    * @return number $max_length \
    */
    public function get_max_length() {
        return $this->max_length;
    }

    /**
    * Set Maximum Length (characters) \
    * Maximum number of characters that can be entered in the field. Maximum value is 250. \
    * @param number $max_length \
    */
    public function set_max_length ($max_length) {
        $this->max_length = $max_length;
    }

    /**
    * Get Character Map \
    * If true, the character map button will display within the text field. The character map will display the <a data-toggle=
	"modal" href="#charMapDefault">default set of special characters</a>.<br/>
If an Array, the character map button will s
	how and display only the array of characters.<br><span class="label label-important">IMPORTANT</span>The HTML document w
	ill require a charset of utf-8: <code>&lt;meta charset="utf-8"&gt;</code> \
    * @return  $character_map \
    */
    public function get_character_map() {
        return $this->character_map;
    }

    /**
    * Set Character Map \
    * If true, the character map button will display within the text field. The character map will display the <a data-toggle=
	"modal" href="#charMapDefault">default set of special characters</a>.<br/>
If an Array, the character map button will s
	how and display only the array of characters.<br><span class="label label-important">IMPORTANT</span>The HTML document w
	ill require a charset of utf-8: <code>&lt;meta charset="utf-8"&gt;</code> \
    * @param  $character_map \
    */
    public function set_character_map ($character_map) {
        $this->character_map = $character_map;
    }

    /**
    * Get Multiple Line \
    * If true the response input will be a text area supporting multiple lines of input. If false the response input will be a
	 text input only supporting one line responses. \
    * @return boolean $multiple_line \
    */
    public function get_multiple_line() {
        return $this->multiple_line;
    }

    /**
    * Set Multiple Line \
    * If true the response input will be a text area supporting multiple lines of input. If false the response input will be a
	 text input only supporting one line responses. \
    * @param boolean $multiple_line \
    */
    public function set_multiple_line ($multiple_line) {
        $this->multiple_line = $multiple_line;
    }

    /**
    * Get Browser Spellcheck \
    * Control the input/textarea attribute spellcheck. See <a href="http://dev.w3.org/html5/spec/single-page.html?utm_source=d
	lvr.it&utm_medium=feed#attr-spellcheck">"W3C article"</a>. Note this is a browser feature and may not always be availabl
	e. \
    * @return boolean $spellcheck \
    */
    public function get_spellcheck() {
        return $this->spellcheck;
    }

    /**
    * Set Browser Spellcheck \
    * Control the input/textarea attribute spellcheck. See <a href="http://dev.w3.org/html5/spec/single-page.html?utm_source=d
	lvr.it&utm_medium=feed#attr-spellcheck">"W3C article"</a>. Note this is a browser feature and may not always be availabl
	e. \
    * @param boolean $spellcheck \
    */
    public function set_spellcheck ($spellcheck) {
        $this->spellcheck = $spellcheck;
    }

    /**
    * Get Case Sensitive? \
    * If true, responses will be compared against valid_responses considering the letters' case. \
    * @return boolean $case_sensitive \
    */
    public function get_case_sensitive() {
        return $this->case_sensitive;
    }

    /**
    * Set Case Sensitive? \
    * If true, responses will be compared against valid_responses considering the letters' case. \
    * @param boolean $case_sensitive \
    */
    public function set_case_sensitive ($case_sensitive) {
        $this->case_sensitive = $case_sensitive;
    }

    
    public function get_widget_type() {
    return 'response';
    }
}

