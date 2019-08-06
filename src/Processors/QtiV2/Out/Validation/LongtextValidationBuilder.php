<?php

namespace LearnosityQti\Processors\QtiV2\Out\Validation;

use LearnosityQti\Processors\QtiV2\Out\ResponseProcessing\QtiResponseProcessingBuilder;
use qtism\common\enums\BaseType;
use qtism\common\enums\Cardinality;
use qtism\data\state\ResponseDeclaration;

class LongtextValidationBuilder extends AbstractQuestionValidationBuilder
{

    protected function buildResponseDeclaration($responseIdentifier, $validation)
    {
        /** @var longtext_validation $validation */
        $responseDeclaration = new ResponseDeclaration($responseIdentifier);
        $responseDeclaration->setCardinality(Cardinality::SINGLE);
        $responseDeclaration->setBaseType(BaseType::STRING);
        return $responseDeclaration;
    }

    public function buildValidation($responseIdentifier, $validation, $isCaseSensitive = true, $distractorRationaleResponseLevel = array())
    {

        $responseProcessing = null;
        if (empty($validation) && method_exists($validation, 'get_max_score') && empty($validation->get_max_score())) {
            // TODO: Need to support more validation type :)
            LogService::log('Invalid value of max_score. Failed to build `responseDeclaration` and `responseProcessingTemplate');
        }

        if ($validation != null) {
            $responseProcessing = $this->buildResponseProcessing($validation, $isCaseSensitive);
        }

        $responseDeclaration = $this->buildResponseDeclaration($responseIdentifier, $validation);
        return [$responseDeclaration, $responseProcessing];
    }
}
