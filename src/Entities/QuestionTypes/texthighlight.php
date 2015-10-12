<?php

namespace LearnosityQti\Entities\QuestionTypes;

use LearnosityQti\Entities\BaseQuestionType;

/**
* This class is auto-generated based on Schemas API and you should not modify its content
* Metadata: {"responses":"v2.72.0","feedback":"v2.71.0","features":"v2.72.0"}
*/
class texthighlight extends BaseQuestionType {
    protected $is_math;
    protected $metadata;
    protected $stimulus;
    protected $stimulus_review;
    protected $type;
    protected $ui_style;
    protected $description;
    protected $template;
    protected $word_bound;
    protected $drag_selection;
    protected $validation;
    
    public function __construct(
                    $type,
                                $template
                        )
    {
                $this->type = $type;
                $this->template = $template;
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
    * @return texthighlight_metadata $metadata \
    */
    public function get_metadata() {
        return $this->metadata;
    }

    /**
    * Set metadata \
    *  \
    * @param texthighlight_metadata $metadata \
    */
    public function set_metadata (texthighlight_metadata $metadata) {
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
    * @return texthighlight_ui_style $ui_style \
    */
    public function get_ui_style() {
        return $this->ui_style;
    }

    /**
    * Set ui_style \
    *  \
    * @param texthighlight_ui_style $ui_style \
    */
    public function set_ui_style (texthighlight_ui_style $ui_style) {
        $this->ui_style = $ui_style;
    }

    /**
    * Get Description (deprecated) \
    * <span class="label label-danger">Deprecated</span> See <em>stimulus_review</em>. <br />
Description of the question and
	 its context to be displayed. 
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
	 its context to be displayed. 
It <a data-toggle="modal" href="#supportedTags">supports HTML entities</a>. \
    * @param string $description \
    */
    public function set_description ($description) {
        $this->description = $description;
    }

    /**
    * Get Template \
    * A string containing markup to be highlighted. <a data-toggle="modal" href="#supportedClozeTemplateTags">HTML supported t
	ags</a>. Surround valid responses within a valid tag for automated marking. \
    * @return string $template \
    */
    public function get_template() {
        return $this->template;
    }

    /**
    * Set Template \
    * A string containing markup to be highlighted. <a data-toggle="modal" href="#supportedClozeTemplateTags">HTML supported t
	ags</a>. Surround valid responses within a valid tag for automated marking. \
    * @param string $template \
    */
    public function set_template ($template) {
        $this->template = $template;
    }

    /**
    * Get Snap to word? \
    * Highlighting partial words automatically highlights the nearest full word(s) \
    * @return boolean $word_bound \
    */
    public function get_word_bound() {
        return $this->word_bound;
    }

    /**
    * Set Snap to word? \
    * Highlighting partial words automatically highlights the nearest full word(s) \
    * @param boolean $word_bound \
    */
    public function set_word_bound ($word_bound) {
        $this->word_bound = $word_bound;
    }

    /**
    * Get Drag Selection? \
    * Setting this to false will force the user to only be able to select a single word and dragging to select multiple words 
	will be disabled. \
    * @return boolean $drag_selection \
    */
    public function get_drag_selection() {
        return $this->drag_selection;
    }

    /**
    * Set Drag Selection? \
    * Setting this to false will force the user to only be able to select a single word and dragging to select multiple words 
	will be disabled. \
    * @param boolean $drag_selection \
    */
    public function set_drag_selection ($drag_selection) {
        $this->drag_selection = $drag_selection;
    }

    /**
    * Get validation \
    * Validation object that includes options on how this question will be automarked \
    * @return texthighlight_validation $validation \
    */
    public function get_validation() {
        return $this->validation;
    }

    /**
    * Set validation \
    * Validation object that includes options on how this question will be automarked \
    * @param texthighlight_validation $validation \
    */
    public function set_validation (texthighlight_validation $validation) {
        $this->validation = $validation;
    }

    
    public function get_widget_type() {
    return 'response';
    }
}

