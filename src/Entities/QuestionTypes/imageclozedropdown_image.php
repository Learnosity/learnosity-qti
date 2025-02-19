<?php

namespace LearnosityQti\Entities\QuestionTypes;

use LearnosityQti\Entities\BaseQuestionTypeAttribute;

/**
 * This class is auto-generated based on Schemas API and you should not modify its content
 * Metadata: {"responses":"v2.228.0","feedback":"v2.71.0","features":"v2.208.0"}
 */
class imageclozedropdown_image extends BaseQuestionTypeAttribute {
    protected $src;
    protected $alt;
    protected $width;
    protected $height;
    protected $title;
    protected $prevent_scale;
    protected $scale;
    protected $preview;
    
    public function __construct(
            )
    {
            }

    /**
     * Get Add image \
     * The URL to the image file. \
     * @return string $src \
     */
    public function get_src() {
        return $this->src;
    }

    /**
     * Set Add image \
     * The URL to the image file. \
     * @param string $src \
     */
    public function set_src ($src) {
        $this->src = $src;
    }

    /**
     * Get Image alternative text \
     * The alternative text of the image. \
     * @return textarea $alt \
     */
    public function get_alt() {
        return $this->alt;
    }

    /**
     * Set Image alternative text \
     * The alternative text of the image. \
     * @param textarea $alt \
     */
    public function set_alt ($alt) {
        $this->alt = $alt;
    }

    /**
     * Get Width (px) \
     * Enter a numeric value for the width of the image. This value should be a pixel (px) value, but you do not need to type <
	 * em>px</em> into the field. \
     * @return number $width \
     */
    public function get_width() {
        return $this->width;
    }

    /**
     * Set Width (px) \
     * Enter a numeric value for the width of the image. This value should be a pixel (px) value, but you do not need to type <
	 * em>px</em> into the field. \
     * @param number $width \
     */
    public function set_width ($width) {
        $this->width = $width;
    }

    /**
     * Get Height (px) \
     * Enter a numeric value for the height of the image. This value should be a pixel (px) value, but you do not need to type 
	 * <em>px</em> into the field. \
     * @return number $height \
     */
    public function get_height() {
        return $this->height;
    }

    /**
     * Set Height (px) \
     * Enter a numeric value for the height of the image. This value should be a pixel (px) value, but you do not need to type 
	 * <em>px</em> into the field. \
     * @param number $height \
     */
    public function set_height ($height) {
        $this->height = $height;
    }

    /**
     * Get Text on hover \
     * The text to be shown on hover. \
     * @return string $title \
     */
    public function get_title() {
        return $this->title;
    }

    /**
     * Set Text on hover \
     * The text to be shown on hover. \
     * @param string $title \
     */
    public function set_title ($title) {
        $this->title = $title;
    }

    /**
     * Get Prevent image scale \
     * Prevent image to be scaled along with screen size change \
     * @return boolean $prevent_scale \
     */
    public function get_prevent_scale() {
        return $this->prevent_scale;
    }

    /**
     * Set Prevent image scale \
     * Prevent image to be scaled along with screen size change \
     * @param boolean $prevent_scale \
     */
    public function set_prevent_scale ($prevent_scale) {
        $this->prevent_scale = $prevent_scale;
    }

    /**
     * Get Image scale \
     * Allow image to be scaled along with font size \
     * @return boolean $scale \
     */
    public function get_scale() {
        return $this->scale;
    }

    /**
     * Set Image scale \
     * Allow image to be scaled along with font size \
     * @param boolean $scale \
     */
    public function set_scale ($scale) {
        $this->scale = $scale;
    }

    /**
     * Get Image preview \
     * Preview of the chosen image \
     * @return hidden $preview \
     */
    public function get_preview() {
        return $this->preview;
    }

    /**
     * Set Image preview \
     * Preview of the chosen image \
     * @param hidden $preview \
     */
    public function set_preview ($preview) {
        $this->preview = $preview;
    }

    
}

