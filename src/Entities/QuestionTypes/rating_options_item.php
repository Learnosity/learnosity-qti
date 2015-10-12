<?php

namespace LearnosityQti\Entities\QuestionTypes;

use LearnosityQti\Entities\BaseQuestionTypeAttribute;

/**
* This class is auto-generated based on Schemas API and you should not modify its content
* Metadata: {"responses":"v2.72.0","feedback":"v2.71.0","features":"v2.72.0"}
*/
class rating_options_item extends BaseQuestionTypeAttribute {
    protected $value;
    protected $label;
    protected $label_tooltip;
    protected $tint;
    protected $description;
    
    public function __construct(
            )
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
    * Number to be displayed for this option. \
    * @return string $label \
    */
    public function get_label() {
        return $this->label;
    }

    /**
    * Set Label \
    * Number to be displayed for this option. \
    * @param string $label \
    */
    public function set_label ($label) {
        $this->label = $label;
    }

    /**
    * Get Label tooltip \
    * Label to be displayed in the tooltip at the beginning of the description for this option. \
    * @return string $label_tooltip \
    */
    public function get_label_tooltip() {
        return $this->label_tooltip;
    }

    /**
    * Set Label tooltip \
    * Label to be displayed in the tooltip at the beginning of the description for this option. \
    * @param string $label_tooltip \
    */
    public function set_label_tooltip ($label_tooltip) {
        $this->label_tooltip = $label_tooltip;
    }

    /**
    * Get Tint \
    * Color to be displayed for this value on selection, in review state and within the tooltip \
    * @return string $tint \
    */
    public function get_tint() {
        return $this->tint;
    }

    /**
    * Set Tint \
    * Color to be displayed for this value on selection, in review state and within the tooltip \
    * @param string $tint \
    */
    public function set_tint ($tint) {
        $this->tint = $tint;
    }

    /**
    * Get Description \
    * Ranking / criteria, this will be shown within the tooltip along side \
    * @return string $description \
    */
    public function get_description() {
        return $this->description;
    }

    /**
    * Set Description \
    * Ranking / criteria, this will be shown within the tooltip along side \
    * @param string $description \
    */
    public function set_description ($description) {
        $this->description = $description;
    }

    
}

