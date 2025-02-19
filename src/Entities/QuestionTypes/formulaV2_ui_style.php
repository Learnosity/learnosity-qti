<?php

namespace LearnosityQti\Entities\QuestionTypes;

use LearnosityQti\Entities\BaseQuestionTypeAttribute;

/**
 * This class is auto-generated based on Schemas API and you should not modify its content
 * Metadata: {"responses":"v2.228.0","feedback":"v2.71.0","features":"v2.208.0"}
 */
class formulaV2_ui_style extends BaseQuestionTypeAttribute {
    protected $fontsize;
    protected $response_font_scale;
    protected $type;
    protected $min_width;
    protected $transparent_background;
    protected $keyboard_below_response_area;
    
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
     * This scales the font relative to the question's font size. Possible values: <ul><li><code>"boosted"</code> 150%</li><li>
	 * <code>"normal"</code> 100%</li></ul> \
     * @return string $response_font_scale \
     */
    public function get_response_font_scale() {
        return $this->response_font_scale;
    }

    /**
     * Set Template font scale \
     * This scales the font relative to the question's font size. Possible values: <ul><li><code>"boosted"</code> 150%</li><li>
	 * <code>"normal"</code> 100%</li></ul> \
     * @param string $response_font_scale \
     */
    public function set_response_font_scale ($response_font_scale) {
        $this->response_font_scale = $response_font_scale;
    }

    /**
     * Get Type \
     * Keyboard style. See <a href="https://authorguide.learnosity.com/hc/en-us/articles/360000439418-Keypad-Customisation">Key
	 * pad Customization</a> for more information. Possible values: <ul><li><code>"floating-keyboard"</code><p>A movable keypad
	 *  appears when the learner brings focus into the response box</p></li><li><code>"block-keyboard"</code><p>A fixed keypad 
	 * that is always visible</p></li><li><code>"block-on-focus-keyboard"</code><p>A fixed keypad appears when the learner brin
	 * gs focus into the response box</p></li><li><code>"no-input-ui"</code><p>No keypad</p></li></ul> \
     * @return string $type \
     */
    public function get_type() {
        return $this->type;
    }

    /**
     * Set Type \
     * Keyboard style. See <a href="https://authorguide.learnosity.com/hc/en-us/articles/360000439418-Keypad-Customisation">Key
	 * pad Customization</a> for more information. Possible values: <ul><li><code>"floating-keyboard"</code><p>A movable keypad
	 *  appears when the learner brings focus into the response box</p></li><li><code>"block-keyboard"</code><p>A fixed keypad 
	 * that is always visible</p></li><li><code>"block-on-focus-keyboard"</code><p>A fixed keypad appears when the learner brin
	 * gs focus into the response box</p></li><li><code>"no-input-ui"</code><p>No keypad</p></li></ul> \
     * @param string $type \
     */
    public function set_type ($type) {
        $this->type = $type;
    }

    /**
     * Get Response minimum width (px) \
     * Controls the minimum width of the response input area, e.g. 550px \
     * @return stringUnits $min_width \
     */
    public function get_min_width() {
        return $this->min_width;
    }

    /**
     * Set Response minimum width (px) \
     * Controls the minimum width of the response input area, e.g. 550px \
     * @param stringUnits $min_width \
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

    /**
     * Get Place keypad below response area \
     * If true, the floating formula keyboard will appear below the question as a whole (instead of below each individual math 
	 * editor in the question). \
     * @return boolean $keyboard_below_response_area \
     */
    public function get_keyboard_below_response_area() {
        return $this->keyboard_below_response_area;
    }

    /**
     * Set Place keypad below response area \
     * If true, the floating formula keyboard will appear below the question as a whole (instead of below each individual math 
	 * editor in the question). \
     * @param boolean $keyboard_below_response_area \
     */
    public function set_keyboard_below_response_area ($keyboard_below_response_area) {
        $this->keyboard_below_response_area = $keyboard_below_response_area;
    }

    
}

