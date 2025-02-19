<?php

namespace LearnosityQti\Entities\QuestionTypes;

use LearnosityQti\Entities\BaseQuestionTypeAttribute;

/**
 * This class is auto-generated based on Schemas API and you should not modify its content
 * Metadata: {"responses":"v2.228.0","feedback":"v2.71.0","features":"v2.208.0"}
 */
class imageclozeformula_response_container extends BaseQuestionTypeAttribute {
    protected $template;
    protected $height;
    protected $width;
    protected $aria_label;
    protected $vertical_top;
    
    public function __construct(
            )
    {
            }

    /**
     * Get Formula template \
     * The initial value in the formula field. If response containers are used, only they will be editable. \
     * @return string $template \
     */
    public function get_template() {
        return $this->template;
    }

    /**
     * Set Formula template \
     * The initial value in the formula field. If response containers are used, only they will be editable. \
     * @param string $template \
     */
    public function set_template ($template) {
        $this->template = $template;
    }

    /**
     * Get Height (px) \
     * Height of the response container, in pixels. Global setting. \
     * @return stringUnits $height \
     */
    public function get_height() {
        return $this->height;
    }

    /**
     * Set Height (px) \
     * Height of the response container, in pixels. Global setting. \
     * @param stringUnits $height \
     */
    public function set_height ($height) {
        $this->height = $height;
    }

    /**
     * Get Width (px) \
     * Width of the response container, in pixels. Global setting. \
     * @return stringUnits $width \
     */
    public function get_width() {
        return $this->width;
    }

    /**
     * Set Width (px) \
     * Width of the response container, in pixels. Global setting. \
     * @param stringUnits $width \
     */
    public function set_width ($width) {
        $this->width = $width;
    }

    /**
     * Get ARIA label \
     * The ARIA label for the response container. The label will be "Response area" by default if no label is provided. \
     * @return string $aria_label \
     */
    public function get_aria_label() {
        return $this->aria_label;
    }

    /**
     * Set ARIA label \
     * The ARIA label for the response container. The label will be "Response area" by default if no label is provided. \
     * @param string $aria_label \
     */
    public function set_aria_label ($aria_label) {
        $this->aria_label = $aria_label;
    }

    /**
     * Get Vertical top \
     * This setting ensures that the position of the response boxes stays at the position set by the author, regardless of chan
	 * ges to the font size or container's size, i.e. it ensures that the position of the responses stays at <em>top: 0, left: 
	 * 0</em> of the container.  \
     * @return boolean $vertical_top \
     */
    public function get_vertical_top() {
        return $this->vertical_top;
    }

    /**
     * Set Vertical top \
     * This setting ensures that the position of the response boxes stays at the position set by the author, regardless of chan
	 * ges to the font size or container's size, i.e. it ensures that the position of the responses stays at <em>top: 0, left: 
	 * 0</em> of the container.  \
     * @param boolean $vertical_top \
     */
    public function set_vertical_top ($vertical_top) {
        $this->vertical_top = $vertical_top;
    }

    
}

