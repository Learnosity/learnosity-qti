<?php

namespace LearnosityQti\Entities\QuestionTypes;

use LearnosityQti\Entities\BaseQuestionTypeAttribute;

/**
 * This class is auto-generated based on Schemas API and you should not modify its content
 * Metadata: {"responses":"v2.228.0","feedback":"v2.71.0","features":"v2.208.0"}
 */
class clozeassociation_response_containers_item extends BaseQuestionTypeAttribute {
    protected $height;
    protected $width;
    protected $wordwrap;
    protected $aria_label;
    
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
     * Get Aria label \
     * Custom aria label text for the response container. \
     * @return string $aria_label \
     */
    public function get_aria_label() {
        return $this->aria_label;
    }

    /**
     * Set Aria label \
     * Custom aria label text for the response container. \
     * @param string $aria_label \
     */
    public function set_aria_label ($aria_label) {
        $this->aria_label = $aria_label;
    }

    
}

