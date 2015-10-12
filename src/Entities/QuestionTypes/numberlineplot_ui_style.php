<?php

namespace LearnosityQti\Entities\QuestionTypes;

use LearnosityQti\Entities\BaseQuestionTypeAttribute;

/**
* This class is auto-generated based on Schemas API and you should not modify its content
* Metadata: {"responses":"v2.72.0","feedback":"v2.71.0","features":"v2.72.0"}
*/
class numberlineplot_ui_style extends BaseQuestionTypeAttribute {
    protected $fontsize;
    protected $layout;
    protected $spacing;
    protected $number_line_margin;
    protected $width;
    protected $height;
    protected $min_width;
    
    public function __construct(
            )
    {
            }

    /**
    * Get Font size \
    * Controls the size of base font for this question. Options are among 'small', 'normal', 'large', 'xlarge' and 'xxlarge'. \
    * @return string $fontsize ie. small, normal, large, xlarge, xxlarge  \
    */
    public function get_fontsize() {
        return $this->fontsize;
    }

    /**
    * Set Font size \
    * Controls the size of base font for this question. Options are among 'small', 'normal', 'large', 'xlarge' and 'xxlarge'. \
    * @param string $fontsize ie. small, normal, large, xlarge, xxlarge  \
    */
    public function set_fontsize ($fontsize) {
        $this->fontsize = $fontsize;
    }

    /**
    * Get Layout \
    * Allows author to decide between horizontal and vertical plotting. \
    * @return string $layout \
    */
    public function get_layout() {
        return $this->layout;
    }

    /**
    * Set Layout \
    * Allows author to decide between horizontal and vertical plotting. \
    * @param string $layout \
    */
    public function set_layout ($layout) {
        $this->layout = $layout;
    }

    /**
    * Get Spacing between stacked responses \
    * Spacing between stacked responses with trailing px unit eg. "30px" \
    * @return string $spacing \
    */
    public function get_spacing() {
        return $this->spacing;
    }

    /**
    * Set Spacing between stacked responses \
    * Spacing between stacked responses with trailing px unit eg. "30px" \
    * @param string $spacing \
    */
    public function set_spacing ($spacing) {
        $this->spacing = $spacing;
    }

    /**
    * Get Number line margin \
    * Distance from the number line's extremes to the sides with trailing px unit eg. "5px" \
    * @return string $number_line_margin \
    */
    public function get_number_line_margin() {
        return $this->number_line_margin;
    }

    /**
    * Set Number line margin \
    * Distance from the number line's extremes to the sides with trailing px unit eg. "5px" \
    * @param string $number_line_margin \
    */
    public function set_number_line_margin ($number_line_margin) {
        $this->number_line_margin = $number_line_margin;
    }

    /**
    * Get Width \
    * Width of the drawn area with trailing px unit eg. "550px". \
    * @return string $width \
    */
    public function get_width() {
        return $this->width;
    }

    /**
    * Set Width \
    * Width of the drawn area with trailing px unit eg. "550px". \
    * @param string $width \
    */
    public function set_width ($width) {
        $this->width = $width;
    }

    /**
    * Get Height \
    * Height of the drawn area with trailing px unit eg. "500px". \
    * @return string $height \
    */
    public function get_height() {
        return $this->height;
    }

    /**
    * Set Height \
    * Height of the drawn area with trailing px unit eg. "500px". \
    * @param string $height \
    */
    public function set_height ($height) {
        $this->height = $height;
    }

    /**
    * Get Minimum width \
    * Minimum width of the drawn area with trailing px unit eg. "550px". Width in vertical mode is being calculated automatica
	lly, but you can force width to take at least some amount of space. Note also that toolbox takes 55px. \
    * @return string $min_width \
    */
    public function get_min_width() {
        return $this->min_width;
    }

    /**
    * Set Minimum width \
    * Minimum width of the drawn area with trailing px unit eg. "550px". Width in vertical mode is being calculated automatica
	lly, but you can force width to take at least some amount of space. Note also that toolbox takes 55px. \
    * @param string $min_width \
    */
    public function set_min_width ($min_width) {
        $this->min_width = $min_width;
    }

    
}

