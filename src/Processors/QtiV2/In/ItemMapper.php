<?php

namespace LearnosityQti\Processors\QtiV2\In;

use \LearnosityQti\AppContainer;
use \LearnosityQti\Exceptions\MappingException;
use \LearnosityQti\Processors\QtiV2\In\Processings\MathsProcessing;
use \LearnosityQti\Processors\QtiV2\In\Processings\ProcessingInterface;
use \LearnosityQti\Processors\QtiV2\In\Processings\RubricsProcessing;
use \LearnosityQti\Services\LogService;
use \qtism\data\AssessmentItem;
use \qtism\data\content\ItemBody;
use \qtism\data\processing\ResponseProcessing;
use \qtism\data\storage\xml\XmlDocument;

class ItemMapper
{
    private $itemBuilderFactory;

    public function __construct(ItemBuilderFactory $itemBuilderFactory)
    {
        $this->itemBuilderFactory = $itemBuilderFactory;
    }

    public function parse($xmlString, $validate = true)
    {
        // TODO: Remove this, and move it higher up
        LogService::flush();

        $xmlString = $this->preprocessXml($xmlString);

        // Load the contents of the XML into a QTI-validated document
        $xmlDocument = new XmlDocument();
        if ($validate === false) {
            LogService::log('QTI pre-validation is turned off, some invalid attributes might be stripped from XML content upon conversion');
        }
        $xmlDocument->loadFromString($xmlString, $validate);

        /** @var AssessmentItem $assessmentItem */
        $assessmentItem = $xmlDocument->getDocumentComponent();
        if (!($assessmentItem instanceof AssessmentItem)) {
            throw new MappingException('XML is not a valid <assessmentItem> document');
        }
        return $this->parseWithAssessmentItemComponent($assessmentItem);
    }

    public function parseWithAssessmentItemComponent(AssessmentItem $assessmentItem)
    {
        // TODO: Move this logging service upper to converter class level
        // Make sure we clean up the log
        // LogService::flush();

        $processings = [
            AppContainer::getApplicationContainer()->get('rubrics_processing'),
            AppContainer::getApplicationContainer()->get('maths_processing'),
            AppContainer::getApplicationContainer()->get('assets_processing'),
            AppContainer::getApplicationContainer()->get('identifiers_processing')
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
        $interactionComponents = $itemBody->getComponentsByClassName(Constants::$supportedInteractions, true);
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

    private function preprocessXml($xmlString)
    {
        $xmlPreprocessings = [
            AppContainer::getApplicationContainer()->get('xml_assessment_items_processing'),
        ];

        /** @var AbstractXmlProcessing $processing */
        foreach ($xmlPreprocessings as $processing) {
            $xmlString = $processing->processXml($xmlString);
        }

        return $xmlString;
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
            return ResponseProcessingTemplate::unsupported();
        }
        if (!empty($responseProcessing->getTemplateLocation())) {
            LogService::log('Does not support \'templateLocation\' on <responseProcessing>. Ignoring <responseProcessing>');
            return ResponseProcessingTemplate::unsupported();
        }
        if (!empty($responseProcessing->getTemplate())) {
            return ResponseProcessingTemplate::getFromTemplateUrl($responseProcessing->getTemplate());
        }
        return ResponseProcessingTemplate::none();
    }
}
