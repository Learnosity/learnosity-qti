<?php

namespace LearnosityQti\Entities\QuestionTypes;

use LearnosityQti\Entities\BaseQuestionTypeAttribute;

/**
* This class is auto-generated based on Schemas API and you should not modify its content
* Metadata: {"responses":"v2.107.0","feedback":"v2.71.0","features":"v2.107.0"}
*/
class formulaV2_validation_alt_responses_item_value_item extends BaseQuestionTypeAttribute {
    protected $method;
    
    public function __construct(
            )
    {
            }

    /**
    * Get Method \
    * The method used to compare user input against the valid response value. \
    * @return string $method ie. equivSymbolic, equivLiteral, equivValue, isSimplified, isFactorised, isExpanded, isUnit, isTrue, stringMatch, equivSyntax  \
    */
    public function get_method() {
        return $this->method;
    }

    /**
    * Set Method \
    * The method used to compare user input against the valid response value. \
    * @param string $method ie. equivSymbolic, equivLiteral, equivValue, isSimplified, isFactorised, isExpanded, isUnit, isTrue, stringMatch, equivSyntax  \
    */
    public function set_method ($method) {
        $this->method = $method;
    }

    
}

