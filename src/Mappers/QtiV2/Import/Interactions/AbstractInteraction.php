<?php

namespace Learnosity\Mappers\QtiV2\Import\Interactions;

use Learnosity\Mappers\QtiV2\Import\ResponseProcessingTemplate;
use qtism\data\content\interactions\Interaction;
use qtism\data\state\ResponseDeclaration;

abstract class AbstractInteraction
{
    protected $interaction;
    protected $responseDeclaration;
    protected $responseProcessingTemplate;
    protected $exceptions;

    public function __construct(Interaction $interaction, ResponseDeclaration $responseDeclaration = null, ResponseProcessingTemplate $responseProcessingTemplate = null)
    {
        $this->interaction = $interaction;
        $this->responseDeclaration = $responseDeclaration;
        $this->responseProcessingTemplate = $responseProcessingTemplate;
    }

    public static function getDocumentation() {
        return null;
    }

    // TODO: Need to verify for <math> tags to see whether we need to enable 'is_math'
    abstract public function getQuestionType();

    public function getExceptions()
    {
        return $this->exceptions;
    }
}
