<?php

namespace LearnosityQti\Entities\QuestionTypes;

use LearnosityQti\Entities\BaseQuestionTypeAttribute;

/**
* This class is auto-generated based on Schemas API and you should not modify its content
* Metadata: {"responses":"v2.107.0","feedback":"v2.71.0","features":"v2.107.0"}
*/
class highlight_image extends BaseQuestionTypeAttribute {
    protected $source;
    protected $width;
    protected $height;
    
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
    * Get Width (px) \
    * The pixel width of the image is needed to calculate the aspect ratio of the image. \
    * @return number $width \
    */
    public function get_width() {
        return $this->width;
    }

    /**
    * Set Width (px) \
    * The pixel width of the image is needed to calculate the aspect ratio of the image. \
    * @param number $width \
    */
    public function set_width ($width) {
        $this->width = $width;
    }

    /**
    * Get Height (px) \
    * The pixel height of the image is needed to calculate the aspect ratio of the image. \
    * @return number $height \
    */
    public function get_height() {
        return $this->height;
    }

    /**
    * Set Height (px) \
    * The pixel height of the image is needed to calculate the aspect ratio of the image. \
    * @param number $height \
    */
    public function set_height ($height) {
        $this->height = $height;
    }

    
}

