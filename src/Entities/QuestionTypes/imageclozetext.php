<?php

namespace LearnosityQti\Entities\QuestionTypes;

use LearnosityQti\Entities\BaseQuestionType;

/**
* This class is auto-generated based on Schemas API and you should not modify its content
* Metadata: {"responses":"v2.107.0","feedback":"v2.71.0","features":"v2.107.0"}
*/
class imageclozetext extends BaseQuestionType {
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
    protected $response_container;
    protected $response_containers;
    protected $image;
    protected $response_positions;
    protected $aria_labels;
    protected $img_src;
    protected $max_length;
    protected $character_map;
    protected $multiple_line;
    protected $spellcheck;
    protected $case_sensitive;
    
    public function __construct(
                    $type,
                                imageclozetext_image $image,
                                array $response_positions
                        )
    {
                $this->type = $type;
                $this->image = $image;
                $this->response_positions = $response_positions;
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
    * @return imageclozetext_validation $validation \
    */
    public function get_validation() {
        return $this->validation;
    }

    /**
    * Set Set correct answer(s) \
    * In this section, configure the correct answer(s) for the question. \
    * @param imageclozetext_validation $validation \
    */
    public function set_validation (imageclozetext_validation $validation) {
        $this->validation = $validation;
    }

    /**
    * Get Response container (global) \
    * Use Response Container (global) to make changes to all response boxes at once. \
    * @return imageclozetext_response_container $response_container \
    */
    public function get_response_container() {
        return $this->response_container;
    }

    /**
    * Set Response container (global) \
    * Use Response Container (global) to make changes to all response boxes at once. \
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
    * Get Image settings \
    * Image settings are defined here. \
    * @return imageclozetext_image $image \
    */
    public function get_image() {
        return $this->image;
    }

    /**
    * Set Image settings \
    * Image settings are defined here. \
    * @param imageclozetext_image $image \
    */
    public function set_image (imageclozetext_image $image) {
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

    /**
    * Get Edit ARIA labels \
    * Text entered here will help assistive technology (e.g. a screen reader) attach a label to the image for accessibility pu
	rposes. \
    * @return array $aria_labels \
    */
    public function get_aria_labels() {
        return $this->aria_labels;
    }

    /**
    * Set Edit ARIA labels \
    * Text entered here will help assistive technology (e.g. a screen reader) attach a label to the image for accessibility pu
	rposes. \
    * @param array $aria_labels \
    */
    public function set_aria_labels (array $aria_labels) {
        $this->aria_labels = $aria_labels;
    }

    /**
    * Get Add image \
    * Absolute URI for the background image. \
    * @return string $img_src \
    */
    public function get_img_src() {
        return $this->img_src;
    }

    /**
    * Set Add image \
    * Absolute URI for the background image. \
    * @param string $img_src \
    */
    public function set_img_src ($img_src) {
        $this->img_src = $img_src;
    }

    /**
    * Get Character limit \
    * Maximum number of characters that can be entered in the field. Maximum value is 250. \
    * @return number $max_length \
    */
    public function get_max_length() {
        return $this->max_length;
    }

    /**
    * Set Character limit \
    * Maximum number of characters that can be entered in the field. Maximum value is 250. \
    * @param number $max_length \
    */
    public function set_max_length ($max_length) {
        $this->max_length = $max_length;
    }

    /**
    * Get Special characters \
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
    * Set Special characters \
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
    * Get Multiple line \
    * If true the response input will be a text area supporting multiple lines of input. If false the response input will be a
	 text input only supporting one line responses. \
    * @return boolean $multiple_line \
    */
    public function get_multiple_line() {
        return $this->multiple_line;
    }

    /**
    * Set Multiple line \
    * If true the response input will be a text area supporting multiple lines of input. If false the response input will be a
	 text input only supporting one line responses. \
    * @param boolean $multiple_line \
    */
    public function set_multiple_line ($multiple_line) {
        $this->multiple_line = $multiple_line;
    }

    /**
    * Get Browser spellcheck \
    * Control the input/textarea attribute spellcheck. See <a href="http://dev.w3.org/html5/spec/single-page.html?utm_source=d
	lvr.it&utm_medium=feed#attr-spellcheck">"W3C article"</a>. Note this is a browser feature and may not always be availabl
	e. \
    * @return boolean $spellcheck \
    */
    public function get_spellcheck() {
        return $this->spellcheck;
    }

    /**
    * Set Browser spellcheck \
    * Control the input/textarea attribute spellcheck. See <a href="http://dev.w3.org/html5/spec/single-page.html?utm_source=d
	lvr.it&utm_medium=feed#attr-spellcheck">"W3C article"</a>. Note this is a browser feature and may not always be availabl
	e. \
    * @param boolean $spellcheck \
    */
    public function set_spellcheck ($spellcheck) {
        $this->spellcheck = $spellcheck;
    }

    /**
    * Get Case sensitive \
    * If true, responses will be compared against valid_responses considering the letters' case. \
    * @return boolean $case_sensitive \
    */
    public function get_case_sensitive() {
        return $this->case_sensitive;
    }

    /**
    * Set Case sensitive \
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

