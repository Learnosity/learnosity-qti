<?php

namespace LearnosityQti\Entities\QuestionTypes;

use LearnosityQti\Entities\BaseQuestionTypeAttribute;

/**
 * This class is auto-generated based on Schemas API and you should not modify its content
 * Metadata: {"responses":"v2.228.0","feedback":"v2.71.0","features":"v2.208.0"}
 */
class imageupload_image extends BaseQuestionTypeAttribute {
    protected $source;
    protected $alt;
    protected $width;
    protected $height;
    protected $preview;
    
    public function __construct(
            )
    {
            }

    /**
     * Get Add image \
     * The image that should be displayed. \
     * @return string $source \
     */
    public function get_source() {
        return $this->source;
    }

    /**
     * Set Add image \
     * The image that should be displayed. \
     * @param string $source \
     */
    public function set_source ($source) {
        $this->source = $source;
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
     * Get Width in pixels \
     * Enter a numeric value for the width of the image. This value should be a pixel (px) value, but you do not need to type <
	 * em>px</em> into the field. \
     * @return number $width \
     */
    public function get_width() {
        return $this->width;
    }

    /**
     * Set Width in pixels \
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

