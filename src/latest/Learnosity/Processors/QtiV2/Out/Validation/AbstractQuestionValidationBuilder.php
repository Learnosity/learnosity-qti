<?php

namespace Learnosity\Processors\QtiV2\Out\Validation;

use Learnosity\Services\LogService;
use qtism\data\processing\ResponseProcessing;

abstract class AbstractQuestionValidationBuilder
{
    private $supportedScoringType = ['exactMatch', 'partialMatch', 'partialMatchV2'];

    abstract protected function buildResponseDeclarationForMatchCorrect($responseIdentifier, $validation);

    abstract protected function buildResponseDeclarationForMapResponse($responseIdentifier, $validation);

    public function buildValidation($responseIdentifier, $validation, $isCaseSensitive)
    {
        // Some basic validation on the `validation` object
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
        $scoringType = $validation->get_scoring_type();

        // If question has `exactMatch` and has `valid_response` and `alt_responses` with score of only `1`s
        // and it is not case sensitive, then it would be mapped to <correctResponse> with `match_correct` template
        if ($scoringType === 'exactMatch' && $this->canBeMappedToCorrectAnswer($validation) && $isCaseSensitive) {
            // TODO: Need to clean up validation `score` value since they can be string, ie '1' instead of 1
            $responseDeclaration = $this->buildResponseDeclarationForMatchCorrect($responseIdentifier, $validation);
            $responseProcessing = new ResponseProcessing();
            $responseProcessing->setTemplate('http://www.imsglobal.org/question/qtiv2p1/rptemplates/match_correct.xml');
            return [$responseDeclaration, $responseProcessing];
        }

        // Otherwise, we would need to build the `MapResponse`
        LogService::log('Validation object could not be supported yet ~');
        return [null, null];
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
