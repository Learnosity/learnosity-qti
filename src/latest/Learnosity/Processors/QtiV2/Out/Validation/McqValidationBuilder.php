<?php

namespace Learnosity\Processors\QtiV2\Out\Validation;

use Learnosity\Exceptions\MappingException;
use qtism\common\enums\BaseType;
use qtism\common\enums\Cardinality;
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
        if ($validation->get_scoring_type() !== 'exactMatch') {
            throw new MappingException('Does not support other scoring type mapping other than `exactNatch`');
        }

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
