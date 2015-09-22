<?php

namespace Learnosity\Processors\QtiV2\Out\Validation;

use Learnosity\Entities\QuestionTypes\mcq_validation;
use Learnosity\Exceptions\MappingException;
use Learnosity\Processors\QtiV2\Out\Constants;
use qtism\common\enums\BaseType;
use qtism\common\enums\Cardinality;
use qtism\data\processing\ResponseProcessing;
use qtism\data\state\ResponseDeclaration;

class McqValidationBuilder extends AbstractQuestionValidationBuilder
{
    private $isMultipleResponse;
    private $valueIdentifierMap;

    public function __construct($isMultipleResponse, $valueIdentifierMap)
    {
        $this->isMultipleResponse = $isMultipleResponse;
        $this->valueIdentifierMap = $valueIdentifierMap;
    }

    protected function buildResponseDeclaration($responseIdentifier, $validation)
    {
        /** @var mcq_validation $validation */
        $responseDeclaration = new ResponseDeclaration($responseIdentifier);

        $responseDeclaration->setCardinality($this->isMultipleResponse ? Cardinality::MULTIPLE : Cardinality::SINGLE);
        $responseDeclaration->setBaseType(BaseType::IDENTIFIER);

        $correctResponseBuilder = new QtiCorrectResponseBuilder();
        $responseDeclaration->setCorrectResponse($correctResponseBuilder->buildWithBaseTypeIdentifier($validation, $this->valueIdentifierMap));
        $mappingBuilder = new QtiMappingBuilder();
        $responseDeclaration->setMapping($mappingBuilder->buildWithBaseTypeIdentifier($validation, $this->valueIdentifierMap));

        return $responseDeclaration;
    }
}
