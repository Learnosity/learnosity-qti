<?php

namespace Learnosity\Mappers\QtiV2\Import;

use Exception;
use Learnosity\Exceptions\MappingException;
use Learnosity\Mappers\QtiV2\Import\Processings\MathsProcessing;
use Learnosity\Mappers\QtiV2\Import\Processings\ProcessingInterface;
use Learnosity\Mappers\QtiV2\Import\Processings\RubricsProcessing;
use qtism\data\AssessmentItem;
use qtism\data\content\ItemBody;
use qtism\data\processing\ResponseProcessing;
use qtism\data\storage\xml\XmlCompactDocument;

class ItemMapper
{
    private $exceptions = [];
    private $supportedInteractions = [
        'inlineChoiceInteraction',
        'choiceInteraction',
        'extendedTextInteraction',
        'textEntryInteraction',
        'matchInteraction',
        'hottextInteraction',
        'gapMatchInteraction',
        'orderInteraction',
        'graphicGapMatchInteraction'
    ];
    private $itemBuilderFactory;

    public function __construct(ItemBuilderFactory $itemBuilderFactory)
    {
        $this->itemBuilderFactory = $itemBuilderFactory;
    }

    public function getExceptions()
    {
        return $this->exceptions;
    }

    public function parse($xmlString)
    {
        $xmlDocument = new XmlCompactDocument();
        $xmlDocument->loadFromString($xmlString);

        /** @var AssessmentItem $assessmentItem */
        $assessmentItem = $xmlDocument->getDocumentComponent();
        if (!($assessmentItem instanceof AssessmentItem)) {
            throw new MappingException('XML is not a valid <assessmentItem> document', MappingException::CRITICAL);
        }
        return $this->parseWithAssessmentItemComponent($assessmentItem);
    }

    public function parseWithAssessmentItemComponent(AssessmentItem $assessmentItem)
    {
        $processings = [
            new RubricsProcessing(),
            new MathsProcessing()
        ];
        // Pre-processing works
        /** @var ProcessingInterface $processing */
        foreach ($processings as $processing) {
            $assessmentItem = $processing->processAssessmentItem($assessmentItem);
        }

        $assessmentItem = $this->validateAssessmentItem($assessmentItem);
        $responseProcessingTemplate = $this->getResponseProcessingTemplate($assessmentItem->getResponseProcessing());

        /** @var ItemBody $itemBody */
        $itemBody = $assessmentItem->getItemBody();

        // Mapping interactions
        $interactionComponents = $itemBody->getComponentsByClassName($this->supportedInteractions, true);
        if (!$interactionComponents || count($interactionComponents) === 0) {
            $this->exceptions[] =
                new MappingException('No supported interactions could be found', MappingException::CRITICAL);
            return [[], [], $this->getExceptionMessages()];
        }
        $responseDeclarations = $assessmentItem->getComponentsByClassName('responseDeclaration', true);

        $itemBuilder = $this->itemBuilderFactory->getItemBuilder($interactionComponents);
        $itemBuilder->map(
            $assessmentItem->getIdentifier(),
            $itemBody,
            $interactionComponents,
            $responseDeclarations,
            $responseProcessingTemplate
        );

        $item = $itemBuilder->getItem();
        if ($assessmentItem->getTitle()) {
            $item->set_description($assessmentItem->getTitle());
        }
        $questions = $itemBuilder->getQuestions();
        $this->exceptions = array_merge($this->exceptions, $itemBuilder->getExceptions());

        // Post-processing works
        /** @var ProcessingInterface $processing */
        foreach ($processings as $processing) {
            list($item, $questions) = $processing->processItemAndQuestions($item, $questions);
            $this->exceptions = array_merge($this->exceptions, $processing->getExceptions());
        }
        return [$item, $questions, $this->getExceptionMessages()];
    }

    private function getExceptionMessages()
    {
        $result = [];
        /** @var Exception $exception */
        foreach ($this->exceptions as $exception) {
            $result[] = $exception->getMessage();
        }
        return $result;
    }

    private function validateAssessmentItem(AssessmentItem $assessmentItem)
    {
        if ($assessmentItem->getOutcomeDeclarations()->count()) {
            $this->exceptions[] = new MappingException('Ignoring <outcomeDeclaration> on <assessmentItem>. Generally we mapped <defaultValue> to 0');
        }
        if ($assessmentItem->getTemplateDeclarations()->count()) {
            throw new MappingException('Does not support <templateDeclaration> on <assessmentItem>. Ignoring <templateDeclaration>', MappingException::CRITICAL);
        }
        if (!empty($assessmentItem->getTemplateProcessing())) {
            throw new MappingException('Does not support <templateProcessing> on <assessmentItem>. Ignoring <templateProcessing>', MappingException::CRITICAL);
        }
        if ($assessmentItem->getModalFeedbacks()->count()) {
            $this->exceptions[] = new MappingException('Ignoring <modalFeedback> on <assessmentItem>');
        }
        if ($assessmentItem->getStylesheets()->count()) {
            $this->exceptions[] = new MappingException('Ignoring <stylesheet> on <assessmentItem>');
        }
        return $assessmentItem;
    }

    private function getResponseProcessingTemplate(ResponseProcessing $responseProcessing = null)
    {
        if (!empty($responseProcessing)) {
            if ($responseProcessing->getResponseRules()->count()) {
                $this->exceptions[] = new MappingException('Does not support custom response processing on <responseProcessing>. Ignoring <responseProcessing>');
            }
            if (!empty($responseProcessing->getTemplateLocation())) {
                $this->exceptions[] = new MappingException('Does not support \'templateLocation\' on <responseProcessing>. Ignoring <responseProcessing>');
            }
            if (!empty($responseProcessing->getTemplate())) {
                $responseProcessingTemplate = ResponseProcessingTemplate::getFromTemplateUrl($responseProcessing->getTemplate());
                if (empty($responseProcessingTemplate)) {
                    $this->exceptions[] = new MappingException('Does not support custom response processing templates. Ignoring <responseProcessing>');
                }
                return $responseProcessingTemplate;
            }
        }
        return null;
    }
}
