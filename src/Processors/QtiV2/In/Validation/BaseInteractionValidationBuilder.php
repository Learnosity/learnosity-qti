<?php

namespace LearnosityQti\Processors\QtiV2\In\Validation;

use LearnosityQti\Exceptions\MappingException;
use LearnosityQti\Processors\QtiV2\In\ResponseProcessingTemplate;
use LearnosityQti\Services\LogService;
use qtism\data\state\ResponseDeclaration;

abstract class BaseInteractionValidationBuilder
{
    protected $responseDeclaration;

    public function __construct(ResponseDeclaration $responseDeclaration = null)
    {
        $this->responseDeclaration = $responseDeclaration;
    }

    protected function getMatchCorrectTemplateValidation()
    {
        LogService::log(
            'Does not support `match_correct` response processing template for this interaction. ' .
            'Fail mapping validation object'
        );
        return null;
    }

    protected function getMapResponseTemplateValidation()
    {
        LogService::log(
            'Does not support `map_response` response processing template for this interaction. ' .
            'Fail mapping validation object'
        );
        return null;
    }

    protected function getNoTemplateResponsesValidation()
    {
        LogService::log('No response processing detected');
        return null;
    }

    public function buildValidation(ResponseProcessingTemplate $responseProcessingTemplate)
    {
        try {
            switch ($responseProcessingTemplate->getTemplate()) {
                case ResponseProcessingTemplate::MATCH_CORRECT:
                    return $this->getMatchCorrectTemplateValidation();
                case ResponseProcessingTemplate::MAP_RESPONSE:
                case ResponseProcessingTemplate::CC2_MAP_RESPONSE:
                    return $this->getMapResponseTemplateValidation();
                case ResponseProcessingTemplate::NONE:
                    if (!empty($this->responseDeclaration)) {
                        // If the response processing template is not set, simply check whether `mapping` or `correctResponse` exists and
                        // simply use `em
                        if (!empty($this->responseDeclaration->getMapping()) && $this->responseDeclaration->getMapping()->getMapEntries()->count() > 0) {
                            LogService::log('Response processing is not set, the `validation` object is assumed to be mapped based on `mapping` map entries elements');
                            return $this->getMapResponseTemplateValidation();
                        }
                        if (!empty($this->responseDeclaration->getCorrectResponse()) && $this->responseDeclaration->getCorrectResponse()->getValues()->count() > 0) {
                            LogService::log('Response processing is not set, the `validation` object is assumed to be mapped based on `correctResponse` values elements');
                            return $this->getMatchCorrectTemplateValidation();
                        }
                    }
                    return $this->getNoTemplateResponsesValidation();
                default:
                    LogService::log('Unrecognised response processing template. Validation is not available');
            }
        } catch (MappingException $e) {
            LogService::log('Validation is not available. Critical error: ' . $e->getMessage());
        }
        return null;
    }
}
