<?php

namespace LearnosityQti\Entities\QuestionTypes;

use LearnosityQti\Entities\BaseQuestionTypeAttribute;

/**
 * This class is auto-generated based on Schemas API and you should not modify its content
 * Metadata: {"responses":"v2.228.0","feedback":"v2.71.0","features":"v2.208.0"}
 */
class imageclozeassociationV2_response_container extends BaseQuestionTypeAttribute {
    protected $height;
    protected $width;
    protected $pointer;
    protected $vertical_top;
    protected $wordwrap;
    protected $background_color;
    protected $show_border;
    
    public function __construct(
            )
    {
            }

    /**
     * Get Height (px) \
     *  \
     * @return stringUnits $height \
     */
    public function get_height() {
        return $this->height;
    }

    /**
     * Set Height (px) \
     *  \
     * @param stringUnits $height \
     */
    public function set_height ($height) {
        $this->height = $height;
    }

    /**
     * Get Width (px) \
     *  \
     * @return stringUnits $width \
     */
    public function get_width() {
        return $this->width;
    }

    /**
     * Set Width (px) \
     *  \
     * @param stringUnits $width \
     */
    public function set_width ($width) {
        $this->width = $width;
    }

    /**
     * Get Pointer \
     * Add response pointer next to the response container. Values can be one of 'top', 'right', 'bottom', 'left' \
     * @return string $pointer \
     */
    public function get_pointer() {
        return $this->pointer;
    }

    /**
     * Set Pointer \
     * Add response pointer next to the response container. Values can be one of 'top', 'right', 'bottom', 'left' \
     * @param string $pointer \
     */
    public function set_pointer ($pointer) {
        $this->pointer = $pointer;
    }

    /**
     * Get Vertical top \
     * This setting ensures that the position of the response boxes stays at the position set by the author, regardless of chan
	 * ges to the font size or container's size, i.e. it ensures that the position of the responses stays at <em>top: 0, left: 
	 * 0</em> of the container.  \
     * @return boolean $vertical_top \
     */
    public function get_vertical_top() {
        return $this->vertical_top;
    }

    /**
     * Set Vertical top \
     * This setting ensures that the position of the response boxes stays at the position set by the author, regardless of chan
	 * ges to the font size or container's size, i.e. it ensures that the position of the responses stays at <em>top: 0, left: 
	 * 0</em> of the container.  \
     * @param boolean $vertical_top \
     */
    public function set_vertical_top ($vertical_top) {
        $this->vertical_top = $vertical_top;
    }

    /**
     * Get Wordwrap \
     * Determines if the possible response text should wrap or show an ellipsis when placed in a response container. \
     * @return boolean $wordwrap \
     */
    public function get_wordwrap() {
        return $this->wordwrap;
    }

    /**
     * Set Wordwrap \
     * Determines if the possible response text should wrap or show an ellipsis when placed in a response container. \
     * @param boolean $wordwrap \
     */
    public function set_wordwrap ($wordwrap) {
        $this->wordwrap = $wordwrap;
    }

    /**
     * Get Fill color \
     * An RGBA string defining the background color for all response containers. \
     * @return string $background_color \
     */
    public function get_background_color() {
        return $this->background_color;
    }

    /**
     * Set Fill color \
     * An RGBA string defining the background color for all response containers. \
     * @param string $background_color \
     */
    public function set_background_color ($background_color) {
        $this->background_color = $background_color;
    }

    /**
     * Get Show dashed border \
     * Determines whether or not borders are visible in response containers. \
     * @return boolean $show_border \
     */
    public function get_show_border() {
        return $this->show_border;
    }

    /**
     * Set Show dashed border \
     * Determines whether or not borders are visible in response containers. \
     * @param boolean $show_border \
     */
    public function set_show_border ($show_border) {
        $this->show_border = $show_border;
    }

    
}

