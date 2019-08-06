<?php

namespace LearnosityQti\Processors\QtiV2\Out\Validation;

use LearnosityQti\Entities\QuestionTypes\mcq_validation;
use LearnosityQti\Processors\QtiV2\Out\ResponseDeclarationBuilders\QtiCorrectResponseBuilder;
use qtism\common\enums\BaseType;
use qtism\common\enums\Cardinality;
use qtism\data\state\ResponseDeclaration;

class PlaintextValidationBuilder extends AbstractQuestionValidationBuilder
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
        if (empty($validation) && method_exists($validation, 'get_max_score') && empty($validation->get_max_score())) {
            // TODO: Need to support more validation type :)
            LogService::log('Invalid value of max_score. Failed to build `responseDeclaration` and `responseProcessingTemplate');
        }

        // if found distractor_rationale_response_level generate response processing with setoutcome value FEEDBACK
        if (!empty($distractorRationaleResponseLevel) && is_array($distractorRationaleResponseLevel)) {
            $score = $validation->get_valid_response()->get_score();
            $responseProcessing = QtiResponseProcessingBuilder::build($score);
        } elseif ($validation != null) {
            $responseProcessing = $this->buildResponseProcessing($validation, $isCaseSensitive);
        }

        $responseDeclaration = $this->buildResponseDeclaration($responseIdentifier, $validation);
        return [$responseDeclaration, $responseProcessing];
    }
}
