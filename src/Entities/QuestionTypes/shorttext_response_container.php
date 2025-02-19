<?php

namespace LearnosityQti\Entities\QuestionTypes;

use LearnosityQti\Entities\BaseQuestionTypeAttribute;

/**
 * This class is auto-generated based on Schemas API and you should not modify its content
 * Metadata: {"responses":"v2.228.0","feedback":"v2.71.0","features":"v2.208.0"}
 */
class shorttext_response_container extends BaseQuestionTypeAttribute {
    protected $input_type;
    protected $aria_label;
    
    public function __construct(
            )
    {
            }

    /**
     * Get Input type \
     * Type of input \
     * @return string $input_type \
     */
    public function get_input_type() {
        return $this->input_type;
    }

    /**
     * Set Input type \
     * Type of input \
     * @param string $input_type \
     */
    public function set_input_type ($input_type) {
        $this->input_type = $input_type;
    }

    /**
     * Get ARIA label \
     * The ARIA label for the response container. It will have a default value if no label is provided. \
     * @return string $aria_label \
     */
    public function get_aria_label() {
        return $this->aria_label;
    }

    /**
     * Set ARIA label \
     * The ARIA label for the response container. It will have a default value if no label is provided. \
     * @param string $aria_label \
     */
    public function set_aria_label ($aria_label) {
        $this->aria_label = $aria_label;
    }

    
}

