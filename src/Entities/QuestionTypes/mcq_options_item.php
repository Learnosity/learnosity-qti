<?php

namespace LearnosityQti\Entities\QuestionTypes;

use LearnosityQti\Entities\BaseQuestionTypeAttribute;

/**
 * This class is auto-generated based on Schemas API and you should not modify its content
 * Metadata: {"responses":"v2.228.0","feedback":"v2.71.0","features":"v2.208.0"}
 */
class mcq_options_item extends BaseQuestionTypeAttribute {
    protected $value;
    protected $label;
    protected $assistive_label;
    
    public function __construct(
            )
    {
            }

    /**
     * Get Value \
     * Value for this option that would be stored as the response if selected. \
     * @return editorMcqOptionValue $value \
     */
    public function get_value() {
        return $this->value;
    }

    /**
     * Set Value \
     * Value for this option that would be stored as the response if selected. \
     * @param editorMcqOptionValue $value \
     */
    public function set_value ($value) {
        $this->value = $value;
    }

    /**
     * Get Label \
     * Label to be displayed for this option - plain string with <a data-toggle='modal' href='#supportedClozeTemplateTags'>HTML
	 *  allowed</a> for formatting or mathjax syntax. \
     * @return editor $label \
     */
    public function get_label() {
        return $this->label;
    }

    /**
     * Set Label \
     * Label to be displayed for this option - plain string with <a data-toggle='modal' href='#supportedClozeTemplateTags'>HTML
	 *  allowed</a> for formatting or mathjax syntax. \
     * @param editor $label \
     */
    public function set_label ($label) {
        $this->label = $label;
    }

    /**
     * Get assistive_label \
     *  \
     * @return mcq_options_item_assistive_label $assistive_label \
     */
    public function get_assistive_label() {
        return $this->assistive_label;
    }

    /**
     * Set assistive_label \
     *  \
     * @param mcq_options_item_assistive_label $assistive_label \
     */
    public function set_assistive_label (mcq_options_item_assistive_label $assistive_label) {
        $this->assistive_label = $assistive_label;
    }

    
}

