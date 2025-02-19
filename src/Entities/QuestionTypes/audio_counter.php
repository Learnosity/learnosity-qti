<?php

namespace LearnosityQti\Entities\QuestionTypes;

use LearnosityQti\Entities\BaseQuestionTypeAttribute;

/**
 * This class is auto-generated based on Schemas API and you should not modify its content
 * Metadata: {"responses":"v2.228.0","feedback":"v2.71.0","features":"v2.208.0"}
 */
class audio_counter extends BaseQuestionTypeAttribute {
    protected $length;
    protected $template;
    
    public function __construct(
            )
    {
            }

    /**
     * Get Countdown to record (in seconds) \
     * Required countdown in seconds to start recording. \
     * @return number $length \
     */
    public function get_length() {
        return $this->length;
    }

    /**
     * Set Countdown to record (in seconds) \
     * Required countdown in seconds to start recording. \
     * @param number $length \
     */
    public function set_length ($length) {
        $this->length = $length;
    }

    /**
     * Get template \
     * Specify custom message to wrap around the counter. 
If custom message is not provided then default message is used.  
Re
	 * quires {num} in custom message for the counter to be injected. \
     * @return string $template \
     */
    public function get_template() {
        return $this->template;
    }

    /**
     * Set template \
     * Specify custom message to wrap around the counter. 
If custom message is not provided then default message is used.  
Re
	 * quires {num} in custom message for the counter to be injected. \
     * @param string $template \
     */
    public function set_template ($template) {
        $this->template = $template;
    }

    
}

