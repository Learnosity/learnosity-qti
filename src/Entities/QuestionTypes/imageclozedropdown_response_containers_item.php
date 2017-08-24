<?php

namespace LearnosityQti\Entities\QuestionTypes;

use LearnosityQti\Entities\BaseQuestionTypeAttribute;

/**
* This class is auto-generated based on Schemas API and you should not modify its content
* Metadata: {"responses":"v2.108.0","feedback":"v2.71.0","features":"v2.107.0"}
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
    * @return string $height \
    */
    public function get_height() {
        return $this->height;
    }

    /**
    * Set Height (px) \
    *  \
    * @param string $height \
    */
    public function set_height ($height) {
        $this->height = $height;
    }

    /**
    * Get Width (px) \
    *  \
    * @return string $width \
    */
    public function get_width() {
        return $this->width;
    }

    /**
    * Set Width (px) \
    *  \
    * @param string $width \
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
    * Default text that can be added into the response entry area. \
    * @return string $placeholder \
    */
    public function get_placeholder() {
        return $this->placeholder;
    }

    /**
    * Set Placeholder \
    * Default text that can be added into the response entry area. \
    * @param string $placeholder \
    */
    public function set_placeholder ($placeholder) {
        $this->placeholder = $placeholder;
    }

    
}

