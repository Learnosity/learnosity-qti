<?php

namespace Learnosity\Entities\QuestionTypes;

use Learnosity\Entities\BaseQuestionTypeAttribute;

/**
* This class is auto-generated based on Schemas API and you should not modify its content
* Metadata: {"responses":"v2.68.0","feedback":"v2.35.0","features":"v2.68.0"}
*/
class formulaV2_validation_valid_response_value_item extends BaseQuestionTypeAttribute {
    protected $method;
    
    public function __construct(
            )
    {
            }

    /**
    * Get Method \
    * The method used to compare user input against the valid response value. \
    * @return string $method ie. equivSymbolic, equivLiteral, equivValue, isSimplified, isFactorised, isExpanded, isUnit, isTrue, equivSyntax  \
    */
    public function get_method() {
        return $this->method;
    }

    /**
    * Set Method \
    * The method used to compare user input against the valid response value. \
    * @param string $method ie. equivSymbolic, equivLiteral, equivValue, isSimplified, isFactorised, isExpanded, isUnit, isTrue, equivSyntax  \
    */
    public function set_method ($method) {
        $this->method = $method;
    }

    
}

