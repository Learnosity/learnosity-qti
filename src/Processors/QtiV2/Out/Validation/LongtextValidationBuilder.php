<?php

namespace LearnosityQti\Processors\QtiV2\Out\Validation;

use LearnosityQti\Entities\QuestionTypes\mcq_validation;
use LearnosityQti\Processors\QtiV2\Out\ResponseDeclarationBuilders\QtiCorrectResponseBuilder;
use qtism\common\enums\BaseType;
use qtism\common\enums\Cardinality;
use qtism\data\state\ResponseDeclaration;

class LongtextValidationBuilder extends AbstractQuestionValidationBuilder
{
    protected $valiation = '';
    protected function buildResponseDeclaration($responseIdentifier, $validation)
    {
        /** @var mcq_validation $validation */
        $responseDeclaration = new ResponseDeclaration($responseIdentifier);

        $responseDeclaration->setCardinality(Cardinality::SINGLE);
        $responseDeclaration->setBaseType(BaseType::IDENTIFIER);

        //$correctResponseBuilder = new QtiCorrectResponseBuilder();
        //$responseDeclaration->setCorrectResponse($correctResponseBuilder->buildWithBaseTypeIdentifier($validation, ''));

        return $responseDeclaration;
    }
}
