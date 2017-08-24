<?php

namespace LearnosityQti\Entities\QuestionTypes;

use LearnosityQti\Entities\BaseQuestionTypeAttribute;

/**
* This class is auto-generated based on Schemas API and you should not modify its content
* Metadata: {"responses":"v2.108.0","feedback":"v2.71.0","features":"v2.107.0"}
*/
class clozetext_response_container extends BaseQuestionTypeAttribute {
    protected $height;
    protected $width;
    protected $placeholder;
    protected $input_type;
    
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
    * Get Placeholder \
    * Text to display as a hint to the user of what to enter \
    * @return string $placeholder \
    */
    public function get_placeholder() {
        return $this->placeholder;
    }

    /**
    * Set Placeholder \
    * Text to display as a hint to the user of what to enter \
    * @param string $placeholder \
    */
    public function set_placeholder ($placeholder) {
        $this->placeholder = $placeholder;
    }

    /**
    * Get Input type \
    * Type of input \
    * @return string $input_type \
    */
    public function get_input_type() {
        return $this->input_type;
    }

    /**
    * Set Input type \
    * Type of input \
    * @param string $input_type \
    */
    public function set_input_type ($input_type) {
        $this->input_type = $input_type;
    }

    
}

