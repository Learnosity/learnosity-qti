<?php

namespace LearnosityQti\Entities\QuestionTypes;

use LearnosityQti\Entities\BaseQuestionTypeAttribute;

/**
* This class is auto-generated based on Schemas API and you should not modify its content
* Metadata: {"responses":"v2.108.0","feedback":"v2.71.0","features":"v2.107.0"}
*/
class mcq_options_item extends BaseQuestionTypeAttribute {
    protected $value;
    protected $label;
    
    public function __construct()
    {
    }      

    /**
    * Get Value \
    * Value for this option that would be stored as the response if selected. \
    * @return string $value \
    */
    public function get_value() {
        return $this->value;
    }

    /**
    * Set Value \
    * Value for this option that would be stored as the response if selected. \
    * @param string $value \
    */
    public function set_value ($value) {
        $this->value = $value;
    }

    /**
    * Get Label \
    * Label to be displayed for this option - plain string with <a data-toggle='modal' href='#supportedClozeTemplateTags'>HTML
	 allowed</a> for formatting or mathjax syntax. \
    * @return string $label \
    */
    public function get_label() {
        return $this->label;
    }

    /**
    * Set Label \
    * Label to be displayed for this option - plain string with <a data-toggle='modal' href='#supportedClozeTemplateTags'>HTML
	 allowed</a> for formatting or mathjax syntax. \
    * @param string $label \
    */
    public function set_label ($label) {
        $this->label = $label;
    }

    
}

