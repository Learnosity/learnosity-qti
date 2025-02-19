<?php

namespace LearnosityQti\Entities\QuestionTypes;

use LearnosityQti\Entities\BaseQuestionTypeAttribute;

/**
 * This class is auto-generated based on Schemas API and you should not modify its content
 * Metadata: {"responses":"v2.228.0","feedback":"v2.71.0","features":"v2.208.0"}
 */
class simpleshading_background_image extends BaseQuestionTypeAttribute {
    protected $src;
    protected $width;
    protected $height;
    protected $opacity;
    protected $alt;
    protected $title;
    
    public function __construct(
            )
    {
            }

    /**
     * Get Add image \
     *  \
     * @return string $src \
     */
    public function get_src() {
        return $this->src;
    }

    /**
     * Set Add image \
     *  \
     * @param string $src \
     */
    public function set_src ($src) {
        $this->src = $src;
    }

    /**
     * Get Width (%) \
     * Image width in percentage of the canvas width \
     * @return number $width \
     */
    public function get_width() {
        return $this->width;
    }

    /**
     * Set Width (%) \
     * Image width in percentage of the canvas width \
     * @param number $width \
     */
    public function set_width ($width) {
        $this->width = $width;
    }

    /**
     * Get Height (%) \
     * Image height in percentage of the canvas height \
     * @return number $height \
     */
    public function get_height() {
        return $this->height;
    }

    /**
     * Set Height (%) \
     * Image height in percentage of the canvas height \
     * @param number $height \
     */
    public function set_height ($height) {
        $this->height = $height;
    }

    /**
     * Get Opacity (%) \
     * Percentage value defining how opaque the image is \
     * @return number $opacity \
     */
    public function get_opacity() {
        return $this->opacity;
    }

    /**
     * Set Opacity (%) \
     * Percentage value defining how opaque the image is \
     * @param number $opacity \
     */
    public function set_opacity ($opacity) {
        $this->opacity = $opacity;
    }

    /**
     * Get Image alternative text \
     * The alternative text of the background image. \
     * @return textarea $alt \
     */
    public function get_alt() {
        return $this->alt;
    }

    /**
     * Set Image alternative text \
     * The alternative text of the background image. \
     * @param textarea $alt \
     */
    public function set_alt ($alt) {
        $this->alt = $alt;
    }

    /**
     * Get Image title \
     * The title of the background image \
     * @return string $title \
     */
    public function get_title() {
        return $this->title;
    }

    /**
     * Set Image title \
     * The title of the background image \
     * @param string $title \
     */
    public function set_title ($title) {
        $this->title = $title;
    }

    
}

