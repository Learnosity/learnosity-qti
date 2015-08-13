<?php

namespace Learnosity\Processors\QtiV2\Out\Validation;

use Learnosity\Exceptions\MappingException;
use qtism\common\enums\BaseType;
use qtism\common\enums\Cardinality;
use qtism\data\state\ResponseDeclaration;

class ShorttextValidationBuilder extends AbstractQuestionValidationBuilder
{
    function buildResponseDeclaration($responseIdentifier, $validation)
    {
        if ($validation->get_scoring_type() !== 'exactMatch') {
            throw new MappingException('Does not support other scoring type mapping other than `exactNatch`');
        }

        $responseDeclaration = new ResponseDeclaration($responseIdentifier);
        $responseDeclaration->setCardinality(Cardinality::SINGLE);
        $responseDeclaration->setBaseType(BaseType::STRING);

        $responseDeclaration->setCorrectResponse($this->buildCorrectResponse($validation));
        $responseDeclaration->setMapping($this->buildMapping($validation));

        return $responseDeclaration;
    }
}
