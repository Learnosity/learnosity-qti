<?php

namespace LearnosityQti\Processors\QtiV2\In\Interactions;

use LearnosityQti\Entities\QuestionTypes\tokenhighlight;
use LearnosityQti\Utils\QtiMarshallerUtil;
use LearnosityQti\Processors\QtiV2\In\Validation\HottextInteractionValidationBuilder;
use qtism\data\content\InlineCollection;
use qtism\data\content\interactions\Hottext;
use qtism\data\content\interactions\HottextInteraction;
use qtism\data\content\interactions\Prompt;
use qtism\data\content\xhtml\text\Span;
use qtism\data\QtiComponentCollection;

class HottextInteractionMapper extends AbstractInteractionMapper
{
    public function getQuestionType()
    {
        /** @var HottextInteraction $interaction */
        $interaction = $this->interaction;
        $question = new tokenhighlight('tokenhighlight', $this->buildTemplate($interaction), 'custom');

        if ($interaction->getPrompt() instanceof Prompt) {
            $promptContent = $interaction->getPrompt()->getContent();
            $question->set_stimulus(QtiMarshallerUtil::marshallCollection($promptContent));
        }

        $question->set_max_selection($interaction->getMaxChoices());

        $validation = $this->buildValidation($interaction);
        if ($validation) {
            $question->set_validation($validation);
        }
        return $question;
    }

    private function buildTemplate(HottextInteraction $interaction)
    {
        $templateCollection = new QtiComponentCollection();
        foreach ($interaction->getComponents() as $component) {
            // Ignore `prompt` since its going to be mapped to `stimulus`
            if (!$component instanceof Prompt) {
                $templateCollection->attach($component);
            }
        }
        $content = QtiMarshallerUtil::marshallCollection($templateCollection);
        foreach ($interaction->getComponentsByClassName('hottext') as $hottext) {
            /** @var Hottext $hottext */
            $hottextString = QtiMarshallerUtil::marshall($hottext);

            $tokenSpan = new span();
            $tokenSpan->setClass('lrn_token');
            $inlineCollection = new InlineCollection();
            foreach ($hottext->getComponents() as $c) {
                $inlineCollection->attach($c);
            }
            $tokenSpan->setContent($inlineCollection);

            // Inject token spans where there are inline hottext nodes
            // HACK: because these tokens are inline, make sure there is space between the token and its inline siblings
            $content = str_replace($hottextString, ' ' . QtiMarshallerUtil::marshall($tokenSpan), $content);
        }
        return $content;
    }

    private function buildValidation(HottextInteraction $interaction)
    {
        $hottextComponents = array_flip(array_map(function ($component) {
            return $component->getIdentifier();
        }, $interaction->getComponentsByClassName('hottext')->getArrayCopy(true)));

        $validationBuilder = new HottextInteractionValidationBuilder(
            $hottextComponents,
            $interaction->getMaxChoices(),
            $this->responseDeclaration
        );
        return $validationBuilder->buildValidation($this->responseProcessingTemplate);
    }
}
