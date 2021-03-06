<?php

namespace LearnosityQti\Entities\QuestionTypes;

use LearnosityQti\Entities\BaseQuestionTypeAttribute;

/**
* This class is auto-generated based on Schemas API and you should not modify its content
* Metadata: {"responses":"v2.108.0","feedback":"v2.71.0","features":"v2.107.0"}
*/
class fillshape_ui_style extends BaseQuestionTypeAttribute {
    protected $fontsize;
    protected $validation_stem_numeration;
    protected $possibility_list_position;
    protected $show_labels;
    
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
    * Get Stem numeration (review only) \
    * Numeration character to be displayed to the left of the validation label. \
    * @return string $validation_stem_numeration ie. number, upper-alpha, lower-alpha  \
    */
    public function get_validation_stem_numeration() {
        return $this->validation_stem_numeration;
    }

    /**
    * Set Stem numeration (review only) \
    * Numeration character to be displayed to the left of the validation label. \
    * @param string $validation_stem_numeration ie. number, upper-alpha, lower-alpha  \
    */
    public function set_validation_stem_numeration ($validation_stem_numeration) {
        $this->validation_stem_numeration = $validation_stem_numeration;
    }

    /**
    * Get Response container position \
    * Defines where the possibility list sits relative to the input zone. \
    * @return string $possibility_list_position \
    */
    public function get_possibility_list_position() {
        return $this->possibility_list_position;
    }

    /**
    * Set Response container position \
    * Defines where the possibility list sits relative to the input zone. \
    * @param string $possibility_list_position \
    */
    public function set_possibility_list_position ($possibility_list_position) {
        $this->possibility_list_position = $possibility_list_position;
    }

    /**
    * Get Show labels \
    * Define whether the value label of the possible response should be shown or hidden. \
    * @return boolean $show_labels \
    */
    public function get_show_labels() {
        return $this->show_labels;
    }

    /**
    * Set Show labels \
    * Define whether the value label of the possible response should be shown or hidden. \
    * @param boolean $show_labels \
    */
    public function set_show_labels ($show_labels) {
        $this->show_labels = $show_labels;
    }

    
}

