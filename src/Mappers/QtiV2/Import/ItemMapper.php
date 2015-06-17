<?php

namespace Learnosity\Mappers\QtiV2\Import;

use Learnosity\Entities\Item;
use Learnosity\Entities\Question;
use Learnosity\Exceptions\MappingException;
use Learnosity\Mappers\QtiV2\Import\Interactions\AbstractInteraction;
use Learnosity\Mappers\QtiV2\Import\Utils\QtiComponentUtil;
use Learnosity\Mappers\QtiV2\Import\Utils\QtiV2Util;
use qtism\data\AssessmentItem;
use qtism\data\content\BlockCollection;
use qtism\data\content\interactions\Interaction;
use qtism\data\content\ItemBody;
use qtism\data\content\RubricBlock;
use qtism\data\content\xhtml\text\Span;
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
        $document = new XmlCompactDocument();
        // TODO: Create ResourceReplacer class here to replace images or take it outside this class!
        $document->loadFromString($xmlString);

        /* @var $assessmentItem AssessmentItem */
        $assessmentItem = $this->validateAssessmentItem($document->getDocumentComponent());
        $responseProcessingTemplate = $this->getResponseProcessingTemplate($assessmentItem->getResponseProcessing());

        // Process <itemBody>
        $questions = [];
        $questionsSpan = [];
        $itemBody = $assessmentItem->getItemBody();
        $itemBody = $this->filterItemBody($itemBody);

        $interactionComponents = $itemBody->getComponentsByClassName($this->supportedInteractions, true);
        if (!$interactionComponents || count($interactionComponents) === 0) {
            throw new MappingException('No supported interactions could be found', MappingException::CRITICAL);
        }
        foreach ($interactionComponents as $component) {
            try {
                /* @var $component Interaction */
                $questionReference = $assessmentItem->getIdentifier() . '_' . $component->getResponseIdentifier();

                // Process <responseDeclaration>
                // TODO: According to QTI, an item should have the corresponding responseDeclaration, thus shall throw error
                // TODO: if it doesn't or perhaps simply ignore?
                /** @var ResponseDeclaration $responseDeclaration */
                $responseDeclaration = $assessmentItem->getComponentByIdentifier($component->getResponseIdentifier());
                $questions[$questionReference] = $this->buildLearnosityQuestion($questionReference, $component, $responseDeclaration, $responseProcessingTemplate);

                // Store 'span' for later replacement with preg replace
                $questionSpan = new span();
                $questionSpan->setClass('learnosity-response question-' . $questionReference);
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

        $item = new Item($assessmentItem->getIdentifier(), array_keys($questions), $content);
        $item->set_status('published');
        return [$item, $questions, $this->exceptions];
    }

    private function validateAssessmentItem(AssessmentItem $assessmentItem)
    {
        if ($assessmentItem->getOutcomeDeclarations()->count()) {
            $this->exceptions[] = new MappingException('Ignoring <outcomeDeclaration> on <assessmentItem>. Generally we mapped <defaultValue> to 0');
        }
        if ($assessmentItem->getTemplateDeclarations()->count()) {
            throw new MappingException('Does not support <templateDeclaration> on <assessmentItem>', MappingException::CRITICAL);
        }
        if (!empty($assessmentItem->getTemplateProcessing())) {
            throw new MappingException('Does not support <templateProcessing> on <assessmentItem>', MappingException::CRITICAL);
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
                throw new MappingException('Does not support custom response processing on <responseProcessing>', MappingException::CRITICAL);
            }
            if (!empty($responseProcessing->getTemplateLocation())) {
                throw new MappingException('Does not support \'templateLocation\' on <responseProcessing>', MappingException::CRITICAL);
            }
            if (!empty($responseProcessing->getTemplate())) {
                $responseProcessingTemplate = ResponseProcessingTemplate::getFromTemplateUrl($responseProcessing->getTemplate());
                if (empty($responseProcessingTemplate)) {
                    throw new MappingException('Does not support custom response processing templates', MappingException::CRITICAL);
                }
                return $responseProcessingTemplate;
            }
        }
        return ResponseProcessingTemplate::matchCorrect();
    }

    private function buildLearnosityQuestion($questionReference, Interaction $component, ResponseDeclaration $responseDeclaration = null, ResponseProcessingTemplate $responseProcessingTemplate = null)
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
