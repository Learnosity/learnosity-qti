<?php

namespace Learnosity\Mappers\QtiV2\Import;

use Exception;
use Learnosity\Entities\BaseQuestionType;
use Learnosity\Entities\Question;
use Learnosity\Exceptions\MappingException;
use qtism\data\AssessmentItem;
use qtism\data\content\BlockCollection;
use qtism\data\content\ItemBody;
use qtism\data\content\Math;
use qtism\data\content\RubricBlock;
use qtism\data\processing\ResponseProcessing;
use qtism\data\QtiComponent;
use qtism\data\storage\xml\XmlCompactDocument;

class ItemMapper
{
    private $exceptions = [];
    private $supportedInteractions = [
        'inlineChoiceInteraction',
        'choiceInteraction',
        'extendedTextInteraction',
        'textEntryInteraction'
    ];
    private $itemBuilderFactory;
    private $hasMathML = false;

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
        $assessmentItem = $this->validateAssessmentItem($assessmentItem);
        $responseProcessingTemplate = $this->getResponseProcessingTemplate($assessmentItem->getResponseProcessing());

        /** @var ItemBody $itemBody */
        $itemBody = $assessmentItem->getItemBody();
        $itemBody = $this->filterItemBody($itemBody);

        // Mapping interactions
        $interactionComponents = $itemBody->getComponentsByClassName($this->supportedInteractions, true);
        if (!$interactionComponents || count($interactionComponents) === 0) {
            throw new MappingException('No supported interactions could be found', MappingException::CRITICAL);
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
        $this->exceptions = array_merge($this->exceptions, $itemBuilder->getExceptions());
        if ($assessmentItem->getTitle()) {
            $item->set_description($assessmentItem->getTitle());
        }

        // Add `is_math` to questions if needed
        $questions = $itemBuilder->getQuestions();
        if ($this->hasMathML) {
            /** @var Question $question */
            foreach ($questions as &$question) {
                /** @var BaseQuestionType $questionType */
                $questionType = $question->get_data();
                if (method_exists($questionType, 'set_is_math')) {
                    $questionType->set_is_math(true);
                }
            }
        }
        return [$item, $questions, $this->getExceptionMessages()];
    }

    private function filterItemBody(ItemBody $itemBody)
    {
        // TODO: Tidy up, yea remove those mathML stuffs
        foreach ($itemBody->getIterator() as $component) {
            if ($component instanceof Math) {
                $element = $component->getXml()->documentElement;
                $element->removeAttributeNS($element->namespaceURI, $element->prefix);
                $component->setXmlString($element->ownerDocument->saveHTML());
                $component->setTargetNamespace('');
                $this->hasMathML = true;
            }
        }

        // TODO: Yea, we ignore rubric but what happen if the rubric is deep inside nested
        $newCollection = new BlockCollection();
        $itemBodyNew = new ItemBody();

        $hasRubric = false;
        /** @var QtiComponent $component */
        foreach ($itemBody->getContent() as $key => $component) {
            if (!($component instanceof RubricBlock)) {
                $newCollection->attach($component);
            } else {
                $hasRubric = true;
            }
        }
        if ($hasRubric) {
            $this->exceptions[] = new MappingException('Does not support <rubricBlock>. Ignoring <rubricBlock>');
        }
        $itemBodyNew->setContent($newCollection);
        return $itemBodyNew;
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
