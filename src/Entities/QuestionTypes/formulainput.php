<?php

namespace Learnosity\Entities\QuestionTypes;

use Learnosity\Entities\BaseQuestionType;

/**
* This class is auto-generated based on Schemas API and you should not modify its content
* Metadata: {"responses":"v2.72.0","feedback":"v2.71.0","features":"v2.72.0"}
*/
class formulainput extends BaseQuestionType {
    protected $type;
    protected $metadata;
    protected $handwriting_recognises;
    protected $showHints;
    protected $value;
    protected $symbols;
    protected $container;
    protected $ui_style;
    protected $input;
    
    public function __construct(
                    $type,
                                formulainput_ui_style $ui_style
                        )
    {
                $this->type = $type;
                $this->ui_style = $ui_style;
            }

    /**
    * Get Feature Type \
    *  \
    * @return string $type \
    */
    public function get_type() {
        return $this->type;
    }

    /**
    * Set Feature Type \
    *  \
    * @param string $type \
    */
    public function set_type ($type) {
        $this->type = $type;
    }

    /**
    * Get metadata \
    *  \
    * @return object $metadata \
    */
    public function get_metadata() {
        return $this->metadata;
    }

    /**
    * Set metadata \
    *  \
    * @param object $metadata \
    */
    public function set_metadata ($metadata) {
        $this->metadata = $metadata;
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
    * Get Show Hints \
    * Whether to show hints or not \
    * @return boolean $showHints \
    */
    public function get_showHints() {
        return $this->showHints;
    }

    /**
    * Set Show Hints \
    * Whether to show hints or not \
    * @param boolean $showHints \
    */
    public function set_showHints ($showHints) {
        $this->showHints = $showHints;
    }

    /**
    * Get Initial Value \
    * LaTeX math to display when rendered. \
    * @return string $value \
    */
    public function get_value() {
        return $this->value;
    }

    /**
    * Set Initial Value \
    * LaTeX math to display when rendered. \
    * @param string $value \
    */
    public function set_value ($value) {
        $this->value = $value;
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

    /**
    * Get Container (global) \
    * Object that defines styles for the input container. \
    * @return formulainput_container $container \
    */
    public function get_container() {
        return $this->container;
    }

    /**
    * Set Container (global) \
    * Object that defines styles for the input container. \
    * @param formulainput_container $container \
    */
    public function set_container (formulainput_container $container) {
        $this->container = $container;
    }

    /**
    * Get UI Style \
    * Object used to control different aspects of the UI \
    * @return formulainput_ui_style $ui_style \
    */
    public function get_ui_style() {
        return $this->ui_style;
    }

    /**
    * Set UI Style \
    * Object used to control different aspects of the UI \
    * @param formulainput_ui_style $ui_style \
    */
    public function set_ui_style (formulainput_ui_style $ui_style) {
        $this->ui_style = $ui_style;
    }

    /**
    * Get Input Selector \
    * CSS selector for input elements which will receive the entered LaTeX math. \
    * @return string $input \
    */
    public function get_input() {
        return $this->input;
    }

    /**
    * Set Input Selector \
    * CSS selector for input elements which will receive the entered LaTeX math. \
    * @param string $input \
    */
    public function set_input ($input) {
        $this->input = $input;
    }

    
    public function get_widget_type() {
    return 'feature';
    }
}

