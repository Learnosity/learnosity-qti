<?php

namespace Learnosity\Processors\QtiV2\Out\Validation;

use Learnosity\Entities\QuestionTypes\shorttext_validation;
use Learnosity\Entities\QuestionTypes\shorttext_validation_alt_responses_item;
use qtism\common\enums\Cardinality;
use qtism\data\state\CorrectResponse;
use qtism\data\state\ResponseDeclaration;
use qtism\data\state\Value;
use qtism\data\state\ValueCollection;

class ShorttextValidationBuilder extends AbstractQuestionValidationBuilder
{
    protected function buildResponseDeclarationForMatchCorrect($responseIdentifier, $validation)
    {
        /** @var shorttext_validation $validation */
        $responseDeclaration = new ResponseDeclaration($responseIdentifier);
        $responseDeclaration->setCardinality(Cardinality::SINGLE);

        // Handle `valid_response`
        $values = new ValueCollection();
        $values->attach(new Value($validation->get_valid_response()->get_value()));

        // Handle `alt_responses`
        /** @var shorttext_validation_alt_responses_item $alt */
        if (count($validation->get_alt_responses()) >= 1) {
            foreach ($validation->get_alt_responses() as $alt) {
                $values->attach(new Value($alt->get_value()));
            }
        }

        // Build correct responses based on that
        $correctResponse = new CorrectResponse($values);
        $responseDeclaration->setCorrectResponse($correctResponse);
        return $responseDeclaration;
    }

    protected function buildResponseDeclarationForMapResponse($responseIdentifier, $validation)
    {
        // TODO: Todo!
        return null;
    }
}
