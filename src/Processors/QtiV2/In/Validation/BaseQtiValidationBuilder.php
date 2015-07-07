<?php
namespace Learnosity\Processors\QtiV2\In\Validation;

use Learnosity\Exceptions\MappingException;
use Learnosity\Processors\Learnosity\In\ValidationBuilder;
use Learnosity\Processors\QtiV2\In\ResponseProcessingTemplate;

abstract class BaseQtiValidationBuilder
{
    protected $exceptions;
    protected $responseProcessingTemplate;
    protected $responseDeclarations;
    protected $validationClassName;
    protected $originalResponseData;
    protected $scoringType;

    abstract protected function handleMatchCorrectTemplate();

    abstract protected function handleMapResponseTemplate();

    abstract protected function handleCC2MapResponseTemplate();

    abstract protected function prepareOriginalResponseData();

    public function __construct(
        ResponseProcessingTemplate $responseProcessingTemplate,
        array $responseDeclarations,
        $validationClassName
    )
    {
        $this->responseProcessingTemplate = $responseProcessingTemplate;
        $this->responseDeclarations = $responseDeclarations;
        $this->validationClassName = $validationClassName;
        $this->originalResponseData = [];
        $this->exceptions = [];
    }

    protected function handleUnknownTemplate()
    {
        $this->exceptions[] =
            new MappingException('Unrecognised response processing template. Validation is not available');
    }

    public function buildValidation()
    {
        switch ($this->responseProcessingTemplate->getTemplate()) {
            case ResponseProcessingTemplate::MATCH_CORRECT:
                $this->handleMatchCorrectTemplate();
                break;
            case ResponseProcessingTemplate::CC2_MAP_RESPONSE:
                $this->handleMapResponseTemplate();
                break;
            case ResponseProcessingTemplate::MAP_RESPONSE:
                $this->handleMapResponseTemplate();
                break;
            default:
                $this->handleUnknownTemplate();
        }

        if (empty($this->originalResponseData)) {
            return null;
        }

        $this->prepareOriginalResponseData();

        $validationBuilder = new ValidationBuilder($this->scoringType, $this->originalResponseData);

        return $validationBuilder->buildValidation($this->validationClassName);
    }

    public function getExceptions()
    {
        return $this->exceptions;
    }
}
