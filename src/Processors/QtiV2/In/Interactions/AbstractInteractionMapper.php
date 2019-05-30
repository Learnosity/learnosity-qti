<?php

namespace LearnosityQti\Processors\QtiV2\In\Interactions;

use \LearnosityQti\Processors\QtiV2\In\ResponseProcessingTemplate;
use \LearnosityQti\Utils\QtiMarshallerUtil;
use \qtism\data\content\interactions\Interaction;
use \qtism\data\content\interactions\Prompt;
use \qtism\data\state\ResponseDeclaration;
use \qtism\data\state\OutcomeDeclarationCollection;

abstract class AbstractInteractionMapper
{
    protected $interaction;
    protected $responseDeclaration;
    protected $responseProcessingTemplate;
    protected $outcomeDeclarations;
    protected $organisationId;

    public function __construct(
        Interaction $interaction,
        ResponseDeclaration $responseDeclaration = null,
        ResponseProcessingTemplate $responseProcessingTemplate = null,
        OutcomeDeclarationCollection $outcomeDeclarations = null,
        $organisationId = ''
    ) {
        $this->interaction = $interaction;
        $this->responseDeclaration = $responseDeclaration;

        if (empty($responseDeclaration)) {
            $responseProcessingTemplate = ResponseProcessingTemplate::none();
        }
        $this->responseProcessingTemplate = $responseProcessingTemplate;

        $this->outcomeDeclarations = $outcomeDeclarations;
        $this->organisationId = $organisationId;
    }

    abstract public function getQuestionType();

    public function getPrompt()
    {
        if ($this->interaction->getPrompt() instanceof Prompt) {
            $promptContent = $this->interaction->getPrompt()->getContent();
            return QtiMarshallerUtil::marshallCollection($promptContent);
        }
        return '';
    }
}
