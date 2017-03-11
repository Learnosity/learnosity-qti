<?php

namespace LearnosityQti\Processors\QtiV2\In;

use \LearnosityQti\AppContainer;
use \LearnosityQti\Exceptions\MappingException;
use \LearnosityQti\Processors\QtiV2\In\Processings\AbstractXmlProcessing;
use \LearnosityQti\Processors\QtiV2\In\Processings\ProcessingInterface;
use \LearnosityQti\Processors\QtiV2\In\ResponseProcessingTemplate;
use \LearnosityQti\Services\LogService;
use \qtism\data\AssessmentItem;
use \qtism\data\content\ItemBody;
use \qtism\data\processing\ResponseProcessing;
use \qtism\data\storage\xml\XmlDocument;
use qtism\data\content\RubricBlock;
use qtism\data\content\BlockCollection;
use qtism\data\QtiComponentCollection;

class ItemMapper
{
    private $itemBuilderFactory;

    public function __construct(ItemBuilderFactory $itemBuilderFactory)
    {
        $this->itemBuilderFactory = $itemBuilderFactory;
    }

    /**
     * Parse an XML string representing a QTI-formatted assessment item
     * into Learnosity item and question data.
     *
     * This method attempts to deserialize the XML, and then calls
     * ItemMapper::parseWithAssessmentItemComponent() to perform the
     * actual parsing operation.
     *
     * The result of a successful parse operation is Learnosity item and
     * questions data that corresponds to the QTI-formatted input.
     *
     * @param  string  $xmlString - XML input to parse
     * @param  boolean $validateXml - whether to validate the XML
     *                              before attempting to deserialize it
     *
     * @return mixed[] - a tuple containing a Learnosity item as the
     *                   first element, associated questions as the second, and
     *                   log messages resulting from the operation as the last element
     */
    public function parse($xmlString, $validateXml = true, $sourceDirectoryPath = null, $metadata = [])
    {
        // TODO: Remove this, and move it higher up
        LogService::flush();

        $xmlString = $this->processXml($xmlString, $this->getXmlProcessings());

        // Load the contents of the XML into a QTI-validated document
        /** @var XmlDocument $xmlDocument */
        $xmlDocument = $this->deserializeXml($xmlString, $validateXml);

        /** @var AssessmentItem $assessmentItem */
        $assessmentItem = $this->getAssessmentItemFromXmlDocument($xmlDocument);

        // Convert the QTI assessment item into Learnosity output
        return $this->parseWithAssessmentItemComponent($assessmentItem, $sourceDirectoryPath, $metadata);
    }

    /**
     * Parse a QTI assessment item into Learnosity item and question data.
     *
     * @param  \qtism\data\AssessmentItem $assessmentItem - item component to parse
     *
     * @return mixed[] - a tuple containing a Learnosity item as the
     *                   first element, associated questions as the second, and
     *                   log messages resulting from the operation as the last element
     */
    public function parseWithAssessmentItemComponent(AssessmentItem $assessmentItem, $sourceDirectoryPath = null, $metadata = [])
    {
        // TODO: Move this logging service upper to converter class level
        // Make sure we clean up the log
        // LogService::flush();

        $processings = $this->getConversionProcessings();

        $assessmentItem = $this->processQtiAssessmentItem($assessmentItem, $processings);

        $assessmentItem = $this->validateQtiAssessmentItem($assessmentItem);

        // Conversion from QTI item to Learnosity item and questions
        // TODO: Handle additional (related) items being passed back
        list($item, $questions, $features, $rubric) = $this->buildLearnosityItemFromQtiAssessmentItem($assessmentItem, $sourceDirectoryPath, $metadata);

        // TODO: Check whether this needs to handle mapping questions to relevant items
        list($item, $questions) = $this->processLearnosityItem($item, $questions, $processings);

        // Flush out all the error messages stored in this static class, also ensure they are unique
        $messages = array_values(array_unique(LogService::flush()));

        // TODO: Support additional (related) items being passed back
        return [
            'item'      => $item,
            'rubric'    => $rubric,
            'questions' => $questions,
            'features'  => $features,
            'messages'  => $messages,
        ];
    }

