<?php

namespace LearnosityQti\Processors\QtiV2\Out\Validation;

use LearnosityQti\Processors\QtiV2\Out\Constants;
use LearnosityQti\Processors\QtiV2\Out\ResponseProcessing\QtiResponseProcessingBuilder;
use LearnosityQti\Services\LogService;
use qtism\data\processing\ResponseProcessing;

abstract class AbstractQuestionValidationBuilder
{
    private $supportedScoringType = ['exactMatch'];

    abstract protected function buildResponseDeclaration($responseIdentifier, $validation);

    public function buildValidation($responseIdentifier, $validation, $isCaseSensitive = true, $feedBackOptions = array())
    {
        // Some basic validation on the `validation` object
        if (empty($validation) && empty($feedBackOptions)) {
            return [null, null];
        }

        if (empty($validation->get_scoring_type()) || !in_array($validation->get_scoring_type(), $this->supportedScoringType)) {
            // TODO: Need to support more validation type :)
            LogService::log('Invalid `scoring_type`, only supported `exactMatch`. Failed to build `responseDeclaration` and `responseProcessingTemplate');
            return [null, null];
        }

        if (empty($validation->get_valid_response()) || empty($validation->get_valid_response()->get_value()) || empty($validation->get_valid_response()->get_score())) {
            LogService::log('Invalid `valid_response` object, fail to build `responseDeclaration` and `responseProcessingTemplate');
            return [null, null];
        }

        $responseDeclaration = $this->buildResponseDeclaration($responseIdentifier, $validation);
        $responseIdentifiers = [];
        foreach ($responseDeclaration as $key => $value) {
            $responseIdentifiers[] = $key;
        }

        $type = [];
        $score = 0;
        $maxscore = 0;
        $penalty = 0;

        if (method_exists($validation, 'get_valid_response')) {
            $type[] = 'score';
            $score = $validation->get_valid_response()->get_score();
        }

        if (method_exists($validation, 'get_max_score') && $validation->get_max_score() != '') {
            $type[] = 'maxscore';
            $maxscore = $validation->get_max_score();
        }
        if (method_exists($validation, 'get_penalty') && $validation->get_penalty() != '') {
            $type[] = 'penalty';
            $penalty = $validation->get_penalty();
        }

        if (sizeof($responseIdentifiers) > 1) {
            $responseProcessing = QtiResponseProcessingBuilder::buildResponseProcessingWithMultipleResponse($score, $maxscore, $penalty, $feedBackOptions, $type, $responseIdentifiers);
        } else {
            // if found distractor_rationale_response_level generate response processing with setoutcome value FEEDBACK
            if (!empty($feedBackOptions) && is_array($feedBackOptions) || in_array('maxscore', $type) || in_array('penalty', $type)) {
                $responseProcessing = QtiResponseProcessingBuilder::build($score, $maxscore, $penalty, $feedBackOptions, $type);
            } else {
                $responseProcessing = $this->buildResponseProcessing($validation, $isCaseSensitive);
            }
        }

        return [$responseDeclaration, $responseProcessing];
    }

    protected function buildResponseProcessing($validation, $isCaseSensitive = true)
    {
        // Guess question type
        $validationClazz = new \ReflectionClass($validation);
        $questionType = str_replace('_validation', '', $validationClazz->getShortName());

        if (in_array($questionType, Constants::$questionTypesWithMappingSupport)) {
            $responseProcessing = new ResponseProcessing();
            $responseProcessing->setTemplate(Constants::RESPONSE_PROCESSING_TEMPLATE_MAP_RESPONSE);
            return $responseProcessing;
        }

        if ((method_exists($validation, 'get_valid_response')) && $validation->get_valid_response()->get_score() != 1) {
            $validation->get_valid_response()->set_score(1);
            LogService::log('Only support mapping to `matchCorrect` template, thus validation score is changed to 1 and since mapped to QTI pre-defined `match_correct.xml` template');
        }

        // Warn and remove `alt_responses` because couldn't support responseDeclaration with multiple valid answers
        if ((method_exists($validation, 'get_alt_responses')) && !empty($validation->get_alt_responses())) {
            $validation->set_alt_responses([]);
            LogService::log('Does not support multiple validation responses for `responseDeclaration`, only use `valid_response`, ignoring `alt_responses`');
        }

        // Warn since we only support match_correct, can't support `$isCaseSensitive`
        if ($isCaseSensitive == false) {
            LogService::log('Only support mapping to `matchCorrect` template, thus case sensitivity is ignored');
        }

        $responseProcessing = new ResponseProcessing();
        $responseProcessing->setTemplate(Constants::RESPONSE_PROCESSING_TEMPLATE_MATCH_CORRECT);
        return $responseProcessing;
    }
}
