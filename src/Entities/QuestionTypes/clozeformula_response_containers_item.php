<?php

namespace LearnosityQti\Entities\QuestionTypes;

use LearnosityQti\Entities\BaseQuestionTypeAttribute;

/**
 * This class is auto-generated based on Schemas API and you should not modify its content
 * Metadata: {"responses":"v2.228.0","feedback":"v2.71.0","features":"v2.208.0"}
 */
class clozeformula_response_containers_item extends BaseQuestionTypeAttribute {
    protected $template;
    protected $width;
    protected $height;
    protected $aria_label;
    
    public function __construct(
            )
    {
            }

    /**
     * Get Template \
     * The initial value in the formula field. If response containers are used, only they will be editable. \
     * @return string $template \
     */
    public function get_template() {
        return $this->template;
    }

    /**
     * Set Template \
     * The initial value in the formula field. If response containers are used, only they will be editable. \
     * @param string $template \
     */
    public function set_template ($template) {
        $this->template = $template;
    }

    /**
     * Get Width (px) \
     * Width of an individual response container, in pixels. Individual container setting. \
     * @return stringUnits $width \
     */
    public function get_width() {
        return $this->width;
    }

    /**
     * Set Width (px) \
     * Width of an individual response container, in pixels. Individual container setting. \
     * @param stringUnits $width \
     */
    public function set_width ($width) {
        $this->width = $width;
    }

    /**
     * Get Height (px) \
     * Height of an individual response container, in pixels. Individual container setting. \
     * @return stringUnits $height \
     */
    public function get_height() {
        return $this->height;
    }

    /**
     * Set Height (px) \
     * Height of an individual response container, in pixels. Individual container setting. \
     * @param stringUnits $height \
     */
    public function set_height ($height) {
        $this->height = $height;
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

