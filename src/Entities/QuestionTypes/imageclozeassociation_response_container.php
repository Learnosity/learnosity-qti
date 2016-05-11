<?php

namespace LearnosityQti\Entities\QuestionTypes;

use LearnosityQti\Entities\BaseQuestionTypeAttribute;

/**
* This class is auto-generated based on Schemas API and you should not modify its content
* Metadata: {"responses":"v2.84.0","feedback":"v2.71.0","features":"v2.84.0"}
*/
class imageclozeassociation_response_container extends BaseQuestionTypeAttribute {
    protected $pointer;
    protected $height;
    protected $width;
    protected $wordwrap;
    
    public function __construct(
            )
    {
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
    * Get Height \
    * The height of the cloze response containers including units. Example: "100px" \
    * @return string $height \
    */
    public function get_height() {
        return $this->height;
    }

    /**
    * Set Height \
    * The height of the cloze response containers including units. Example: "100px" \
    * @param string $height \
    */
    public function set_height ($height) {
        $this->height = $height;
    }

    /**
    * Get Width \
    * The width of the cloze response containers including units. Example: "100px" \
    * @return string $width \
    */
    public function get_width() {
        return $this->width;
    }

    /**
    * Set Width \
    * The width of the cloze response containers including units. Example: "100px" \
    * @param string $width \
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

    
}

