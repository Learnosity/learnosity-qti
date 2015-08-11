<?php

namespace Learnosity\Entities\QuestionTypes;

use Learnosity\Entities\BaseQuestionTypeAttribute;

/**
* This class is auto-generated based on Schemas API and you should not modify its content
* Metadata: {"responses":"v2.68.0","feedback":"v2.35.0","features":"v2.68.0"}
*/
class simpleshading_validation_valid_response_value extends BaseQuestionTypeAttribute {
    protected $method;
    
    public function __construct(
            )
    {
            }

    /**
    * Get Method \
    * Score the response based on exact locations. \
    * @return string $method ie. byLocation, byCount  \
    */
    public function get_method() {
        return $this->method;
    }

    /**
    * Set Method \
    * Score the response based on exact locations. \
    * @param string $method ie. byLocation, byCount  \
    */
    public function set_method ($method) {
        $this->method = $method;
    }

    
}

