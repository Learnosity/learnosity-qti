<?php

namespace LearnosityQti\Entities\QuestionTypes;

use LearnosityQti\Entities\BaseQuestionTypeAttribute;

/**
 * This class is auto-generated based on Schemas API and you should not modify its content
 * Metadata: {"responses":"v2.228.0","feedback":"v2.71.0","features":"v2.208.0"}
 */
class clozeformulaV2_hints extends BaseQuestionTypeAttribute {
    protected $items;
    
    public function __construct(
            )
    {
            }

    /**
     * Get items \
     *  \
     * @return array $items \
     */
    public function get_items() {
        return $this->items;
    }

    /**
     * Set items \
     *  \
     * @param array $items \
     */
    public function set_items (array $items) {
        $this->items = $items;
    }

    
}

