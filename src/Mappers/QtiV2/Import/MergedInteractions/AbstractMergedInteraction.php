<?php

namespace Learnosity\Mappers\QtiV2\Import\MergedInteractions;

use Learnosity\Mappers\QtiV2\Import\ResponseProcessingTemplate;
use qtism\data\content\ItemBody;
use qtism\data\QtiComponentCollection;

abstract class AbstractMergedInteraction
{
    protected $questionReference;
    protected $itemBody;
    protected $responseDeclarations;
    protected $responseProcessingTemplate;

    protected $exceptions;

    public function __construct($questionReference,
        ItemBody $itemBody,
        QtiComponentCollection $responseDeclarations,
        ResponseProcessingTemplate $responseProcessingTemplate = null
    )
    {
        $this->questionReference = $questionReference;
        $this->itemBody = $itemBody;
        $this->responseProcessingTemplate = $responseProcessingTemplate;

        foreach ($responseDeclarations as $responseDeclaration) {
            $this->responseDeclarations[$responseDeclaration->getIdentifier()] = $responseDeclaration;
        }
    }

    // TODO: Need to verify for <math> tags to see whether we need to enable 'is_math'
    abstract public function getQuestionType();

    abstract public function getItemContent();

    public function getExceptions()
    {
        return $this->exceptions;
    }
}