<?php

namespace LearnosityQti\Entities\QuestionTypes;

use LearnosityQti\Entities\BaseQuestionTypeAttribute;

/**
* This class is auto-generated based on Schemas API and you should not modify its content
* Metadata: {"responses":"v2.72.0","feedback":"v2.71.0","features":"v2.72.0"}
*/
class clozeinlinetext_ui_style extends BaseQuestionTypeAttribute {
    protected $fontsize;
    protected $validation_stem_numeration;
    
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
    * Get Validation Stem Numeration \
    * Numeration character to be displayed to the left of the validation label. \
    * @return string $validation_stem_numeration ie. number, upper-alpha, lower-alpha  \
    */
    public function get_validation_stem_numeration() {
        return $this->validation_stem_numeration;
    }

    /**
    * Set Validation Stem Numeration \
    * Numeration character to be displayed to the left of the validation label. \
    * @param string $validation_stem_numeration ie. number, upper-alpha, lower-alpha  \
    */
    public function set_validation_stem_numeration ($validation_stem_numeration) {
        $this->validation_stem_numeration = $validation_stem_numeration;
    }

    
}

