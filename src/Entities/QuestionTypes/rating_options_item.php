<?php

namespace LearnosityQti\Entities\QuestionTypes;

use LearnosityQti\Entities\BaseQuestionTypeAttribute;

/**
 * This class is auto-generated based on Schemas API and you should not modify its content
 * Metadata: {"responses":"v2.228.0","feedback":"v2.71.0","features":"v2.208.0"}
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
     * Indicates the worth of the rating and is stored as the response when selected. \
     * @return string $value \
     */
    public function get_value() {
        return $this->value;
    }

    /**
     * Set Value \
     * Indicates the worth of the rating and is stored as the response when selected. \
     * @param string $value \
     */
    public function set_value ($value) {
        $this->value = $value;
    }

    /**
     * Get Label \
     * The content that is displayed in the rating option button. This can be a percentage, number or a text label. It also sup
	 * ports unicode for emojis. \
     * @return string $label \
     */
    public function get_label() {
        return $this->label;
    }

    /**
     * Set Label \
     * The content that is displayed in the rating option button. This can be a percentage, number or a text label. It also sup
	 * ports unicode for emojis. \
     * @param string $label \
     */
    public function set_label ($label) {
        $this->label = $label;
    }

    /**
     * Get Tooltip \
     * A message displayed above the rating option button when a user hovers over the button. \
     * @return string $label_tooltip \
     */
    public function get_label_tooltip() {
        return $this->label_tooltip;
    }

    /**
     * Set Tooltip \
     * A message displayed above the rating option button when a user hovers over the button. \
     * @param string $label_tooltip \
     */
    public function set_label_tooltip ($label_tooltip) {
        $this->label_tooltip = $label_tooltip;
    }

    /**
     * Get Tint \
     * Sets the color for the response option tooltip, the text for that response option in the information tooltip, and the re
	 * sponse when displayed in review state. \
     * @return string $tint \
     */
    public function get_tint() {
        return $this->tint;
    }

    /**
     * Set Tint \
     * Sets the color for the response option tooltip, the text for that response option in the information tooltip, and the re
	 * sponse when displayed in review state. \
     * @param string $tint \
     */
    public function set_tint ($tint) {
        $this->tint = $tint;
    }

    /**
     * Get Description \
     * Text that describes the rating criteria. It will be shown within the information tooltip, if enabled. \
     * @return string $description \
     */
    public function get_description() {
        return $this->description;
    }

    /**
     * Set Description \
     * Text that describes the rating criteria. It will be shown within the information tooltip, if enabled. \
     * @param string $description \
     */
    public function set_description ($description) {
        $this->description = $description;
    }

    
}

