<?php

namespace LearnosityQti\Entities\QuestionTypes;

use LearnosityQti\Entities\BaseQuestionTypeAttribute;

/**
 * This class is auto-generated based on Schemas API and you should not modify its content
 * Metadata: {"responses":"v2.228.0","feedback":"v2.71.0","features":"v2.208.0"}
 */
class formula_dev_response_container extends BaseQuestionTypeAttribute {
    protected $height;
    protected $width;
    protected $aria_label;
    
    public function __construct(
            )
    {
            }

    /**
     * Get Height (px) \
     * Height of the response container, in pixels. Global setting. \
     * @return stringUnits $height \
     */
    public function get_height() {
        return $this->height;
    }

    /**
     * Set Height (px) \
     * Height of the response container, in pixels. Global setting. \
     * @param stringUnits $height \
     */
    public function set_height ($height) {
        $this->height = $height;
    }

    /**
     * Get Width (px) \
     * Width of the response container, in pixels. Global setting. \
     * @return stringUnits $width \
     */
    public function get_width() {
        return $this->width;
    }

    /**
     * Set Width (px) \
     * Width of the response container, in pixels. Global setting. \
     * @param stringUnits $width \
     */
    public function set_width ($width) {
        $this->width = $width;
    }

    /**
     * Get ARIA label \
     * The ARIA label for the response container. The label will be "Response area" by default if no label is provided. \
     * @return string $aria_label \
     */
    public function get_aria_label() {
        return $this->aria_label;
    }

    /**
     * Set ARIA label \
     * The ARIA label for the response container. The label will be "Response area" by default if no label is provided. \
     * @param string $aria_label \
     */
    public function set_aria_label ($aria_label) {
        $this->aria_label = $aria_label;
    }

    
}

