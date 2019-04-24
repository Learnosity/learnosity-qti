<?php

namespace LearnosityQti\Processors\QtiV2\Out\Validation;

use LearnosityQti\Processors\QtiV2\Out\ResponseDeclarationBuilders\QtiCorrectResponseBuilder;
use LearnosityQti\Processors\QtiV2\Out\ResponseDeclarationBuilders\QtiMappingBuilder;
use qtism\common\enums\BaseType;
use qtism\common\enums\Cardinality;
use qtism\data\state\MapEntry;
use qtism\data\state\ResponseDeclaration;

class ShorttextValidationBuilder extends AbstractQuestionValidationBuilder
{
    private $isCaseSensitive;

    public function __construct($isCaseSensitive)
    {
        $this->isCaseSensitive = $isCaseSensitive;
    }

    protected function buildResponseDeclaration($responseIdentifier, $validation)
    {
        
        $responseDeclaration = new ResponseDeclaration($responseIdentifier);
        $responseDeclaration->setCardinality(Cardinality::SINGLE);
        $responseDeclaration->setBaseType(BaseType::STRING);

        $correctResponseBuilder = new QtiCorrectResponseBuilder();
        $responseDeclaration->setCorrectResponse($correctResponseBuilder->build($validation));

        $mappingResponseBuilder = new QtiMappingBuilder();
        $mapping = $mappingResponseBuilder->build($validation);
        $responseDeclaration->setMapping($mapping);

        foreach ($mapping->getMapEntries() as $mapEntry) {
            /** @var MapEntry $mapEntry */
            $mapEntry->setCaseSensitive($this->isCaseSensitive);
        }
        return $responseDeclaration;
    }
}
