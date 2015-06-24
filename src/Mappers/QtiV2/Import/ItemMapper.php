<?php

namespace Learnosity\Mappers\QtiV2\Import;

use Exception;
use Learnosity\Entities\Item\item;
use Learnosity\Entities\Question;
use Learnosity\Exceptions\MappingException;
use Learnosity\Mappers\QtiV2\Import\Interactions\AbstractInteraction;
use Learnosity\Mappers\QtiV2\Import\MergedInteractions\AbstractMergedInteraction;
use Learnosity\Mappers\QtiV2\Import\Utils\QtiComponentUtil;
use qtism\data\AssessmentItem;
use qtism\data\content\BlockCollection;
use qtism\data\content\interactions\Interaction;
use qtism\data\content\ItemBody;
use qtism\data\content\RubricBlock;
use qtism\data\processing\ResponseProcessing;
use qtism\data\state\ResponseDeclaration;
use qtism\data\storage\xml\XmlCompactDocument;

class ItemMapper
{
    /* @var $xmlDocument XmlCompactDocument */
    private $xmlDocument;
    /* @var $mapperFactory MapperFactory */
    private $mapperFactory;
    private $exceptions = [];
    private $supportedInteractions = ['inlineChoiceInteraction', 'choiceInteraction',
        'extendedTextInteraction', 'textEntryInteraction'];


    public function __construct(XmlCompactDocument $document, MapperFactory $mapperFactory)
    {
        $this->xmlDocument = $document;
        $this->exceptions = [];
        $this->mapperFactory = $mapperFactory;
    }

    public function getExceptions()
    {
        return $this->exceptions;
    }

    public function parse($xmlString)
    {
        $this->xmlDocument->loadFromString($xmlString);

        /* @var $assessmentItem AssessmentItem */
        $assessmentItem = $this->validateAssessmentItem($this->xmlDocument->getDocumentComponent());
        $responseProcessingTemplate = $this->getResponseProcessingTemplate($assessmentItem->getResponseProcessing());

        // Process <itemBody>
        $questions = [];
        $content = '';

        /** @var ItemBody $itemBody */
        $itemBody = $assessmentItem->getItemBody();
        $itemBody = $this->filterItemBody($itemBody);

        // Mapping interactions
        $interactionComponents = $itemBody->getComponentsByClassName($this->supportedInteractions, true);
        if (!$interactionComponents || count($interactionComponents) === 0) {
            $this->exceptions[] =
                new MappingException('No supported interactions could be found', MappingException::CRITICAL);
            return null;
        }

        // Decide whether we shall merge interaction
        $interactionTypes = array_unique(array_map(function ($component) {
            /* @var $component Interaction */
            return $component->getQtiClassName();
        }, $interactionComponents->getArrayCopy()));
        $possibleMergedInteractionTypes = ['textEntryInteraction', 'inlineChoiceInteraction'];

        if (count($interactionTypes) === 1 && in_array($interactionTypes[0], $possibleMergedInteractionTypes)) {
            $questionReference = $assessmentItem->getIdentifier();
            foreach ($interactionComponents as $component) {
                /* @var $component Interaction */
                $questionReference .= '_' . $component->getResponseIdentifier();
                /** @var ResponseDeclaration $responseDeclaration */
                // TODO: Need checking if merged exists, maybe again?
            }
            $responseDeclarations = $assessmentItem->getComponentsByClassName('responseDeclaration', true);
            try {
                /** @var AbstractMergedInteraction $mapper */
                $mapper = $this->mapperFactory->getMapper(
                    ucfirst($interactionTypes[0]),
                    [$questionReference, $itemBody, $responseDeclarations, $responseProcessingTemplate],
                    MapperFactory::MAPPER_TYPE_MERGED
                );

                $questionType = $mapper->getQuestionType();
                $questions[$questionReference] = $this->getQuestion($questionType, $questionReference);
                $content = $mapper->getItemContent();
                $this->exceptions = array_merge($this->exceptions, $mapper->getExceptions());
            } catch (MappingException $e) {
                $this->exceptions[] = $e;
                if ($e->getType() === MappingException::CRITICAL) {
                    throw $e;
                }
            }
        } else {
            // Do stuff normally
            $questionsSpan = [];

            foreach ($interactionComponents as $component) {
                try {
                    /* @var $component Interaction */
                    $questionReference = $assessmentItem->getIdentifier() . '_' . $component->getResponseIdentifier();

                    // Process <responseDeclaration>
                    /** @var ResponseDeclaration $responseDeclaration */
                    $responseDeclaration = $assessmentItem->getComponentByIdentifier($component->getResponseIdentifier());

                    $factory = $this->mapperFactory;
                    $mapper = $factory->getMapper(
                        ucfirst($component->getQtiClassName()),
                        [$component, $responseDeclaration, $responseProcessingTemplate],
                        MapperFactory::MAPPER_TYPE_STD
                    );
                    $questionType = $mapper->getQuestionType();

                    $questions[$questionReference] =  $this->getQuestion($questionType, $questionReference);
                    $this->exceptions = array_merge($this->exceptions, $mapper->getExceptions());
                    $interactionXml = QtiComponentUtil::marshall($component);
                    $questionsSpan[$questionReference] = $interactionXml;
                } catch (MappingException $e) {
                    $this->exceptions[] = $e;
                    if ($e->getType() === MappingException::CRITICAL) {
                        throw $e;
                    }
                }
            }

            // Build item's HTML content
            $content = QtiComponentUtil::marshallCollection($itemBody->getComponents());
            foreach ($questionsSpan as $questionReference => $interactionXml) {
                $questionSpan = '<span class="learnosity-response question-' . $questionReference . '"></span>';
                $content = str_replace($interactionXml, $questionSpan, $content);
            }
        }

        $item = new item($assessmentItem->getIdentifier(), array_keys($questions), $content);
        if ($assessmentItem->getTitle()) {
            $item->set_description($assessmentItem->getTitle());
        }
        $item->set_status('published');

        return [$item, $questions, $this->getExceptionMessages()];
    }

    protected function getQuestion($questionType, $questionReference) {
        return new Question($questionType->get_type(), $questionReference, $questionType);
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

    private function filterItemBody($itemBody)
    {
        $newCollection = new BlockCollection();
        $itemBodyNew = new ItemBody();

        // TODO: Yea, we ignore rubric but what happen if the rubric is deep inside nested
        $hasRubric = false;
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
}
