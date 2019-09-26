<?php

namespace LearnosityQti\Processors\QtiV2\Out\Validation;

use qtism\common\enums\BaseType;
use qtism\common\enums\Cardinality;
use qtism\data\state\ResponseDeclaration;

class VideoplayerValidationBuilder extends AbstractQuestionValidationBuilder
{
    protected function buildResponseDeclaration($responseIdentifier, $validation)
    {
        /** @var plainText_validation $validation */
        $responseDeclaration = new ResponseDeclaration($responseIdentifier);
        $responseDeclaration->setCardinality(Cardinality::SINGLE);
        $responseDeclaration->setBaseType(BaseType::STRING);

        return $responseDeclaration;
    }

    public function buildValidation($responseIdentifier, $validation, $isCaseSensitive = true, $distractorRationaleResponseLevel = array())
    {
        $responseProcessing = null;
        
        $responseDeclaration = $this->buildResponseDeclaration($responseIdentifier, $validation);
        return [$responseDeclaration, $responseProcessing];
    }
}
