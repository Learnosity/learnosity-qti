<?php

namespace Learnosity\Mappers\QtiV2\Import\Interactions;

use Learnosity\Mappers\QtiV2\Import\Utils\QtiComponentUtil;
use qtism\data\content\interactions\GapChoice;
use qtism\data\content\interactions\GapMatchInteraction as QtiGapMatchInteraction;
use Learnosity\Entities\QuestionTypes\clozeassociation;
use qtism\data\content\interactions\Prompt;
use qtism\data\QtiComponentCollection;

class GapMatchInteraction extends AbstractInteraction
{
    public function getQuestionType()
    {
        /** @var QtiGapMatchInteraction $interaction */
        $interaction = $this->interaction;

        $possibleResponses = [];
        $gapChoices = $interaction->getComponentsByClassName('gapText', true);
        $gapChoices->merge($interaction->getComponentsByClassName('gapImg', true));
        /** @var GapChoice $gapChoice */
        foreach ($gapChoices as $gapChoice) {
            $gapChoiceContent = QtiComponentUtil::marshallCollection($gapChoice->getComponents());
            $possibleResponses[$gapChoice->getIdentifier()] = $gapChoiceContent;
        }
        $question = new clozeassociation('clozeassociation', $this->buildTemplate($interaction), array_values($possibleResponses));

        if ($interaction->getPrompt() instanceof Prompt) {
            $promptContent = $interaction->getPrompt()->getContent();
            $question->set_stimulus(QtiComponentUtil::marshallCollection($promptContent));
        }
        $validation = $this->buildValidation();
        if ($validation) {
            $question->set_validation($validation);
        }
        return $question;
    }

    private function buildTemplate(QtiGapMatchInteraction $interaction)
    {
        $templateCollection = new QtiComponentCollection();
        foreach ($interaction->getComponents() as $component) {
            // Ignore `prompt` and the `gapChoice` since they are going to be mapped somewhere else :)
            if (!$component instanceof Prompt && !$component instanceof GapChoice) {
                $templateCollection->attach($component);
            }
        }
        $content = QtiComponentUtil::marshallCollection($templateCollection);
        foreach ($interaction->getComponentsByClassName('gap', true) as $gap) {
            $gapString = QtiComponentUtil::marshall($gap);
            $content = str_replace($gapString, '{{response}}', $content);
        }
        return $content;
    }

    private function buildValidation()
    {
        return null;
    }
}
