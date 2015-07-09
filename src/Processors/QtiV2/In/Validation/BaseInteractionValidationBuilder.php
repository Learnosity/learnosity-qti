<?php

namespace Learnosity\Processors\QtiV2\In\Validation;

use Learnosity\Exceptions\MappingException;
use Learnosity\Processors\QtiV2\In\ResponseProcessingTemplate;

abstract class BaseInteractionValidationBuilder
{
    protected $exceptions = [];

    protected function getMatchCorrectTemplateValidation()
    {
        $this->exceptions[] =
            new MappingException('Does not support `match_correct` response processing template for this interaction.
                Fail mapping validation object');
        return null;
    }

    protected function getMapResponseTemplateValidation()
    {
        $this->exceptions[] =
            new MappingException('Does not support `map_response` response processing template for this interaction.
                Fail mapping validation object');
        return null;
    }

    protected function getNoTemplateResponsesValidation()
    {
        $this->exceptions[] =
            new MappingException('No response processing detected');
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
                    $this->exceptions[] = new MappingException('Unrecognised response processing template.
                    Validation is not available');
            }
        } catch (MappingException $e) {
            $this->exceptions[] = $e;
        }
        return null;
    }

    public function getExceptions()
    {
        return $this->exceptions;
    }
}
