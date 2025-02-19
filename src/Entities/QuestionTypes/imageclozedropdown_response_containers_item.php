<?php

namespace LearnosityQti\Entities\QuestionTypes;

use LearnosityQti\Entities\BaseQuestionTypeAttribute;

/**
 * This class is auto-generated based on Schemas API and you should not modify its content
 * Metadata: {"responses":"v2.228.0","feedback":"v2.71.0","features":"v2.208.0"}
 */
class imageclozedropdown_response_containers_item extends BaseQuestionTypeAttribute {
    protected $height;
    protected $width;
    protected $pointer;
    protected $placeholder;
    
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
     * Get Placeholder \
     * Placeholder text that can be added into the response entry area, which disappears when user starts typing. \
     * @return string $placeholder \
     */
    public function get_placeholder() {
        return $this->placeholder;
    }

    /**
     * Set Placeholder \
     * Placeholder text that can be added into the response entry area, which disappears when user starts typing. \
     * @param string $placeholder \
     */
    public function set_placeholder ($placeholder) {
        $this->placeholder = $placeholder;
    }

    
}

