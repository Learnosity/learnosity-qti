<?php

namespace LearnosityQti\Processors\QtiV2\Out\Validation;

use LearnosityQti\Entities\QuestionTypes\mcq_validation;
use LearnosityQti\Processors\QtiV2\Out\ResponseDeclarationBuilders\QtiCorrectResponseBuilder;
use qtism\common\enums\BaseType;
use qtism\common\enums\Cardinality;
use qtism\data\state\ResponseDeclaration;

class LongtextValidationBuilder extends AbstractQuestionValidationBuilder
{

    protected function buildResponseDeclaration($responseIdentifier)
    {
        /** @var longtext_validation $validation */
        $responseDeclaration = new ResponseDeclaration($responseIdentifier);

        $responseDeclaration->setCardinality(Cardinality::SINGLE);
        $responseDeclaration->setBaseType(BaseType::STRING);
        return $responseDeclaration;
    }
}