    /**
     * Takes a QTI assessment item and generates corresponding
     * Learnosity item and question data.
     *
     * @param  \qtism\data\AssessmentItem $assessmentItem
     *
     * @return mixed[] - a tuple containing a Learnosity item as the
     *                   first element, and associated questions as the second
     *
     * @throws \LearnosityQti\Exceptions\MappingException
     */
    protected function buildLearnosityItemFromQtiAssessmentItem(AssessmentItem $assessmentItem, $sourceDirectoryPath = null, $metadata = [])
    {
        $responseProcessingTemplate = $this->getResponseProcessingTemplate($assessmentItem->getResponseProcessing());

        /** @var ItemBody $itemBody */
        $itemBody = $assessmentItem->getItemBody();

        list($itemBody, $rubricBlocks) = $this->processQtiItemBodyRubricBlocks($itemBody);

        // Mapping interactions
        $interactionComponents = $itemBody->getComponentsByClassName(Constants::$supportedInteractions, true);
        if (!$interactionComponents || count($interactionComponents) === 0) {
            throw new MappingException('No supported interaction mapper could be found');
        }
        $responseDeclarations = $assessmentItem->getComponentsByClassName('responseDeclaration', true);
        $itemBuilder = $this->itemBuilderFactory->getItemBuilder($assessmentItem);
        $itemBuilder->setSourceDirectoryPath($sourceDirectoryPath);
        if (isset($metadata['point_value'])) {
            $itemBuilder->setItemPointValue($metadata['point_value']);
        }

        $itemBuilder->map(
            $assessmentItem->getIdentifier(),
            $itemBody,
            $interactionComponents,
            $responseDeclarations,
            $responseProcessingTemplate,
            $rubricBlocks
        );

        $item = $itemBuilder->getItem();
        if ($assessmentItem->getTitle()) {
            $item->set_description($assessmentItem->getTitle());
        }
        // Handle additional (related) items being passed back
        $rubric = $itemBuilder->getRubricItem();

        $questions = $itemBuilder->getQuestions();
        $features = $itemBuilder->getFeatures();

        // Support additional (related) items being passed back
        return [$item, $questions, $features, $rubric];
    }

    /**
     * Deserialize an XML string into a QTI-validated document.
     *
     * @param  string $xmlString - the XML content to deserialize
     * @param  boolean $validateXml - whether to validate the XML
     *                              before attempting to deserialize it
     *
     * @return \qtism\data\storage\xml\XmlDocument
     */
    protected function deserializeXml($xmlString, $validateXml)
    {
        $xmlDocument = new XmlDocument();
        if (!$validateXml) {
            LogService::log('QTI pre-validation is turned off, some invalid attributes might be stripped from XML content upon conversion');
        }
        $xmlDocument->loadFromString($xmlString, $validateXml);

        return $xmlDocument;
    }

    /**
     * Returns a list of objects that are used for processing the XML.
     *
     * @return AbstractXmlProcessing[]
     */
    protected function getXmlProcessings()
    {
        return [
            AppContainer::getApplicationContainer()->get('xml_assessment_items_processing'),
        ];
    }

    /**
     * Returns a list of objects that are used for performing
     * pre- and post-processing on converted assessment items.
     *
     * @return ProcessingInterface[]
     */
    protected function getConversionProcessings()
    {
        return [
            // AppContainer::getApplicationContainer()->get('rubrics_processing'),
            AppContainer::getApplicationContainer()->get('maths_processing'),
            AppContainer::getApplicationContainer()->get('assets_processing'),
            AppContainer::getApplicationContainer()->get('identifiers_processing'),
        ];
    }

