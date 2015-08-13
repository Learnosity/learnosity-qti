<?php

namespace Learnosity\Processors\QtiV2\Out\Validation;

use Learnosity\Entities\QuestionTypes\shorttext_validation_alt_responses_item;
use Learnosity\Processors\QtiV2\Out\Constants;
use Learnosity\Services\LogService;
use qtism\data\processing\ResponseProcessing;
use qtism\data\state\CorrectResponse;
use qtism\data\state\MapEntry;
use qtism\data\state\MapEntryCollection;
use qtism\data\state\Mapping;
use qtism\data\state\Value;
use qtism\data\state\ValueCollection;

abstract class AbstractQuestionValidationBuilder
{
    private $supportedScoringType = ['exactMatch', 'partialMatch', 'partialMatchV2'];

    abstract function buildResponseDeclaration($responseIdentifier, $validation);

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

        $responseProcessing = new ResponseProcessing();
        // If question has `exactMatch` and has `valid_response` and `alt_responses` with score of only `1`s
        // and it is not case sensitive, then it would be mapped to <correctResponse> with `match_correct` template
        if ($scoringType === 'exactMatch' && $this->canBeMappedToCorrectAnswer($validation) && $isCaseSensitive) {
            $responseProcessing->setTemplate(Constants::RESPONSE_PROCESSING_TEMPLATE_MATCH_CORRECT);
        } else {
            // Otherwise, we would need to build the `MapResponse`
            LogService::log('Validation object could not be supported yet ~');
            $responseProcessing->setTemplate(Constants::RESPONSE_PROCESSING_TEMPLATE_MAP_RESPONSE);
        }

        $responseDeclaration = $this->buildResponseDeclaration($responseIdentifier, $validation);
        return [$responseDeclaration, $responseProcessing];
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

    protected function buildCorrectResponse($validation)
    {
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
        return new CorrectResponse($values);
    }

    protected function buildMapping($validation)
    {
        // Handle `valid_response`
        $mapEntries = new MapEntryCollection();
        $mapEntries->attach(new MapEntry($validation->get_valid_response()->get_value(), floatval($validation->get_valid_response()->get_score())));
        // Handle `alt_responses`
        /** @var shorttext_validation_alt_responses_item $alt */
        if (count($validation->get_alt_responses()) > 0) {
            foreach ($validation->get_alt_responses() as $alt) {
                $mapEntries->attach(new MapEntry($alt->get_value(), floatval($alt->get_score())));
            }
        }
        return new Mapping($mapEntries);
    }
}
