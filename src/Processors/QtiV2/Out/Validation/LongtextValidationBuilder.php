<?php

namespace LearnosityQti\Processors\QtiV2\Out\Validation;

use qtism\common\enums\BaseType;
use qtism\common\enums\Cardinality;
use qtism\data\state\ResponseDeclaration;

class LongtextValidationBuilder extends AbstractQuestionValidationBuilder
{

    protected function buildResponseDeclaration($responseIdentifier, $validation)
    {
        /** @var longtext_validation $validation */
        $responseDeclaration = new ResponseDeclaration($responseIdentifier);
        $responseDeclaration->setCardinality(Cardinality::SINGLE);
        $responseDeclaration->setBaseType(BaseType::STRING);
        return $responseDeclaration;
    }
}
