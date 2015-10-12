<?php

namespace LearnosityQti\Entities\QuestionTypes;

use LearnosityQti\Entities\BaseQuestionTypeAttribute;

/**
* This class is auto-generated based on Schemas API and you should not modify its content
* Metadata: {"responses":"v2.72.0","feedback":"v2.71.0","features":"v2.72.0"}
*/
class audioplayer_metadata extends BaseQuestionTypeAttribute {
    protected $transcript;
    
    public function __construct(
            )
    {
            }

    /**
    * Get Transcript \
    * The transcript for the audio being played \
    * @return string $transcript \
    */
    public function get_transcript() {
        return $this->transcript;
    }

    /**
    * Set Transcript \
    * The transcript for the audio being played \
    * @param string $transcript \
    */
    public function set_transcript ($transcript) {
        $this->transcript = $transcript;
    }

    
}

