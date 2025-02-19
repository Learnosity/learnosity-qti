<?php

namespace LearnosityQti\Entities\QuestionTypes;

use LearnosityQti\Entities\BaseQuestionTypeAttribute;

/**
 * This class is auto-generated based on Schemas API and you should not modify its content
 * Metadata: {"responses":"v2.228.0","feedback":"v2.71.0","features":"v2.208.0"}
 */
class multistepmath_hints_items_item extends BaseQuestionTypeAttribute {
    protected $content;
    
    public function __construct(
            )
    {
            }

    /**
     * Get content \
     *  \
     * @return string $content \
     */
    public function get_content() {
        return $this->content;
    }

    /**
     * Set content \
     *  \
     * @param string $content \
     */
    public function set_content ($content) {
        $this->content = $content;
    }

    
}

