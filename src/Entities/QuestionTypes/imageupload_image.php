<?php

namespace Learnosity\Entities\QuestionTypes;

use Learnosity\Entities\BaseQuestionTypeAttribute;

/**
* This class is auto-generated based on Schemas API and you should not modify its content
* Metadata: {"responses":"v2.72.0","feedback":"v2.71.0","features":"v2.72.0"}
*/
class imageupload_image extends BaseQuestionTypeAttribute {
    protected $source;
    protected $width;
    protected $height;
    
    public function __construct(
            )
    {
            }

    /**
    * Get Source URL \
    * The image that should be displayed. \
    * @return string $source \
    */
    public function get_source() {
        return $this->source;
    }

    /**
    * Set Source URL \
    * The image that should be displayed. \
    * @param string $source \
    */
    public function set_source ($source) {
        $this->source = $source;
    }

    /**
    * Get Width in pixels \
    * The pixel width of the image is needed to calculate the aspect ratio of the image. \
    * @return number $width \
    */
    public function get_width() {
        return $this->width;
    }

    /**
    * Set Width in pixels \
    * The pixel width of the image is needed to calculate the aspect ratio of the image. \
    * @param number $width \
    */
    public function set_width ($width) {
        $this->width = $width;
    }

    /**
    * Get Height in pixels \
    * The pixel height of the image is needed to calculate the aspect ratio of the image. \
    * @return number $height \
    */
    public function get_height() {
        return $this->height;
    }

    /**
    * Set Height in pixels \
    * The pixel height of the image is needed to calculate the aspect ratio of the image. \
    * @param number $height \
    */
    public function set_height ($height) {
        $this->height = $height;
    }

    
}

