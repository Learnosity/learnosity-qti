<?php

namespace Learnosity\Processors\QtiV2\Out\Validation;

use Learnosity\Processors\QtiV2\Out\Constants;
use Learnosity\Services\LogService;
use qtism\data\processing\ResponseProcessing;

abstract class AbstractQuestionValidationBuilder
{
    private $supportedScoringType = ['exactMatch', 'partialMatch', 'partialMatchV2'];

    abstract protected function buildResponseDeclaration($responseIdentifier, $validation);

    // TODO: Only `single` cardinality is able to be mapped with `alt_responses`
    // abstract protected function buildResponseDeclarationCardinality();

    public function buildValidation($responseIdentifier, $validation, $isCaseSensitive = true)
    {
        // Some basic validation on the `validation` object
        if (empty($validation)) {
            return [null, null];
        }

        if (empty($validation->get_scoring_type()) || !in_array($validation->get_scoring_type(), $this->supportedScoringType)) {
            LogService::log('Invalid `scoring_type`, fail to build `responseDeclaration` and `responseProcessingTemplate');
            return [null, null];
        }

        if (empty($validation->get_valid_response()) || empty($validation->get_valid_response()->get_value()) || empty($validation->get_valid_response()->get_score())) {
            LogService::log('Invalid `valid_response` object, fail to build `responseDeclaration` and `responseProcessingTemplate');
            return [null, null];
        }

        if (!empty($validation->get_alt_responses())) {
            foreach ($validation->get_alt_responses() as $alt) {
                if (empty($alt->get_value()) || empty($alt->get_score())) {
                    LogService::log('Invalid `alt_responses` object, fail to build `responseDeclaration` and `responseProcessingTemplate');
                    return [null, null];
                }
            }
        }

        $responseProcessing = $this->buildResponseProcessing($validation, $isCaseSensitive);
        $responseDeclaration = $this->buildResponseDeclaration($responseIdentifier, $validation);
        return [$responseDeclaration, $responseProcessing];
    }

    protected function buildResponseProcessing($validation, $isCaseSensitive = true)
    {
        $responseProcessing = new ResponseProcessing();

        $scoringType = $validation->get_scoring_type();
        // If question has `exactMatch` and has `valid_response` and `alt_responses` with score of only `1`s
        // and it is not case sensitive, then it would be mapped to <correctResponse> with `match_correct` template
        if ($scoringType === 'exactMatch' && $this->canBeMappedToCorrectAnswer($validation) && $isCaseSensitive) {
            $responseProcessing->setTemplate(Constants::RESPONSE_PROCESSING_TEMPLATE_MATCH_CORRECT);
        } else {
            // Otherwise, we would need to build the `MapResponse`
            $responseProcessing->setTemplate(Constants::RESPONSE_PROCESSING_TEMPLATE_MAP_RESPONSE);
        }

        return $responseProcessing;
    }

    private function canBeMappedToCorrectAnswer($validation)
    {
        // Basically check whether all the score in `valid_response` and `alt_responses` are just simply 1s,
        // because `match_correct` simply expects those to be just 1s
        if (intval($validation->get_valid_response()->get_score()) !== 1) {
            return false;
        }
        if (!empty($validation->get_alt_responses())) {
            foreach ($validation->get_alt_responses() as $alt) {
                if (intval($alt->get_score()) !== 1) {
                    return false;
                }
            }
        }
        return true;
    }
}
