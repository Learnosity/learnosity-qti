<?php

namespace LearnosityQti\Entities\QuestionTypes;

use LearnosityQti\Entities\BaseQuestionTypeAttribute;

/**
* This class is auto-generated based on Schemas API and you should not modify its content
* Metadata: {"responses":"v2.107.0","feedback":"v2.71.0","features":"v2.107.0"}
*/
class mcq_ui_style extends BaseQuestionTypeAttribute {
    protected $fontsize;
    protected $type;
    protected $choice_label;
    protected $columns;
    protected $orientation;
    
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
    * Get Style \
    *  \
    * @return string $type \
    */
    public function get_type() {
        return $this->type;
    }

    /**
    * Set Style \
    *  \
    * @param string $type \
    */
    public function set_type ($type) {
        $this->type = $type;
    }

    /**
    * Get Label type \
    *  \
    * @return string $choice_label \
    */
    public function get_choice_label() {
        return $this->choice_label;
    }

    /**
    * Set Label type \
    *  \
    * @param string $choice_label \
    */
    public function set_choice_label ($choice_label) {
        $this->choice_label = $choice_label;
    }

    /**
    * Get Number of columns \
    *  \
    * @return number $columns \
    */
    public function get_columns() {
        return $this->columns;
    }

    /**
    * Set Number of columns \
    *  \
    * @param number $columns \
    */
    public function set_columns ($columns) {
        $this->columns = $columns;
    }

    /**
    * Get Orientation \
    * Sorts the order of the responses vertically or horizontally \
    * @return string $orientation \
    */
    public function get_orientation() {
        return $this->orientation;
    }

    /**
    * Set Orientation \
    * Sorts the order of the responses vertically or horizontally \
    * @param string $orientation \
    */
    public function set_orientation ($orientation) {
        $this->orientation = $orientation;
    }

    
}

