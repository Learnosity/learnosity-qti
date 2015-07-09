<?php

namespace Learnosity\Processors\QtiV2\In;

use Exception;
use Learnosity\AppContainer;
use Learnosity\Exceptions\MappingException;
use Learnosity\Processors\QtiV2\In\Processings\MathsProcessing;
use Learnosity\Processors\QtiV2\In\Processings\ProcessingInterface;
use Learnosity\Processors\QtiV2\In\Processings\RubricsProcessing;
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
        'graphicGapMatchInteraction',
        'graphicOrderInteraction',
        'hotspotInteraction'
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
            new MathsProcessing(),
            AppContainer::getApplicationContainer()->get('assets_processing')
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
        if ($responseProcessing === null) {
            return ResponseProcessingTemplate::none();
        }
        if ($responseProcessing->getResponseRules()->count()) {
            $this->exceptions[] = new MappingException('Does not support custom response processing on <responseProcessing>. Ignoring <responseProcessing>');
        }
        if (!empty($responseProcessing->getTemplateLocation())) {
            $this->exceptions[] = new MappingException('Does not support \'templateLocation\' on <responseProcessing>. Ignoring <responseProcessing>');
        }
        if (!empty($responseProcessing->getTemplate())) {
            return ResponseProcessingTemplate::getFromTemplateUrl($responseProcessing->getTemplate());
        }
        return ResponseProcessingTemplate::unsupported();
    }
}
