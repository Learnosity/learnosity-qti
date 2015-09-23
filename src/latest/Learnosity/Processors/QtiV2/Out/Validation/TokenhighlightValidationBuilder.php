<?php

namespace Learnosity\Processors\QtiV2\Out\Validation;

use Learnosity\Entities\QuestionTypes\tokenhighlight_validation;
use Learnosity\Processors\QtiV2\Out\ResponseDeclarationBuilders\QtiCorrectResponseBuilder;
use qtism\common\enums\BaseType;
use qtism\common\enums\Cardinality;
use qtism\data\state\ResponseDeclaration;

class TokenhighlightValidationBuilder extends AbstractQuestionValidationBuilder
{
    private $indexIdentifierMap;

    public function __construct(array $indexIdentifierMap)
    {
        $this->indexIdentifierMap = $indexIdentifierMap;
    }

    protected function buildResponseDeclaration($responseIdentifier, $validation)
    {
        /** @var tokenhighlight_validation $validation */
        $responseDeclaration = new ResponseDeclaration($responseIdentifier, BaseType::IDENTIFIER);
        $answersCount = count($validation->get_valid_response()->get_value());
        $responseDeclaration->setCardinality($answersCount <= 1 ? Cardinality::SINGLE : Cardinality::MULTIPLE);

        $correctResponseBuilder = new QtiCorrectResponseBuilder();
        $responseDeclaration->setCorrectResponse($correctResponseBuilder->buildWithBaseTypeIdentifier($validation, $this->indexIdentifierMap));

        return $responseDeclaration;
    }
}
