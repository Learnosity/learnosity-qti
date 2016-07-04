<?php

namespace LearnosityQti\Entities\QuestionTypes;

use LearnosityQti\Entities\BaseQuestionType;

/**
 * This class is auto-generated based on Schemas API and you should not modify its content
 * Metadata: {"responses":"v2.86.0","feedback":"v2.71.0","features":"v2.84.0"}
 */
class plaintext extends BaseQuestionType
{
    protected $is_math;
    protected $metadata;
    protected $stimulus;
    protected $stimulus_review;
    protected $type;
    protected $ui_style;
    protected $validation;
    protected $description;
    protected $character_map;
    protected $max_length;
    protected $show_copy;
    protected $show_cut;
    protected $show_paste;
    protected $show_word_limit;
    protected $submit_over_limit;
    protected $spellcheck;
    protected $placeholder;

    public function __construct(
        $type
    ) {
        $this->type = $type;
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
     * @return plaintext_metadata $metadata \
     */
    public function get_metadata()
    {
        return $this->metadata;
    }

    /**
     * Set metadata \
     *  \
     *
     * @param plaintext_metadata $metadata \
     */
    public function set_metadata(plaintext_metadata $metadata)
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
     * Get UI style \
     * User Interface style \
     *
     * @return plaintext_ui_style $ui_style \
     */
    public function get_ui_style()
    {
        return $this->ui_style;
    }

    /**
     * Set UI style \
     * User Interface style \
     *
     * @param plaintext_ui_style $ui_style \
     */
    public function set_ui_style(plaintext_ui_style $ui_style)
    {
        $this->ui_style = $ui_style;
    }

    /**
     * Get Validation \
     * Validation object that includes guidelines on for how this question should be marked. \
     *
     * @return plaintext_validation $validation \
     */
    public function get_validation()
    {
        return $this->validation;
    }

    /**
     * Set Validation \
     * Validation object that includes guidelines on for how this question should be marked. \
     *
     * @param plaintext_validation $validation \
     */
    public function set_validation(plaintext_validation $validation)
    {
        $this->validation = $validation;
    }

    /**
     * Get Description (deprecated) \
     * <span class="label label-danger">Deprecated</span> See <em>stimulus_review</em>. <br />
     * Description of the question and
     * its context to be displayed.
     * It <a data-toggle="modal" href="#supportedTags">supports HTML entities</a>. \
     *
     * @return string $description \
     */
    public function get_description()
    {
        return $this->description;
    }

    /**
     * Set Description (deprecated) \
     * <span class="label label-danger">Deprecated</span> See <em>stimulus_review</em>. <br />
     * Description of the question and
     * its context to be displayed.
     * It <a data-toggle="modal" href="#supportedTags">supports HTML entities</a>. \
     *
     * @param string $description \
     */
    public function set_description($description)
    {
        $this->description = $description;
    }

    /**
     * Get Character Map \
     * If true, the character map button will display within the short text field. The character map will display the <a data-t
     * oggle="modal" href="#charMapDefault">default set of special characters</a>.<br/>
     * If an Array, the character map button
     * will show and display only the array of characters.<br><span class="label label-important">IMPORTANT</span> The HTML doc
     * ument will require a charset of utf-8: <code>&lt;meta charset="utf-8"&gt;</code> \
     *
     * @return  $character_map \
     */
    public function get_character_map()
    {
        return $this->character_map;
    }

    /**
     * Set Character Map \
     * If true, the character map button will display within the short text field. The character map will display the <a data-t
     * oggle="modal" href="#charMapDefault">default set of special characters</a>.<br/>
     * If an Array, the character map button
     * will show and display only the array of characters.<br><span class="label label-important">IMPORTANT</span> The HTML doc
     * ument will require a charset of utf-8: <code>&lt;meta charset="utf-8"&gt;</code> \
     *
     * @param  $character_map \
     */
    public function set_character_map($character_map)
    {
        $this->character_map = $character_map;
    }

    /**
     * Get Maximum Length (words) \
     * Maximum number of words that can be entered in the field. Maximum: 100,000 chars ~ 10,000 words <span class="label label
     * -warning">To Be Confirmed</span> \
     *
     * @return number $max_length \
     */
    public function get_max_length()
    {
        return $this->max_length;
    }

    /**
     * Set Maximum Length (words) \
     * Maximum number of words that can be entered in the field. Maximum: 100,000 chars ~ 10,000 words <span class="label label
     * -warning">To Be Confirmed</span> \
     *
     * @param number $max_length \
     */
    public function set_max_length($max_length)
    {
        $this->max_length = $max_length;
    }

    /**
     * Get Show Copy Button \
     * When this is true the Copy Button is shown in the plain text toolbar. \
     *
     * @return boolean $show_copy \
     */
    public function get_show_copy()
    {
        return $this->show_copy;
    }

    /**
     * Set Show Copy Button \
     * When this is true the Copy Button is shown in the plain text toolbar. \
     *
     * @param boolean $show_copy \
     */
    public function set_show_copy($show_copy)
    {
        $this->show_copy = $show_copy;
    }

    /**
     * Get Show Cut Button \
     * When this is true the Cut button is shown in the plain text toolbar. \
     *
     * @return boolean $show_cut \
     */
    public function get_show_cut()
    {
        return $this->show_cut;
    }

    /**
     * Set Show Cut Button \
     * When this is true the Cut button is shown in the plain text toolbar. \
     *
     * @param boolean $show_cut \
     */
    public function set_show_cut($show_cut)
    {
        $this->show_cut = $show_cut;
    }

    /**
     * Get Show Paste Button \
     * When this is true the Paste button is shown in the plain text toolbar. \
     *
     * @return boolean $show_paste \
     */
    public function get_show_paste()
    {
        return $this->show_paste;
    }

    /**
     * Set Show Paste Button \
     * When this is true the Paste button is shown in the plain text toolbar. \
     *
     * @param boolean $show_paste \
     */
    public function set_show_paste($show_paste)
    {
        $this->show_paste = $show_paste;
    }

    /**
     * Get Word Limit Setting \
     * Determines how the word limit UI will display. Options are the following strings: <br /><strong>"always"</strong>: Word
     * limit is always shown and updated as the user types <br /> <strong>"on-limit"</strong>: Word limit is only displayed whe
     * n the word limit is exceeded <br /> <strong>"off"</strong>: No word limit it shown. \
     *
     * @return  $show_word_limit \
     */
    public function get_show_word_limit()
    {
        return $this->show_word_limit;
    }

    /**
     * Set Word Limit Setting \
     * Determines how the word limit UI will display. Options are the following strings: <br /><strong>"always"</strong>: Word
     * limit is always shown and updated as the user types <br /> <strong>"on-limit"</strong>: Word limit is only displayed whe
     * n the word limit is exceeded <br /> <strong>"off"</strong>: No word limit it shown. \
     *
     * @param  $show_word_limit \
     */
    public function set_show_word_limit($show_word_limit)
    {
        $this->show_word_limit = $show_word_limit;
    }

    /**
     * Get Submit over limit? \
     * Determines if the user is able to save/submit when the word limit has been exceeded. \
     *
     * @return boolean $submit_over_limit \
     */
    public function get_submit_over_limit()
    {
        return $this->submit_over_limit;
    }

    /**
     * Set Submit over limit? \
     * Determines if the user is able to save/submit when the word limit has been exceeded. \
     *
     * @param boolean $submit_over_limit \
     */
    public function set_submit_over_limit($submit_over_limit)
    {
        $this->submit_over_limit = $submit_over_limit;
    }

    /**
     * Get Browser Spellcheck \
     * Control the input/textarea attribute spellcheck. See <a href="http://dev.w3.org/html5/spec/single-page.html?utm_source=d
     * lvr.it&utm_medium=feed#attr-spellcheck">"W3C article"</a>. Note this is a browser feature and may not always be availabl
     * e. \
     *
     * @return boolean $spellcheck \
     */
    public function get_spellcheck()
    {
        return $this->spellcheck;
    }

    /**
     * Set Browser Spellcheck \
     * Control the input/textarea attribute spellcheck. See <a href="http://dev.w3.org/html5/spec/single-page.html?utm_source=d
     * lvr.it&utm_medium=feed#attr-spellcheck">"W3C article"</a>. Note this is a browser feature and may not always be availabl
     * e. \
     *
     * @param boolean $spellcheck \
     */
    public function set_spellcheck($spellcheck)
    {
        $this->spellcheck = $spellcheck;
    }

    /**
     * Get Placeholder \
     * Text to display as a hint to the user of what to enter \
     *
     * @return string $placeholder \
     */
    public function get_placeholder()
    {
        return $this->placeholder;
    }

    /**
     * Set Placeholder \
     * Text to display as a hint to the user of what to enter \
     *
     * @param string $placeholder \
     */
    public function set_placeholder($placeholder)
    {
        $this->placeholder = $placeholder;
    }


    public function get_widget_type()
    {
        return 'response';
    }
}

