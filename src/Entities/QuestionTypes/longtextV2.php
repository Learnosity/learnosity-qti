<?php

namespace LearnosityQti\Entities\QuestionTypes;

use LearnosityQti\Entities\BaseQuestionType;

/**
 * This class is auto-generated based on Schemas API and you should not modify its content
 * Metadata: {"responses":"v2.228.0","feedback":"v2.71.0","features":"v2.208.0"}
 */
class longtextV2 extends BaseQuestionType {
    protected $is_math;
    protected $metadata;
    protected $stimulus;
    protected $stimulus_review;
    protected $instructor_stimulus;
    protected $type;
    protected $ui_style;
    protected $validation;
    protected $description;
    protected $formatting_options;
    protected $max_length;
    protected $character_map;
    protected $spellcheck;
    protected $disable_auto_link;
    protected $submit_over_limit;
    protected $placeholder;
    protected $show_word_limit;
    protected $text_blocks;
    protected $show_word_count;
    protected $showHints;
    protected $horizontal_layout;
    protected $math_renderer;
    protected $numberPad;
    protected $symbols;
    
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
     * @return longtextV2_metadata $metadata \
     */
    public function get_metadata() {
        return $this->metadata;
    }

    /**
     * Set metadata \
     *  \
     * @param longtextV2_metadata $metadata \
     */
    public function set_metadata (longtextV2_metadata $metadata) {
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
     * @return longtextV2_ui_style $ui_style \
     */
    public function get_ui_style() {
        return $this->ui_style;
    }

    /**
     * Set ui_style \
     *  \
     * @param longtextV2_ui_style $ui_style \
     */
    public function set_ui_style (longtextV2_ui_style $ui_style) {
        $this->ui_style = $ui_style;
    }

    /**
     * Get Validation \
     * In this section, configure the correct answer(s) for the question. \
     * @return longtextV2_validation $validation \
     */
    public function get_validation() {
        return $this->validation;
    }

    /**
     * Set Validation \
     * In this section, configure the correct answer(s) for the question. \
     * @param longtextV2_validation $validation \
     */
    public function set_validation (longtextV2_validation $validation) {
        $this->validation = $validation;
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
     * Get Text formatting options \
     * An array containing strings of text formatting options to make available. Available options: <ul><li><code>bold</code></
	 * li><li><code>italic</code></li><li><code>underline</code></li><li><code>unorderedList</code></li><li><code>orderedList</
	 * code></li><li><code>removeFormat</code></li><li><code>codeInline</code></li><li><code>codeEditor</code></li><li><code>al
	 * ignLeft</code></li><li><code>alignCenter</code></li><li><code>alignRight</code></li><li><code>alignJustify</code></li><l
	 * i><code>superscript</code></li><li><code>subscript</code></li><li><code>indentIncrease</code></li><li><code>indentDecrea
	 * se</code></li><li><code>textDirectionLtr</code></li><li><code>textDirectionRtl</code></li><li><code>insertHorizontalRule
	 * </code></li><li><code>table</code></li><li><code>charactermap</code></li><li><code>image</code></li><li><code>undo</code
	 * ></li><li><code>redo</code></li></ul> \
     * @return array $formatting_options \
     */
    public function get_formatting_options() {
        return $this->formatting_options;
    }

    /**
     * Set Text formatting options \
     * An array containing strings of text formatting options to make available. Available options: <ul><li><code>bold</code></
	 * li><li><code>italic</code></li><li><code>underline</code></li><li><code>unorderedList</code></li><li><code>orderedList</
	 * code></li><li><code>removeFormat</code></li><li><code>codeInline</code></li><li><code>codeEditor</code></li><li><code>al
	 * ignLeft</code></li><li><code>alignCenter</code></li><li><code>alignRight</code></li><li><code>alignJustify</code></li><l
	 * i><code>superscript</code></li><li><code>subscript</code></li><li><code>indentIncrease</code></li><li><code>indentDecrea
	 * se</code></li><li><code>textDirectionLtr</code></li><li><code>textDirectionRtl</code></li><li><code>insertHorizontalRule
	 * </code></li><li><code>table</code></li><li><code>charactermap</code></li><li><code>image</code></li><li><code>undo</code
	 * ></li><li><code>redo</code></li></ul> \
     * @param array $formatting_options \
     */
    public function set_formatting_options (array $formatting_options) {
        $this->formatting_options = $formatting_options;
    }

    /**
     * Get Word limit \
     * Maximum number of words that can be entered in the field. Maximum: 100,000 chars ~ 10,000 words \
     * @return number $max_length \
     */
    public function get_max_length() {
        return $this->max_length;
    }

    /**
     * Set Word limit \
     * Maximum number of words that can be entered in the field. Maximum: 100,000 chars ~ 10,000 words \
     * @param number $max_length \
     */
    public function set_max_length ($max_length) {
        $this->max_length = $max_length;
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
     * Get Disable auto link \
     * Sets whether urls, entered by the user should automatically become clickable-links. \
     * @return boolean $disable_auto_link \
     */
    public function get_disable_auto_link() {
        return $this->disable_auto_link;
    }

    /**
     * Set Disable auto link \
     * Sets whether urls, entered by the user should automatically become clickable-links. \
     * @param boolean $disable_auto_link \
     */
    public function set_disable_auto_link ($disable_auto_link) {
        $this->disable_auto_link = $disable_auto_link;
    }

    /**
     * Get Submit over limit \
     * Determines if the user is able to save/submit when the word limit has been exceeded. \
     * @return boolean $submit_over_limit \
     */
    public function get_submit_over_limit() {
        return $this->submit_over_limit;
    }

    /**
     * Set Submit over limit \
     * Determines if the user is able to save/submit when the word limit has been exceeded. \
     * @param boolean $submit_over_limit \
     */
    public function set_submit_over_limit ($submit_over_limit) {
        $this->submit_over_limit = $submit_over_limit;
    }

    /**
     * Get Placeholder \
     * Placeholder text that can be added into the response entry area, which disappears when user starts typing. \
     * @return string $placeholder \
     */
    public function get_placeholder() {
        return $this->placeholder;
    }

    /**
     * Set Placeholder \
     * Placeholder text that can be added into the response entry area, which disappears when user starts typing. \
     * @param string $placeholder \
     */
    public function set_placeholder ($placeholder) {
        $this->placeholder = $placeholder;
    }

    /**
     * Get Word limit \
     * Determines how the word limit UI will display. Options are the following strings: <br /><strong>"always"</strong>: Word 
	 * limit is always shown and updated as the user types <br /> <strong>"on-limit"</strong>: Word limit is only displayed whe
	 * n the word limit is exceeded <br /> <strong>"off"</strong>: No word limit it shown. \
     * @return string $show_word_limit ie. always, on-limit, off  \
     */
    public function get_show_word_limit() {
        return $this->show_word_limit;
    }

    /**
     * Set Word limit \
     * Determines how the word limit UI will display. Options are the following strings: <br /><strong>"always"</strong>: Word 
	 * limit is always shown and updated as the user types <br /> <strong>"on-limit"</strong>: Word limit is only displayed whe
	 * n the word limit is exceeded <br /> <strong>"off"</strong>: No word limit it shown. \
     * @param string $show_word_limit ie. always, on-limit, off  \
     */
    public function set_show_word_limit ($show_word_limit) {
        $this->show_word_limit = $show_word_limit;
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
     * Get Show word count \
     * Displays the word count underneath the entry dialog. \
     * @return boolean $show_word_count \
     */
    public function get_show_word_count() {
        return $this->show_word_count;
    }

    /**
     * Set Show word count \
     * Displays the word count underneath the entry dialog. \
     * @param boolean $show_word_count \
     */
    public function set_show_word_count ($show_word_count) {
        $this->show_word_count = $show_word_count;
    }

    /**
     * Get Show keypad hints \
     * Disables hints, including keyboard shortcuts and group titles, shown on the keypad's top left corner when hovering over 
	 * a symbol group key. \
     * @return boolean $showHints \
     */
    public function get_showHints() {
        return $this->showHints;
    }

    /**
     * Set Show keypad hints \
     * Disables hints, including keyboard shortcuts and group titles, shown on the keypad's top left corner when hovering over 
	 * a symbol group key. \
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

