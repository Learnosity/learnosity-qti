<?php

namespace Learnosity\Mappers\QtiV2\Import;

use Exception;
use Learnosity\Entities\Item;
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
    private $exceptions = [];
    private $supportedInteractions = ['inlineChoiceInteraction', 'choiceInteraction',
        'extendedTextInteraction', 'textEntryInteraction'];

    public function parse($xmlString)
    {
        $this->exceptions = [];

        $document = new XmlCompactDocument();
        $document->loadFromString($xmlString);

        /* @var $assessmentItem AssessmentItem */
        $assessmentItem = $this->validateAssessmentItem($document->getDocumentComponent());
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
            throw new MappingException('No supported interactions could be found', MappingException::CRITICAL);
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
            try {
                $mapperClass = 'Learnosity\Mappers\QtiV2\Import\MergedInteractions\\Merged' . ucfirst($interactionTypes[0]);
                $responseDeclarations = $assessmentItem->getComponentsByClassName('responseDeclaration', true);

                /** @var AbstractMergedInteraction $parser */
                $parser = new $mapperClass($questionReference, $itemBody, $responseDeclarations, $responseProcessingTemplate);
                $questionType = $parser->getQuestionType();
                $content = $parser->getItemContent();

                $questions[$questionReference] = new Question($questionType->get_type(), $questionReference, $questionType);
                $this->exceptions = array_merge($this->exceptions, $parser->getExceptions());
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
                    $mapperClass = 'Learnosity\Mappers\QtiV2\Import\Interactions\\' . ucfirst($component->getQtiClassName());

                    /** @var AbstractInteraction $parser */
                    $parser = new $mapperClass($component, $responseDeclaration, $responseProcessingTemplate);
                    $questionType = $parser->getQuestionType();
                    $questions[$questionReference] = new Question($questionType->get_type(), $questionReference, $questionType);
                    $this->exceptions = array_merge($this->exceptions, $parser->getExceptions());

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


        $item = new Item($assessmentItem->getIdentifier(), array_keys($questions), $content);
        if ($assessmentItem->getTitle()) {
            $item->set_description($assessmentItem->getTitle());
        }
        $item->set_status('published');

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

    private function buildLearnosityQuestion(
        $questionReference,
        Interaction $component,
        ResponseDeclaration $responseDeclaration = null,
        ResponseProcessingTemplate $responseProcessingTemplate = null
    )
    {
        $mapperClass = 'Learnosity\Mappers\QtiV2\Import\Interactions\\' . ucfirst($component->getQtiClassName());

        /** @var AbstractInteraction $parser */
        $parser = new $mapperClass($component, $responseDeclaration, $responseProcessingTemplate);
        $questionType = $parser->getQuestionType();

        return new Question($questionType->get_type(), $questionReference, $questionType);
    }

    /**
     * Filter all components with non-supported classes
     * @param $itemBody
     * @return array
     */
    private function filterItemBody($itemBody)
    {
        $newCollection = new BlockCollection();
        $itemBodyNew = new ItemBody();

        foreach ($itemBody->getContent() as $key => $component) {
            if (!($component instanceof RubricBlock)) {
                $newCollection->attach($component);
            }
        }
        $itemBodyNew->setContent($newCollection);
        return $itemBodyNew;
    }
}
