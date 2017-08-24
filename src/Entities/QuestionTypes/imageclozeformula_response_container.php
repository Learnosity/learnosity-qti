<?php

namespace LearnosityQti\Entities\QuestionTypes;

use LearnosityQti\Entities\BaseQuestionTypeAttribute;

/**
* This class is auto-generated based on Schemas API and you should not modify its content
* Metadata: {"responses":"v2.108.0","feedback":"v2.71.0","features":"v2.107.0"}
*/
class imageclozeformula_response_container extends BaseQuestionTypeAttribute {
    protected $template;
    protected $height;
    protected $width;
    protected $vertical_top;
    
    public function __construct(
            )
    {
            }

    /**
    * Get Formula template \
    *  \
    * @return string $template \
    */
    public function get_template() {
        return $this->template;
    }

    /**
    * Set Formula template \
    *  \
    * @param string $template \
    */
    public function set_template ($template) {
        $this->template = $template;
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
    * Get Vertical top \
    * Response container will always stay at the top left position if this attribute value is set to true \
    * @return boolean $vertical_top \
    */
    public function get_vertical_top() {
        return $this->vertical_top;
    }

    /**
    * Set Vertical top \
    * Response container will always stay at the top left position if this attribute value is set to true \
    * @param boolean $vertical_top \
    */
    public function set_vertical_top ($vertical_top) {
        $this->vertical_top = $vertical_top;
    }

    
}

