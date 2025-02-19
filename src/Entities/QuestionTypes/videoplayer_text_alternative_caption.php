<?php

namespace LearnosityQti\Entities\QuestionTypes;

use LearnosityQti\Entities\BaseQuestionTypeAttribute;

/**
 * This class is auto-generated based on Schemas API and you should not modify its content
 * Metadata: {"responses":"v2.228.0","feedback":"v2.71.0","features":"v2.208.0"}
 */
class videoplayer_text_alternative_caption extends BaseQuestionTypeAttribute {
    protected $src;
    
    public function __construct(
            )
    {
            }

    /**
     * Get Caption URL \
     * A link to your hosted video's caption file. You can upload an VTT file. \
     * @return string $src \
     */
    public function get_src() {
        return $this->src;
    }

    /**
     * Set Caption URL \
     * A link to your hosted video's caption file. You can upload an VTT file. \
     * @param string $src \
     */
    public function set_src ($src) {
        $this->src = $src;
    }

    
}

