<?php

namespace LearnosityQti\Entities\QuestionTypes;

use LearnosityQti\Entities\BaseQuestionTypeAttribute;

/**
* This class is auto-generated based on Schemas API and you should not modify its content
* Metadata: {"responses":"v2.84.0","feedback":"v2.71.0","features":"v2.84.0"}
*/
class shorttext_response_container extends BaseQuestionTypeAttribute {
    protected $input_type;
    
    public function __construct(
            )
    {
            }

    /**
    * Get Input type \
    * Type of input \
    * @return string $input_type \
    */
    public function get_input_type() {
        return $this->input_type;
    }

    /**
    * Set Input type \
    * Type of input \
    * @param string $input_type \
    */
    public function set_input_type ($input_type) {
        $this->input_type = $input_type;
    }

    
}

