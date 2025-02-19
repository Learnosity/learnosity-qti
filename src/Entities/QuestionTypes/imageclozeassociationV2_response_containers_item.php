<?php

namespace LearnosityQti\Entities\QuestionTypes;

use LearnosityQti\Entities\BaseQuestionTypeAttribute;

/**
 * This class is auto-generated based on Schemas API and you should not modify its content
 * Metadata: {"responses":"v2.228.0","feedback":"v2.71.0","features":"v2.208.0"}
 */
class imageclozeassociationV2_response_containers_item extends BaseQuestionTypeAttribute {
    protected $x;
    protected $y;
    protected $height;
    protected $width;
    protected $background_color;
    protected $show_border;
    protected $aria_label;
    
    public function __construct(
            )
    {
            }

    /**
     * Get X axis image position \
     * x value indicating the horizontal position of the top left corner of the response field on the image. The value is a per
	 * centage of the total width of the image. \
     * @return number $x \
     */
    public function get_x() {
        return $this->x;
    }

    /**
     * Set X axis image position \
     * x value indicating the horizontal position of the top left corner of the response field on the image. The value is a per
	 * centage of the total width of the image. \
     * @param number $x \
     */
    public function set_x ($x) {
        $this->x = $x;
    }

    /**
     * Get Y axis image position \
     * y value indicating the vertical position of the top left corner of the response field on the image. The value is a perce
	 * ntage of the total height of the image. \
     * @return number $y \
     */
    public function get_y() {
        return $this->y;
    }

    /**
     * Set Y axis image position \
     * y value indicating the vertical position of the top left corner of the response field on the image. The value is a perce
	 * ntage of the total height of the image. \
     * @param number $y \
     */
    public function set_y ($y) {
        $this->y = $y;
    }

    /**
     * Get Height \
     * The height of the response container. By default any value you pass will be converted to percents notation, e.g. "100%".
	 *  Passing any other notation (e.g. 5, or "5px") is supported for backwards compatibility, but it will be converted back t
	 * o percents notation. \
     * @return string $height \
     */
    public function get_height() {
        return $this->height;
    }

    /**
     * Set Height \
     * The height of the response container. By default any value you pass will be converted to percents notation, e.g. "100%".
	 *  Passing any other notation (e.g. 5, or "5px") is supported for backwards compatibility, but it will be converted back t
	 * o percents notation. \
     * @param string $height \
     */
    public function set_height ($height) {
        $this->height = $height;
    }

    /**
     * Get Width \
     * The width of the response container. By default any value you pass will be converted to percents notation, e.g. "100%". 
	 * Passing any other notation (e.g. 5, or "5px") is supported for backwards compatibility, but it will be converted back to
	 *  percents notation. \
     * @return string $width \
     */
    public function get_width() {
        return $this->width;
    }

    /**
     * Set Width \
     * The width of the response container. By default any value you pass will be converted to percents notation, e.g. "100%". 
	 * Passing any other notation (e.g. 5, or "5px") is supported for backwards compatibility, but it will be converted back to
	 *  percents notation. \
     * @param string $width \
     */
    public function set_width ($width) {
        $this->width = $width;
    }

    /**
     * Get Fill color \
     * An RGBA string defining the background color for a response container. \
     * @return string $background_color \
     */
    public function get_background_color() {
        return $this->background_color;
    }

    /**
     * Set Fill color \
     * An RGBA string defining the background color for a response container. \
     * @param string $background_color \
     */
    public function set_background_color ($background_color) {
        $this->background_color = $background_color;
    }

    /**
     * Get Show dashed border \
     * Determines whether or not borders are visible on response containers. \
     * @return boolean $show_border \
     */
    public function get_show_border() {
        return $this->show_border;
    }

    /**
     * Set Show dashed border \
     * Determines whether or not borders are visible on response containers. \
     * @param boolean $show_border \
     */
    public function set_show_border ($show_border) {
        $this->show_border = $show_border;
    }

    /**
     * Get Aria label \
     * Accessibility booster \
     * @return stringOrderedList $aria_label \
     */
    public function get_aria_label() {
        return $this->aria_label;
    }

    /**
     * Set Aria label \
     * Accessibility booster \
     * @param stringOrderedList $aria_label \
     */
    public function set_aria_label ($aria_label) {
        $this->aria_label = $aria_label;
    }

    
}

