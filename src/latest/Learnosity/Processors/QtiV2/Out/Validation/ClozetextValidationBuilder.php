<?php

namespace Learnosity\Processors\QtiV2\Out\Validation;

use Learnosity\Entities\QuestionTypes\clozetext_validation;
use Learnosity\Processors\QtiV2\Out\Constants;
use Learnosity\Services\LogService;
use qtism\data\processing\ResponseProcessing;
use qtism\data\state\CorrectResponse;
use qtism\data\state\ResponseDeclaration;
use qtism\data\state\ResponseDeclarationCollection;
use qtism\data\state\Value;
use qtism\data\state\ValueCollection;

class ClozetextValidationBuilder extends AbstractQuestionValidationBuilder
{
    protected function buildResponseDeclaration($responseIdentifier, $validation)
    {
        /** @var clozetext_validation $validation */

        // Remove `alt_responses` because couldn't support responseDeclaration with multiple valid answers
        if (!empty($validation->get_alt_responses())) {
            $validation->set_alt_responses([]);
            LogService::log('Fail to map multiple validation responses for `responseDeclaration`, only use `valid_response`, ignoring `alt_responses`');
        }

        if ($validation->get_valid_response()->get_score() != 1) {
            $validation->get_valid_response()->set_score(1);
            LogService::log('Only support mapping to `matchCorrect` template, thus validation score is changed to 1 and since mapped to QTI pre-defined `match_correct.xml` template');
        }

        // Since we split {{response}} to multiple interactions, so we would have multiple <responseDeclaration> as needed as well
        $responseDeclarationCollection = new ResponseDeclarationCollection();
        foreach ($validation->get_valid_response()->get_value() as $index => $value) {
            // We make assumption about interaction identifier shall always be the appended with index, ie. `_0`
            $responseDeclaration = new ResponseDeclaration($responseIdentifier . '_' . $index);
            $valueCollection = new ValueCollection();
            $valueCollection->attach(new Value($value));
            $responseDeclaration->setCorrectResponse(new CorrectResponse($valueCollection));
            $responseDeclarationCollection->attach($responseDeclaration);
        }

        return $responseDeclarationCollection;
    }

    protected function buildResponseProcessing($validation, $isCaseSensitive = true)
    {
        $responseProcessing = new ResponseProcessing();
        $responseProcessing->setTemplate(Constants::RESPONSE_PROCESSING_TEMPLATE_MATCH_CORRECT);
        return $responseProcessing;
    }}
