<?php

namespace Learnosity\Processors\QtiV2\Out\Validation;

use Learnosity\Exceptions\MappingException;
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
        if ($validation->get_scoring_type() !== 'exactMatch') {
            throw new MappingException('Does not support other scoring type mapping other than `exactNatch`');
        }

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
