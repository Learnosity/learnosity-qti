<?php

namespace LearnosityQti\Entities\QuestionTypes;

use LearnosityQti\Entities\BaseQuestionType;

/**
 * This class is auto-generated based on Schemas API and you should not modify its content
 * Metadata: {"responses":"v2.228.0","feedback":"v2.71.0","features":"v2.208.0"}
 */
class clozeinlinetext extends BaseQuestionType {
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
    protected $description;
    protected $template;
    protected $character_map;
    protected $max_length;
    protected $spellcheck;
    protected $case_sensitive;
    protected $math_renderer;
    
    public function __construct(
                    $type,
                                $template
                        )
    {
                $this->type = $type;
                $this->template = $template;
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
     * @return clozeinlinetext_metadata $metadata \
     */
    public function get_metadata() {
        return $this->metadata;
    }

    /**
     * Set metadata \
     *  \
     * @param clozeinlinetext_metadata $metadata \
     */
    public function set_metadata (clozeinlinetext_metadata $metadata) {
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
     * @return clozeinlinetext_ui_style $ui_style \
     */
    public function get_ui_style() {
        return $this->ui_style;
    }

    /**
     * Set ui_style \
     *  \
     * @param clozeinlinetext_ui_style $ui_style \
     */
    public function set_ui_style (clozeinlinetext_ui_style $ui_style) {
        $this->ui_style = $ui_style;
    }

    /**
     * Get Check answer attempts \
     * If instant_feedback is true, this field determines how many times the user can click on the 'Check Answer' button. 0 mea
	 * ns unlimited. \
     * @return number $feedback_attempts \
     */
    public function get_feedback_attempts() {
        return $this->feedback_attempts;
    }

    /**
     * Set Check answer attempts \
     * If instant_feedback is true, this field determines how many times the user can click on the 'Check Answer' button. 0 mea
	 * ns unlimited. \
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
     * @return clozeinlinetext_validation $validation \
     */
    public function get_validation() {
        return $this->validation;
    }

    /**
     * Set Set correct answer(s) \
     * In this section, configure the correct answer(s) for the question. \
     * @param clozeinlinetext_validation $validation \
     */
    public function set_validation (clozeinlinetext_validation $validation) {
        $this->validation = $validation;
    }

    /**
     * Get Response container (global) \
     * Use Response Container (global) to make changes to all response boxes at once. \
     * @return clozeinlinetext_response_container $response_container \
     */
    public function get_response_container() {
        return $this->response_container;
    }

    /**
     * Set Response container (global) \
     * Use Response Container (global) to make changes to all response boxes at once. \
     * @param clozeinlinetext_response_container $response_container \
     */
    public function set_response_container (clozeinlinetext_response_container $response_container) {
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
     * Get Description (deprecated) \
     * <span class="label label-danger">Deprecated</span> See <em>stimulus_review</em>. <br />
Description of the question and
	 *  its context to be displayed. 
It <a data-toggle="modal" href="#supportedTags">supports HTML entities</a>. \
     * @return string $description \
     */
    public function get_description() {
        return $this->description;
    }

    /**
     * Set Description (deprecated) \
     * <span class="label label-danger">Deprecated</span> See <em>stimulus_review</em>. <br />
Description of the question and
	 *  its context to be displayed. 
It <a data-toggle="modal" href="#supportedTags">supports HTML entities</a>. \
     * @param string $description \
     */
    public function set_description ($description) {
        $this->description = $description;
    }

    /**
     * Get Template \
     * Text, tables, images and response boxes can be added to this area. Click on the Insert Response button in the Rich Text 
	 * Editor to add a response box, or press the underscore button on your keyboard twice as a shortcut. \
     * @return string $template \
     */
    public function get_template() {
        return $this->template;
    }

    /**
     * Set Template \
     * Text, tables, images and response boxes can be added to this area. Click on the Insert Response button in the Rich Text 
	 * Editor to add a response box, or press the underscore button on your keyboard twice as a shortcut. \
     * @param string $template \
     */
    public function set_template ($template) {
        $this->template = $template;
    }

    /**
     * Get Special characters \
     * If true, the character map button will display within the short text field. The character map will display the <a data-t
	 * oggle="modal" href="#charMapDefault">default set of special characters</a>.<br/>
If an Array, the character map button 
	 * will show and display only the array of characters.<br><span class="label label-important">IMPORTANT</span> The HTML doc
	 * ument will require a charset of utf-8: <code>&lt;meta charset="utf-8"&gt;</code> \
     * @return  $character_map \
     */
    public function get_character_map() {
        return $this->character_map;
    }

    /**
     * Set Special characters \
     * If true, the character map button will display within the short text field. The character map will display the <a data-t
	 * oggle="modal" href="#charMapDefault">default set of special characters</a>.<br/>
If an Array, the character map button 
	 * will show and display only the array of characters.<br><span class="label label-important">IMPORTANT</span> The HTML doc
	 * ument will require a charset of utf-8: <code>&lt;meta charset="utf-8"&gt;</code> \
     * @param  $character_map \
     */
    public function set_character_map ($character_map) {
        $this->character_map = $character_map;
    }

    /**
     * Get Maximum length (characters) \
     * Maximum number of characters that can be entered in the field. Maximum value is 250. For longer questions use longtext t
	 * ype. \
     * @return number $max_length \
     */
    public function get_max_length() {
        return $this->max_length;
    }

    /**
     * Set Maximum length (characters) \
     * Maximum number of characters that can be entered in the field. Maximum value is 250. For longer questions use longtext t
	 * ype. \
     * @param number $max_length \
     */
    public function set_max_length ($max_length) {
        $this->max_length = $max_length;
    }

    /**
     * Get Spellcheck and text correction \
     * Control the input/textarea attributes spellcheck, autocapitalize, autocomplete and autocorrect. See <a href="https://dev
	 * eloper.mozilla.org/en-US/docs/Web/HTML/Global_attributes/spellcheck">"Spell check MDN"</a>. <a href="https://developer.m
	 * ozilla.org/en-US/docs/Web/HTML/Global_attributes/autocapitalize">"Autocapitalize MDN"</a>. <a href="https://developer.mo
	 * zilla.org/en-US/docs/Web/HTML/Attributes/autocomplete">"Autocomplete MDN"</a> <a href="https://developer.apple.com/libra
	 * ry/archive/documentation/AppleApplications/Reference/SafariHTMLRef/Articles/Attributes.html">" Autocorrect "</a>. Note t
	 * hese are browser features and may not always be available. \
     * @return boolean $spellcheck \
     */
    public function get_spellcheck() {
        return $this->spellcheck;
    }

    /**
     * Set Spellcheck and text correction \
     * Control the input/textarea attributes spellcheck, autocapitalize, autocomplete and autocorrect. See <a href="https://dev
	 * eloper.mozilla.org/en-US/docs/Web/HTML/Global_attributes/spellcheck">"Spell check MDN"</a>. <a href="https://developer.m
	 * ozilla.org/en-US/docs/Web/HTML/Global_attributes/autocapitalize">"Autocapitalize MDN"</a>. <a href="https://developer.mo
	 * zilla.org/en-US/docs/Web/HTML/Attributes/autocomplete">"Autocomplete MDN"</a> <a href="https://developer.apple.com/libra
	 * ry/archive/documentation/AppleApplications/Reference/SafariHTMLRef/Articles/Attributes.html">" Autocorrect "</a>. Note t
	 * hese are browser features and may not always be available. \
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

