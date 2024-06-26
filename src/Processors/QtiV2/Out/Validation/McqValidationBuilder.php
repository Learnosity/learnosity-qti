<?php

namespace LearnosityQti\Processors\QtiV2\Out\Validation;

use LearnosityQti\Entities\QuestionTypes\mcq_validation;
use LearnosityQti\Exceptions\MappingException;
use LearnosityQti\Processors\QtiV2\Out\ResponseDeclarationBuilders\QtiCorrectResponseBuilder;
use qtism\common\enums\BaseType;
use qtism\common\enums\Cardinality;
use qtism\data\state\ResponseDeclaration;

class McqValidationBuilder extends AbstractQuestionValidationBuilder
{
    private bool $isMultipleResponse;
    private array $valueIdentifierMap;

    public function __construct($isMultipleResponse, $valueIdentifierMap)
    {
        $this->isMultipleResponse = $isMultipleResponse;
        $this->valueIdentifierMap = $valueIdentifierMap;
    }

    /**
     * @throws MappingException
     */
    protected function buildResponseDeclaration(
        $responseIdentifier,
        $validation
    ): ResponseDeclaration {
        /** @var mcq_validation $validation */
        $responseDeclaration = new ResponseDeclaration($responseIdentifier);

        $responseDeclaration->setCardinality(
            $this->isMultipleResponse ?
                Cardinality::MULTIPLE
                : Cardinality::SINGLE
        );

        $responseDeclaration->setBaseType(BaseType::IDENTIFIER);

        $correctResponseBuilder = new QtiCorrectResponseBuilder();
        $responseDeclaration->setCorrectResponse(
            $correctResponseBuilder->buildWithBaseTypeIdentifier(
                $validation,
                $this->valueIdentifierMap,
            ),
        );

        return $responseDeclaration;
    }
}
