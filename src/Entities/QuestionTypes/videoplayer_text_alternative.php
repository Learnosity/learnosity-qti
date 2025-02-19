<?php

namespace LearnosityQti\Entities\QuestionTypes;

use LearnosityQti\Entities\BaseQuestionTypeAttribute;

/**
 * This class is auto-generated based on Schemas API and you should not modify its content
 * Metadata: {"responses":"v2.228.0","feedback":"v2.71.0","features":"v2.208.0"}
 */
class videoplayer_text_alternative extends BaseQuestionTypeAttribute {
    protected $caption;
    
    public function __construct(
            )
    {
            }

    /**
     * Get Caption \
     *  \
     * @return videoplayer_text_alternative_caption $caption \
     */
    public function get_caption() {
        return $this->caption;
    }

    /**
     * Set Caption \
     *  \
     * @param videoplayer_text_alternative_caption $caption \
     */
    public function set_caption (videoplayer_text_alternative_caption $caption) {
        $this->caption = $caption;
    }

    
}

