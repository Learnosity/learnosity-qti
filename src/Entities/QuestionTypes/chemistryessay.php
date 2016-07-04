<?php

namespace LearnosityQti\Entities\QuestionTypes;

use LearnosityQti\Entities\BaseQuestionType;

/**
 * This class is auto-generated based on Schemas API and you should not modify its content
 * Metadata: {"responses":"v2.86.0","feedback":"v2.71.0","features":"v2.84.0"}
 */
class chemistryessay extends BaseQuestionType
{
    protected $handwriting_recognises;
    protected $is_math;
    protected $metadata;
    protected $stimulus;
    protected $stimulus_review;
    protected $type;
    protected $ui_style;
    protected $validation;
    protected $text_blocks;
    protected $showHints;
    protected $numberPad;
    protected $symbols;

    public function __construct(
        $type,
        chemistryessay_ui_style $ui_style
    ) {
        $this->type     = $type;
        $this->ui_style = $ui_style;
    }

    /**
     * Get Handwriting Recognises \
     * A string with the name of one of the available math grammar sets. \
     *
     * @return string $handwriting_recognises ie. standard, mathbasic  \
     */
    public function get_handwriting_recognises()
    {
        return $this->handwriting_recognises;
    }

    /**
     * Set Handwriting Recognises \
     * A string with the name of one of the available math grammar sets. \
     *
     * @param string $handwriting_recognises ie. standard, mathbasic  \
     */
    public function set_handwriting_recognises($handwriting_recognises)
    {
        $this->handwriting_recognises = $handwriting_recognises;
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
     * @return chemistryessay_metadata $metadata \
     */
    public function get_metadata()
    {
        return $this->metadata;
    }

    /**
     * Set metadata \
     *  \
     *
     * @param chemistryessay_metadata $metadata \
     */
    public function set_metadata(chemistryessay_metadata $metadata)
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
     * Get ui_style \
     *  \
     *
     * @return chemistryessay_ui_style $ui_style \
     */
    public function get_ui_style()
    {
        return $this->ui_style;
    }

    /**
     * Set ui_style \
     *  \
     *
     * @param chemistryessay_ui_style $ui_style \
     */
    public function set_ui_style(chemistryessay_ui_style $ui_style)
    {
        $this->ui_style = $ui_style;
    }

    /**
     * Get Validation \
     * Validation object that includes guidelines on for how this question should be marked. \
     *
     * @return chemistryessay_validation $validation \
     */
    public function get_validation()
    {
        return $this->validation;
    }

    /**
     * Set Validation \
     * Validation object that includes guidelines on for how this question should be marked. \
     *
     * @param chemistryessay_validation $validation \
     */
    public function set_validation(chemistryessay_validation $validation)
    {
        $this->validation = $validation;
    }

    /**
     * Get Text blocks \
     * List of custom text blocks. Maximum length 9 characters. \
     *
     * @return array $text_blocks \
     */
    public function get_text_blocks()
    {
        return $this->text_blocks;
    }

    /**
     * Set Text blocks \
     * List of custom text blocks. Maximum length 9 characters. \
     *
     * @param array $text_blocks \
     */
    public function set_text_blocks(array $text_blocks)
    {
        $this->text_blocks = $text_blocks;
    }

    /**
     * Get Show Hints \
     * Disables hint, including keyboard shortcuts and group titles, shown on the keyboard's top left corner when hovering over
     * a symbol group key. \
     *
     * @return boolean $showHints \
     */
    public function get_showHints()
    {
        return $this->showHints;
    }

    /**
     * Set Show Hints \
     * Disables hint, including keyboard shortcuts and group titles, shown on the keyboard's top left corner when hovering over
     * a symbol group key. \
     *
     * @param boolean $showHints \
     */
    public function set_showHints($showHints)
    {
        $this->showHints = $showHints;
    }

    /**
     * Get Custom Number Pad \
     *  \
     *
     * @return array $numberPad \
     */
    public function get_numberPad()
    {
        return $this->numberPad;
    }

    /**
     * Set Custom Number Pad \
     *  \
     *
     * @param array $numberPad \
     */
    public function set_numberPad(array $numberPad)
    {
        $this->numberPad = $numberPad;
    }

    /**
     * Get Symbols \
     * An array containing either strings or a nested objects of symbol definitions. \
     *
     * @return array $symbols \
     */
    public function get_symbols()
    {
        return $this->symbols;
    }

    /**
     * Set Symbols \
     * An array containing either strings or a nested objects of symbol definitions. \
     *
     * @param array $symbols \
     */
    public function set_symbols(array $symbols)
    {
        $this->symbols = $symbols;
    }


    public function get_widget_type()
    {
        return 'response';
    }
}

