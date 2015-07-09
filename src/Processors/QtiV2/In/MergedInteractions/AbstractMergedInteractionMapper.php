<?php

namespace Learnosity\Processors\QtiV2\In\MergedInteractions;

use Learnosity\Processors\QtiV2\In\ResponseProcessingTemplate;
use qtism\data\content\ItemBody;
use qtism\data\QtiComponentCollection;

abstract class AbstractMergedInteractionMapper
{
    protected $questionReference;
    protected $itemBody;
    protected $responseDeclarations = [];
    protected $responseProcessingTemplate;

    protected $exceptions = [];

    public function __construct(
        $questionReference,
        ItemBody $itemBody,
        QtiComponentCollection $responseDeclarations = null,
        ResponseProcessingTemplate $responseProcessingTemplate = null
    ) {
        $this->questionReference = $questionReference;
        $this->itemBody = $itemBody;
        $this->responseProcessingTemplate = empty($responseProcessingTemplate) ? ResponseProcessingTemplate::none() : $responseProcessingTemplate;
        $this->exceptions = [];

        if (!empty($responseDeclarations)) {
            foreach ($responseDeclarations as $responseDeclaration) {
                $this->responseDeclarations[$responseDeclaration->getIdentifier()] = $responseDeclaration;
            }
        }
    }

    abstract public function getQuestionType();

    abstract public function getItemContent();

    public function getExceptions()
    {
        return $this->exceptions;
    }
}
