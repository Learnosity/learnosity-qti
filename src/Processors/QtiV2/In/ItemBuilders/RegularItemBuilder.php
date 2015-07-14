<?php

namespace Learnosity\Processors\QtiV2\In\ItemBuilders;


use Learnosity\Entities\Question;
use Learnosity\Processors\QtiV2\In\ResponseProcessingTemplate;
use Learnosity\Processors\QtiV2\In\Utils\QtiComponentUtil;
use Learnosity\Services\LogService;
use qtism\data\content\interactions\Interaction;
use qtism\data\content\ItemBody;
use qtism\data\QtiComponentCollection;
use qtism\data\state\ResponseDeclaration;

class RegularItemBuilder extends AbstractItemBuilder
{
    const MAPPER_CLASS_BASE = 'Learnosity\Processors\QtiV2\In\Interactions\\';

    public function map(
        $assessmentItemIdentifier,
        ItemBody $itemBody,
        QtiComponentCollection $interactionComponents,
        QtiComponentCollection $responseDeclarations = null,
        ResponseProcessingTemplate $responseProcessingTemplate = null
    ) {
        $this->assessmentItemIdentifier = $assessmentItemIdentifier;

        $questionsXmls = [];
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
            $questionsXmls[$questionReference] = QtiComponentUtil::marshall($component);
        }

        // Build item's HTML content
        $extraContent = QtiComponentUtil::marshallCollection($itemBody->getComponents());
        foreach ($questionsXmls as $questionReference => $interactionXml) {
            // Append this question span to our `item` content as it is
            $this->content .= '<span class="learnosity-response question-' . $questionReference . '"></span>';
            // Store extra html content since we now has to slug it in into question stimulus
            $extraContent = str_replace($interactionXml, '', $extraContent);
        }

        // Making assumption question always has stimulus `right`?
        // So, prepend the extra content on the stimulus on the first question
        if (!empty(trim($extraContent))) {
            $firstQuestionReference = key($this->questions);
            $newStimulus = $extraContent . $this->questions[$firstQuestionReference]->get_data()->get_stimulus();
            $this->questions[$firstQuestionReference]->get_data()->set_stimulus($newStimulus);

            LogService::log('Extra <itemBody> content is prepended to question stimulus and please verify as this `might` break item content structure');
        }
        return true;
    }
}
