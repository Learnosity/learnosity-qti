<?php

namespace Learnosity\Entities\QuestionTypes;

use Learnosity\Entities\BaseQuestionTypeAttribute;

/**
* This class is auto-generated based on Schemas API and you should not modify its content
* Metadata: {"responses":"v2.68.0","feedback":"v2.35.0","features":"v2.68.0"}
*/
class chemistryessay_ui_style extends BaseQuestionTypeAttribute {
    protected $fontsize;
    protected $response_font_scale;
    protected $default_mode;
    protected $max_lines;
    protected $text_formatting_options;
    
    public function __construct(
            )
    {
            }

    /**
    * Get Font size \
    * Controls the size of base font for this question. Options are among 'small', 'normal', 'large', 'xlarge' and 'xxlarge'. \
    * @return string $fontsize ie. small, normal, large, xlarge, xxlarge  \
    */
    public function get_fontsize() {
        return $this->fontsize;
    }

    /**
    * Set Font size \
    * Controls the size of base font for this question. Options are among 'small', 'normal', 'large', 'xlarge' and 'xxlarge'. \
    * @param string $fontsize ie. small, normal, large, xlarge, xxlarge  \
    */
    public function set_fontsize ($fontsize) {
        $this->fontsize = $fontsize;
    }

    /**
    * Get Response font scale \
    * This scales the font relative to the question's font size. \
    * @return string $response_font_scale \
    */
    public function get_response_font_scale() {
        return $this->response_font_scale;
    }

    /**
    * Set Response font scale \
    * This scales the font relative to the question's font size. \
    * @param string $response_font_scale \
    */
    public function set_response_font_scale ($response_font_scale) {
        $this->response_font_scale = $response_font_scale;
    }

    /**
    * Get Default mode \
    * The default mode of the first line when question is first focused \
    * @return string $default_mode \
    */
    public function get_default_mode() {
        return $this->default_mode;
    }

    /**
    * Set Default mode \
    * The default mode of the first line when question is first focused \
    * @param string $default_mode \
    */
    public function set_default_mode ($default_mode) {
        $this->default_mode = $default_mode;
    }

    /**
    * Get Maximum lines \
    * Limits the number of lines of text / math that can be entered. \
    * @return number $max_lines \
    */
    public function get_max_lines() {
        return $this->max_lines;
    }

    /**
    * Set Maximum lines \
    * Limits the number of lines of text / math that can be entered. \
    * @param number $max_lines \
    */
    public function set_max_lines ($max_lines) {
        $this->max_lines = $max_lines;
    }

    /**
    * Get Text Formatting Options \
    * An array containing strings of text formatting options to make available. \
    * @return array $text_formatting_options \
    */
    public function get_text_formatting_options() {
        return $this->text_formatting_options;
    }

    /**
    * Set Text Formatting Options \
    * An array containing strings of text formatting options to make available. \
    * @param array $text_formatting_options \
    */
    public function set_text_formatting_options (array $text_formatting_options) {
        $this->text_formatting_options = $text_formatting_options;
    }

    
}

