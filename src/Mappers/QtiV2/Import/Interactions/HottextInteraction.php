<?php

namespace Learnosity\Mappers\QtiV2\Import\Interactions;

use Learnosity\Entities\QuestionTypes\tokenhighlight;
use Learnosity\Entities\QuestionTypes\tokenhighlight_validation;
use Learnosity\Entities\QuestionTypes\tokenhighlight_validation_valid_response;
use Learnosity\Mappers\QtiV2\Import\Utils\QtiComponentUtil;
use qtism\data\content\InlineCollection;
use qtism\data\content\interactions\Hottext;
use qtism\data\content\interactions\Prompt;
use qtism\data\content\xhtml\text\Span;
use qtism\data\content\interactions\HottextInteraction as QtiHottextInteraction;
use qtism\data\state\ResponseDeclaration;
use qtism\data\state\Value;

class HottextInteraction extends AbstractInteraction
{
    public function getQuestionType()
    {
        /** @var QtiHottextInteraction $interaction */
        $interaction = $this->interaction;
        $question = new tokenhighlight('tokenhighlight', $this->buildTemplate($interaction), 'custom');

        if ($interaction->getPrompt() instanceof Prompt) {
            $promptContent = $interaction->getPrompt()->getContent();
            $question->set_stimulus(QtiComponentUtil::marshallCollection($promptContent));
        }

        if ($interaction->getMaxChoices()) {
            $question->set_max_selection($interaction->getMaxChoices());
        }

        $validation = $this->buildValidation($interaction, $this->responseDeclaration);
        if ($validation) {
            $question->set_validation($validation);
        }
        return $question;
    }

    private function buildValidation(QtiHottextInteraction $interaction, ResponseDeclaration $responseDeclaration)
    {
        $hottextComponents = array_flip(array_map(function ($component) {
            return $component->getIdentifier();
        }, $interaction->getComponentsByClassName('hottext')->getArrayCopy(true)));

        $validResponseValues = [];
        foreach ($responseDeclaration->getCorrectResponse()->getValues() as $value) {
            /** @var Value $value */
            $hottextIdentifier = $value->getValue();
            $validResponseValues[] = $hottextComponents[$hottextIdentifier];
        }

        $validation = new tokenhighlight_validation();
        $validation->set_scoring_type('exactMatch');
        $validResponse = new tokenhighlight_validation_valid_response();
        $validResponse->set_score(1);
        $validResponse->set_value($validResponseValues);
        $validation->set_valid_response($validResponse);

        return $validation;
    }

    private function buildTemplate(QtiHottextInteraction $interaction)
    {
        $content = QtiComponentUtil::marshallCollection($interaction->getComponents());
        foreach ($interaction->getComponentsByClassName('hottext') as $hottext) {
            /** @var Hottext $hottext */
            $hottextString = QtiComponentUtil::marshall($hottext);

            $tokenSpan = new span();
            $tokenSpan->setClass('lrn_token');
            $inlineCollection = new InlineCollection();
            foreach ($hottext->getComponents() as $c) {
                $inlineCollection->attach($c);
            }
            $tokenSpan->setContent($inlineCollection);
            $content = str_replace($hottextString, QtiComponentUtil::marshall($tokenSpan), $content);
        }
        return $content;
    }
}
