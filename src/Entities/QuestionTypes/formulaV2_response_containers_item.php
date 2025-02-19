<?php

namespace LearnosityQti\Entities\QuestionTypes;

use LearnosityQti\Entities\BaseQuestionTypeAttribute;

/**
 * This class is auto-generated based on Schemas API and you should not modify its content
 * Metadata: {"responses":"v2.228.0","feedback":"v2.71.0","features":"v2.208.0"}
 */
class formulaV2_response_containers_item extends BaseQuestionTypeAttribute {
    protected $height;
    protected $width;
    
    public function __construct(
            )
    {
            }

    /**
     * Get Height (px) \
     * Height of the response container, in pixels. Individual container setting. \
     * @return stringUnits $height \
     */
    public function get_height() {
        return $this->height;
    }

    /**
     * Set Height (px) \
     * Height of the response container, in pixels. Individual container setting. \
     * @param stringUnits $height \
     */
    public function set_height ($height) {
        $this->height = $height;
    }

    /**
     * Get Width (px) \
     * Width of the response container, in pixels. Individual container setting. \
     * @return stringUnits $width \
     */
    public function get_width() {
        return $this->width;
    }

    /**
     * Set Width (px) \
     * Width of the response container, in pixels. Individual container setting. \
     * @param stringUnits $width \
     */
    public function set_width ($width) {
        $this->width = $width;
    }

    
}

