<?php

namespace LearnosityQti\Entities\QuestionTypes;

use LearnosityQti\Entities\BaseQuestionTypeAttribute;

/**
* This class is auto-generated based on Schemas API and you should not modify its content
* Metadata: {"responses":"v2.107.0","feedback":"v2.71.0","features":"v2.107.0"}
*/
class formulaV2_ui_style extends BaseQuestionTypeAttribute {
    protected $fontsize;
    protected $response_font_scale;
    protected $type;
    protected $min_width;
    protected $transparent_background;
    
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
    * Get Template font scale \
    * This scales the font relative to the question's font size. \
    * @return string $response_font_scale \
    */
    public function get_response_font_scale() {
        return $this->response_font_scale;
    }

    /**
    * Set Template font scale \
    * This scales the font relative to the question's font size. \
    * @param string $response_font_scale \
    */
    public function set_response_font_scale ($response_font_scale) {
        $this->response_font_scale = $response_font_scale;
    }

    /**
    * Get Type \
    * Keyboard style. See the <a href="//docs.learnosity.com/authoring/authorguide/formula/gettingstarted/keyboard_types" targ
	et="_blank">knowledgebase article on formula keyboard types</a> for more information. \
    * @return string $type \
    */
    public function get_type() {
        return $this->type;
    }

    /**
    * Set Type \
    * Keyboard style. See the <a href="//docs.learnosity.com/authoring/authorguide/formula/gettingstarted/keyboard_types" targ
	et="_blank">knowledgebase article on formula keyboard types</a> for more information. \
    * @param string $type \
    */
    public function set_type ($type) {
        $this->type = $type;
    }

    /**
    * Get Response minimum width (px) \
    * Controls the minimum width of the response input area, e.g. 550px \
    * @return string $min_width \
    */
    public function get_min_width() {
        return $this->min_width;
    }

    /**
    * Set Response minimum width (px) \
    * Controls the minimum width of the response input area, e.g. 550px \
    * @param string $min_width \
    */
    public function set_min_width ($min_width) {
        $this->min_width = $min_width;
    }

    /**
    * Get Transparent background \
    * Determines whether the area housing nested response areas should be transparent \
    * @return boolean $transparent_background \
    */
    public function get_transparent_background() {
        return $this->transparent_background;
    }

    /**
    * Set Transparent background \
    * Determines whether the area housing nested response areas should be transparent \
    * @param boolean $transparent_background \
    */
    public function set_transparent_background ($transparent_background) {
        $this->transparent_background = $transparent_background;
    }

    
}

