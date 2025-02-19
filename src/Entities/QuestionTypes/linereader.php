<?php

namespace LearnosityQti\Entities\QuestionTypes;

use LearnosityQti\Entities\BaseQuestionType;

/**
 * This class is auto-generated based on Schemas API and you should not modify its content
 * Metadata: {"responses":"v2.228.0","feedback":"v2.71.0","features":"v2.208.0"}
 */
class linereader extends BaseQuestionType {
    protected $type;
    protected $metadata;
    protected $simplefeature_id;
    protected $button;
    protected $visible;
    
    public function __construct(
                    $type
                        )
    {
                $this->type = $type;
            }

    /**
     * Get Feature Type \
     * Use the value 'linereader' for this field. \
     * @return string $type \
     */
    public function get_type() {
        return $this->type;
    }

    /**
     * Set Feature Type \
     * Use the value 'linereader' for this field. \
     * @param string $type \
     */
    public function set_type ($type) {
        $this->type = $type;
    }

    /**
     * Get metadata \
     *  \
     * @return object $metadata \
     */
    public function get_metadata() {
        return $this->metadata;
    }

    /**
     * Set metadata \
     *  \
     * @param object $metadata \
     */
    public function set_metadata ($metadata) {
        $this->metadata = $metadata;
    }

    /**
     * Get Simple feature reference \
     *  \
     * @return string $simplefeature_id \
     */
    public function get_simplefeature_id() {
        return $this->simplefeature_id;
    }

    /**
     * Set Simple feature reference \
     *  \
     * @param string $simplefeature_id \
     */
    public function set_simplefeature_id ($simplefeature_id) {
        $this->simplefeature_id = $simplefeature_id;
    }

    /**
     * Get Button \
     * A button to toggle the Line Reader is displayed when this value is true. \
     * @return boolean $button \
     */
    public function get_button() {
        return $this->button;
    }

    /**
     * Set Button \
     * A button to toggle the Line Reader is displayed when this value is true. \
     * @param boolean $button \
     */
    public function set_button ($button) {
        $this->button = $button;
    }

    /**
     * Get Initial visibility \
     * This value determines the initial visibility of the Line Reader. \
     * @return boolean $visible \
     */
    public function get_visible() {
        return $this->visible;
    }

    /**
     * Set Initial visibility \
     * This value determines the initial visibility of the Line Reader. \
     * @param boolean $visible \
     */
    public function set_visible ($visible) {
        $this->visible = $visible;
    }

    
    public function get_widget_type() {
    return 'feature';
    }
}