    /**
     * Process a Learnosity item following a successful QTI-to-learnosity conversion.
     *
     * @param  mixed $item - item to process
     * @param  mixed $questions - questions to process
     * @param  ProcessingInterface[] $processings - list of processing tasks
     *
     * @return mixed[] - a tuple containing a Learnosity item as the
     *                   first element, and associated questions as the second
     */
    protected function processLearnosityItem($item, $questions, array $processings)
    {
        /** @var ProcessingInterface $processing */
        foreach ($processings as $processing) {
            list($item, $questions) = $processing->processItemAndQuestions($item, $questions);
        }

        return [$item, $questions];
    }

    /**
     * Process a QTI assessment item prior to conversion.
     *
     * @param  \qtism\data\AssessmentItem $assessmentItem - item component to process
     * @param  ProcessingInterface[] $processings - list of processing tasks
     *
     * @return \qtism\data\AssessmentItem
     */
    protected function processQtiAssessmentItem(AssessmentItem $assessmentItem, array $processings)
    {
        /** @var ProcessingInterface $processing */
        foreach ($processings as $processing) {
            $assessmentItem = $processing->processAssessmentItem($assessmentItem);
        }

        return $assessmentItem;
    }

    protected function processQtiItemBodyRubricBlocks(ItemBody $itemBody)
    {
        // TODO: what happens if the rubric is deep inside nested?
        $rubricBlocks = new QtiComponentCollection();
        $newCollection = new BlockCollection();
        $itemBodyNew = new ItemBody();

        // Iterate over components; extract and process any rubricBlock
        /** @var QtiComponent $component */
        foreach ($itemBody->getContent() as $key => $component) {
            // Separate the <rubricBlock> elements from the item body
            if ($component instanceof RubricBlock) {
                $rubricBlocks->attach($component);
            } else {
                $newCollection->attach($component);
            }
        }
        $itemBodyNew->setContent($newCollection);

        return [$itemBodyNew, $rubricBlocks];
    }

    /**
     * Process an XML string prior to deserialization.
     *
     * @param  string $xmlString - XML content to process
     * @param  AbstractXmlProcessing[] $processings - list of processing tasks
     *
     * @return string
     */
    protected function processXml($xmlString, array $processings)
    {
        /** @var AbstractXmlProcessing $processing */
        foreach ($processings as $processing) {
            $xmlString = $processing->processXml($xmlString);
        }

        return $xmlString;
    }

    /**
     * Validates a QTI assessment item for conversion.
     *
     * @param  \qtism\data\AssessmentItem $assessmentItem - item component to validate
     *
     * @return \qtism\data\AssessmentItem
     *
     * @throws \LearnosityQti\Exceptions\MappingException
     */
    private function validateQtiAssessmentItem(AssessmentItem $assessmentItem)
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

    /**
     * Retrieves the assessment item from a given XML document.
     *
     * The assessment item must be the root element of the document.
     *
     * @param  \qtism\data\storage\xml\XmlDocument $xmlDocument
     *
     * @return \qtism\data\AssessmentItem
     *
     * @throws \LearnosityQti\Exceptions\MappingException
     */
    private function getAssessmentItemFromXmlDocument(XmlDocument $xmlDocument)
    {
        $assessmentItem = $xmlDocument->getDocumentComponent();
        if (!($assessmentItem instanceof AssessmentItem)) {
            throw new MappingException('XML is not a valid <assessmentItem> document');
        }

        return $assessmentItem;
    }

    /**
     * Obtains the corresponding Learnosity "response processing template"
     * for a given QTI ResponseProcessing component.
     *
     * @param  \qtism\data\processing\ResponseProcessing $responseProcessing
     *
     * @return \LearnosityQti\Processors\QtiV2\In\ResponseProcessingTemplate
     */
    private function getResponseProcessingTemplate(ResponseProcessing $responseProcessing = null)
    {
        if ($responseProcessing === null) {
            return ResponseProcessingTemplate::none();
        }
        if ($responseProcessing->getResponseRules()->count()) {
            LogService::log('Use builtin responseProcessing');
            return ResponseProcessingTemplate::builtin($responseProcessing);
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
