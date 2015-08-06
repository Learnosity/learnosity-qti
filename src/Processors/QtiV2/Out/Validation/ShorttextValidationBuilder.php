<?php

namespace Learnosity\Processors\QtiV2\Out\Validation;

use Learnosity\Entities\QuestionTypes\shorttext_validation;
use qtism\common\enums\Cardinality;
use qtism\data\state\ResponseDeclaration;

class ShorttextValidationBuilder extends AbstractQuestionValidationBuilder
{
    /**
     * @param int $responseIdentifier
     * @param shorttext_validation $validation
     *
     * @return ResponseDeclaration
     */
    protected function buildResponseDeclarationForMatchCorrect($responseIdentifier, $validation)
    {
        $responseDeclaration = new ResponseDeclaration($responseIdentifier);
        $responseDeclaration->setCardinality(Cardinality::SINGLE);

        // TODO: Not yet finished
        return $responseDeclaration;
    }

    protected function buildResponseDeclarationForMapResponse($responseIdentifier, $validation)
    {
        // TODO: Todo!
        return null;
    }
}
