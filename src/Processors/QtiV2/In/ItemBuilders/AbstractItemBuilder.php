<?php

namespace Learnosity\Processors\QtiV2\In\ItemBuilders;

use Learnosity\Entities\Item\item;
use Learnosity\Processors\QtiV2\In\ResponseProcessingTemplate;
use qtism\data\content\ItemBody;
use qtism\data\QtiComponentCollection;

abstract class AbstractItemBuilder
{
    protected $assessmentItemIdentifier;
    protected $questions = [];
    protected $content = '';

    public function getItem()
    {
        $item = new item($this->assessmentItemIdentifier, array_keys($this->questions), $this->content);
        $item->set_status('published');
        return $item;
    }

    public function getQuestions()
    {
        return array_values($this->questions);
    }

    protected function getMapperInstance($interactionType, $params)
    {
        $reflectionClass = new \ReflectionClass(static::MAPPER_CLASS_BASE.  ucfirst($interactionType . 'Mapper'));
        return $reflectionClass->newInstanceArgs($params);
    }

    abstract public function map(
        $assessmentItemIdentifier,
        ItemBody $itemBody,
        QtiComponentCollection $interactionComponents,
        QtiComponentCollection $responseDeclarations = null,
        ResponseProcessingTemplate $responseProcessingTemplate = null
    );
}
