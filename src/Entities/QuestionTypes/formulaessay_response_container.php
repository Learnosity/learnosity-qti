<?php

namespace LearnosityQti\Entities\QuestionTypes;

use LearnosityQti\Entities\BaseQuestionTypeAttribute;

/**
 * This class is auto-generated based on Schemas API and you should not modify its content
 * Metadata: {"responses":"v2.228.0","feedback":"v2.71.0","features":"v2.208.0"}
 */
class formulaessay_response_container extends BaseQuestionTypeAttribute {
    protected $aria_label;
    
    public function __construct(
            )
    {
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

    
}

