<?php

namespace Learnosity\Mappers\QtiV2\Import\Interactions;

use Learnosity\Mappers\QtiV2\Import\ResponseProcessingTemplate;
use Learnosity\Mappers\QtiV2\Import\Utils\QtiComponentUtil;
use qtism\data\content\interactions\Interaction;
use qtism\data\content\interactions\Prompt;
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
        $this->exceptions = [];
    }

    // TODO: Need to verify for <math> tags to see whether we need to enable 'is_math'
    abstract public function getQuestionType();

    public function getExceptions()
    {
        return $this->exceptions;
    }

    public function getPrompt() {
        if ($this->interaction->getPrompt() instanceof Prompt) {
            $promptContent = $this->interaction->getPrompt()->getContent();
            return QtiComponentUtil::marshallCollection($promptContent);
        }
        return '';
    }
}
