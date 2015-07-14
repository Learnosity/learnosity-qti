<?php

namespace Learnosity\Processors\QtiV2\In;

use Learnosity\AppContainer;
use Learnosity\Exceptions\MappingException;
use Learnosity\Processors\QtiV2\In\Processings\MathsProcessing;
use Learnosity\Processors\QtiV2\In\Processings\ProcessingInterface;
use Learnosity\Processors\QtiV2\In\Processings\RubricsProcessing;
use Learnosity\Services\LogService;
use qtism\data\AssessmentItem;
use qtism\data\content\ItemBody;
use qtism\data\processing\ResponseProcessing;
use qtism\data\storage\xml\XmlCompactDocument;

class ItemMapper
{
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
        // Make sure we clean up the log
        LogService::flush();

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
            throw new MappingException('No supported interaction mapper could be found');
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

        // Post-processing works
        /** @var ProcessingInterface $processing */
        foreach ($processings as $processing) {
            list($item, $questions) = $processing->processItemAndQuestions($item, $questions);
        }

        // Flush out all the error messages stored in this static class, also ensure they are unique
        $messages = array_values(array_unique(LogService::flush()));
        return [$item, $questions, $messages];
    }

    private function validateAssessmentItem(AssessmentItem $assessmentItem)
    {
        if ($assessmentItem->getOutcomeDeclarations()->count()) {
            LogService::log('Ignoring <outcomeDeclaration> on <assessmentItem>. Generally we mapped <defaultValue> to 0');
        }
        if ($assessmentItem->getTemplateDeclarations()->count()) {
            throw new MappingException('Does not support <templateDeclaration> on <assessmentItem>. Ignoring <templateDeclaration>');
        }
        if (!empty($assessmentItem->getTemplateProcessing())) {
            throw new MappingException('Does not support <templateProcessing> on <assessmentItem>. Ignoring <templateProcessing>');
        }
        if ($assessmentItem->getModalFeedbacks()->count()) {
            LogService::log('Ignoring <modalFeedback> on <assessmentItem>');
        }
        if ($assessmentItem->getStylesheets()->count()) {
            LogService::log('Ignoring <stylesheet> on <assessmentItem>');
        }
        return $assessmentItem;
    }

    private function getResponseProcessingTemplate(ResponseProcessing $responseProcessing = null)
    {
        if ($responseProcessing === null) {
            return ResponseProcessingTemplate::none();
        }
        if ($responseProcessing->getResponseRules()->count()) {
            LogService::log('Does not support custom response processing on <responseProcessing>. Ignoring <responseProcessing>');
        }
        if (!empty($responseProcessing->getTemplateLocation())) {
            LogService::log('Does not support \'templateLocation\' on <responseProcessing>. Ignoring <responseProcessing>');
        }
        if (!empty($responseProcessing->getTemplate())) {
            return ResponseProcessingTemplate::getFromTemplateUrl($responseProcessing->getTemplate());
        }
        return ResponseProcessingTemplate::unsupported();
    }
}
