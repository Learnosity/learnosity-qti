<?php

namespace Learnosity\Processors\QtiV2\In\Validation;

use Learnosity\Exceptions\MappingException;
use Learnosity\Processors\QtiV2\In\ResponseProcessingTemplate;
use Learnosity\Services\LogService;

abstract class BaseInteractionValidationBuilder
{
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
