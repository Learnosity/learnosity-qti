<?php

namespace Learnosity\Processors\QtiV2\Out\Validation;

use Learnosity\Entities\QuestionTypes\orderlist_validation;
use Learnosity\Processors\QtiV2\Out\ResponseDeclarationBuilders\QtiCorrectResponseBuilder;
use qtism\common\enums\BaseType;
use qtism\common\enums\Cardinality;
use qtism\data\state\ResponseDeclaration;

class OrderlistValidationBuilder extends AbstractQuestionValidationBuilder
{
    private $indexIdentifiersMap;

    public function __construct(array $indexIdentifiersMap)
    {
        $this->indexIdentifiersMap = $indexIdentifiersMap;
    }

    protected function buildResponseDeclaration($responseIdentifier, $validation)
    {
        /** @var orderlist_validation $validation */

        $responseDeclaration = new ResponseDeclaration($responseIdentifier);
        $responseDeclaration->setCardinality(Cardinality::ORDERED);
        $responseDeclaration->setBaseType(BaseType::IDENTIFIER);

        $correctResponseBuilder = new QtiCorrectResponseBuilder();
        $responseDeclaration->setCorrectResponse($correctResponseBuilder->buildWithBaseTypeIdentifier($validation, $this->indexIdentifiersMap));

        return $responseDeclaration;
    }
}
