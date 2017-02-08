<?php

namespace LearnosityQti\Processors\QtiV2\In\MergedInteractions;

use \LearnosityQti\Processors\QtiV2\In\ResponseProcessingTemplate;
use \qtism\data\content\ItemBody;
use \qtism\data\QtiComponentCollection;
use \qtism\data\state\OutcomeDeclarationCollection;

abstract class AbstractMergedInteractionMapper
{
    protected $questionReference;
    protected $itemBody;
    protected $responseDeclarations = [];
    protected $responseProcessingTemplate;
    protected $outcomeDeclarations;

    public function __construct(
        $questionReference,
        ItemBody $itemBody,
        QtiComponentCollection $responseDeclarations = null,
        ResponseProcessingTemplate $responseProcessingTemplate = null,
        OutcomeDeclarationCollection $outcomeDeclarations = null
    ) {
        $this->questionReference = $questionReference;
        $this->itemBody = $itemBody;
        $this->responseProcessingTemplate = empty($responseProcessingTemplate) ? ResponseProcessingTemplate::none() : $responseProcessingTemplate;
        if (!empty($responseDeclarations)) {
            foreach ($responseDeclarations as $responseDeclaration) {
                $this->responseDeclarations[$responseDeclaration->getIdentifier()] = $responseDeclaration;
            }
        }

        $this->outcomeDeclarations = $outcomeDeclarations;
    }

    abstract public function getQuestionType();

    abstract public function getItemContent();
}
