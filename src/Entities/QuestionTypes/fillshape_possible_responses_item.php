<?php

namespace LearnosityQti\Entities\QuestionTypes;

use LearnosityQti\Entities\BaseQuestionTypeAttribute;

/**
* This class is auto-generated based on Schemas API and you should not modify its content
* Metadata: {"responses":"v2.107.0","feedback":"v2.71.0","features":"v2.107.0"}
*/
class fillshape_possible_responses_item extends BaseQuestionTypeAttribute {
    protected $value;
    protected $fill;
    protected $image;
    protected $shape;
    
    public function __construct(
            )
    {
            }

    /**
    * Get Value \
    *  \
    * @return string $value \
    */
    public function get_value() {
        return $this->value;
    }

    /**
    * Set Value \
    *  \
    * @param string $value \
    */
    public function set_value ($value) {
        $this->value = $value;
    }

    /**
    * Get Fill \
    * Defines the color and opacity of the individual possible response. \
    * @return string $fill \
    */
    public function get_fill() {
        return $this->fill;
    }

    /**
    * Set Fill \
    * Defines the color and opacity of the individual possible response. \
    * @param string $fill \
    */
    public function set_fill ($fill) {
        $this->fill = $fill;
    }

    /**
    * Get Image \
    * Define the background image for the current possible response. Only supported in the Rectangle and Grid shapes. \
    * @return fillshape_possible_responses_item_image $image \
    */
    public function get_image() {
        return $this->image;
    }

    /**
    * Set Image \
    * Define the background image for the current possible response. Only supported in the Rectangle and Grid shapes. \
    * @param fillshape_possible_responses_item_image $image \
    */
    public function set_image (fillshape_possible_responses_item_image $image) {
        $this->image = $image;
    }

    /**
    * Get Shape type \
    * The shape of each possible response. Only supported in the Grid shape. \
    * @return string $shape ie. rectangle, circle  \
    */
    public function get_shape() {
        return $this->shape;
    }

    /**
    * Set Shape type \
    * The shape of each possible response. Only supported in the Grid shape. \
    * @param string $shape ie. rectangle, circle  \
    */
    public function set_shape ($shape) {
        $this->shape = $shape;
    }

    
}

