<?php

namespace LearnosityQti\Entities\QuestionTypes;

use LearnosityQti\Entities\BaseQuestionTypeAttribute;

/**
* This class is auto-generated based on Schemas API and you should not modify its content
* Metadata: {"responses":"v2.84.0","feedback":"v2.71.0","features":"v2.84.0"}
*/
class graphplotting_background_image extends BaseQuestionTypeAttribute {
    protected $src;
    protected $x;
    protected $y;
    protected $width;
    protected $height;
    protected $opacity;
    
    public function __construct(
            )
    {
            }

    /**
    * Get Image source \
    *  \
    * @return string $src \
    */
    public function get_src() {
        return $this->src;
    }

    /**
    * Set Image source \
    *  \
    * @param string $src \
    */
    public function set_src ($src) {
        $this->src = $src;
    }

    /**
    * Get X \
    * X coordinate of the image's centre \
    * @return number $x \
    */
    public function get_x() {
        return $this->x;
    }

    /**
    * Set X \
    * X coordinate of the image's centre \
    * @param number $x \
    */
    public function set_x ($x) {
        $this->x = $x;
    }

    /**
    * Get Y \
    * Y coordinate of the image's centre \
    * @return number $y \
    */
    public function get_y() {
        return $this->y;
    }

    /**
    * Set Y \
    * Y coordinate of the image's centre \
    * @param number $y \
    */
    public function set_y ($y) {
        $this->y = $y;
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

    
}

