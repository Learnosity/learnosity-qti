<?php

namespace Learnosity\Processors\QtiV2\In\Interactions;

use Learnosity\Processors\QtiV2\In\ResponseProcessingTemplate;
use Learnosity\Processors\QtiV2\In\Utils\QtiComponentUtil;
use qtism\data\content\interactions\Interaction;
use qtism\data\content\interactions\Prompt;
use qtism\data\state\ResponseDeclaration;

abstract class AbstractInteractionMapper
{
    protected $interaction;
    protected $responseDeclaration;
    protected $responseProcessingTemplate;
    protected $exceptions;

    public function __construct(Interaction $interaction, ResponseDeclaration $responseDeclaration = null, ResponseProcessingTemplate $responseProcessingTemplate = null)
    {
        $this->interaction = $interaction;
        $this->responseDeclaration = $responseDeclaration;
        $this->responseProcessingTemplate = empty($responseProcessingTemplate) ? ResponseProcessingTemplate::none() : $responseProcessingTemplate;
        $this->exceptions = [];
    }

    abstract public function getQuestionType();

    public function getExceptions()
    {
        return $this->exceptions;
    }

    public function getPrompt()
    {
        if ($this->interaction->getPrompt() instanceof Prompt) {
            $promptContent = $this->interaction->getPrompt()->getContent();
            return QtiComponentUtil::marshallCollection($promptContent);
        }
        return '';
    }
}
