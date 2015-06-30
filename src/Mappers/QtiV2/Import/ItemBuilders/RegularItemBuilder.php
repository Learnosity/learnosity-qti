<?php

namespace Learnosity\Mappers\QtiV2\Import\ItemBuilders;


use Learnosity\Entities\Question;
use Learnosity\Mappers\QtiV2\Import\ResponseProcessingTemplate;
use Learnosity\Mappers\QtiV2\Import\Utils\QtiComponentUtil;
use qtism\data\content\interactions\Interaction;
use qtism\data\content\ItemBody;
use qtism\data\QtiComponentCollection;
use qtism\data\state\ResponseDeclaration;

class RegularItemBuilder extends AbstractItemBuilder
{
    const MAPPER_CLASS_BASE = 'Learnosity\Mappers\QtiV2\Import\Interactions\\';

    public function map(
        $assessmentItemIdentifier,
        ItemBody $itemBody,
        QtiComponentCollection $interactionComponents,
        QtiComponentCollection $responseDeclarations = null,
        ResponseProcessingTemplate $responseProcessingTemplate = null
    ) {
        $this->assessmentItemIdentifier = $assessmentItemIdentifier;

        $questionsSpan = [];
        $responseDeclarationsMap = [];

        if ($responseDeclarations) {
            /** @var ResponseDeclaration $responseDeclaration */
            foreach ($responseDeclarations as $responseDeclaration) {
                $responseDeclarationsMap[$responseDeclaration->getIdentifier()] = $responseDeclaration;
            }
        }

        foreach ($interactionComponents as $component) {
            /* @var $component Interaction */
            $questionReference = $assessmentItemIdentifier . '_' . $component->getResponseIdentifier();

            // Process <responseDeclaration>
            $responseDeclaration = isset($responseDeclarationsMap[$component->getResponseIdentifier()]) ?
                $responseDeclarationsMap[$component->getResponseIdentifier()] : null;

            $mapper = $this->getMapperInstance(
                $component->getQtiClassName(),
                [$component, $responseDeclaration, $responseProcessingTemplate]
            );
            $question = $mapper->getQuestionType();

            $this->questions[$questionReference] = new Question($question->get_type(), $questionReference, $question);
            $this->exceptions = array_merge($this->exceptions, $mapper->getExceptions());
            $questionsSpan[$questionReference] = QtiComponentUtil::marshall($component);
        }

        // Build item's HTML content
        $this->content = QtiComponentUtil::marshallCollection($itemBody->getComponents());
        foreach ($questionsSpan as $questionReference => $interactionXml) {
            $questionSpan = '<span class="learnosity-response question-' . $questionReference . '"></span>';
            $this->content = str_replace($interactionXml, $questionSpan, $this->content);
        }
        return true;
    }
}
