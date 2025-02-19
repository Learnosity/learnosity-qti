<?php

namespace LearnosityQti\Entities\QuestionTypes;

use LearnosityQti\Entities\BaseQuestionTypeAttribute;

/**
 * This class is auto-generated based on Schemas API and you should not modify its content
 * Metadata: {"responses":"v2.228.0","feedback":"v2.71.0","features":"v2.208.0"}
 */
class mcq_options_item_assistive_label extends BaseQuestionTypeAttribute {
    protected $label;
    protected $exposed_visible_label;
    
    public function __construct(
            )
    {
            }

    /**
     * Get Custom ARIA label \
     * A plain text description for accessibility users that is accessible by screen readers and assistive technologies when na
	 * vigating to the response. \
     * @return string $label \
     */
    public function get_label() {
        return $this->label;
    }

    /**
     * Set Custom ARIA label \
     * A plain text description for accessibility users that is accessible by screen readers and assistive technologies when na
	 * vigating to the response. \
     * @param string $label \
     */
    public function set_label ($label) {
        $this->label = $label;
    }

    /**
     * Get Visible content is navigatable by a screen reader \
     * If this option is checked, then the visible text can be navigated and spoken by screen readers. If the option is not che
	 * cked and the custom aria-label is empty, the content is flattened and will be read out as one piece of information to th
	 * e learner. \
     * @return boolean $exposed_visible_label \
     */
    public function get_exposed_visible_label() {
        return $this->exposed_visible_label;
    }

    /**
     * Set Visible content is navigatable by a screen reader \
     * If this option is checked, then the visible text can be navigated and spoken by screen readers. If the option is not che
	 * cked and the custom aria-label is empty, the content is flattened and will be read out as one piece of information to th
	 * e learner. \
     * @param boolean $exposed_visible_label \
     */
    public function set_exposed_visible_label ($exposed_visible_label) {
        $this->exposed_visible_label = $exposed_visible_label;
    }

    
}

