<?php

namespace Learnosity\Processors\QtiV2\Out\QuestionTypes;

use Learnosity\Entities\BaseQuestionType;
use Learnosity\Entities\QuestionTypes\orderlist;
use Learnosity\Utils\QtiMarshallerUtil;
use qtism\data\content\FlowStaticCollection;
use qtism\data\content\interactions\OrderInteraction;
use qtism\data\content\interactions\Orientation;
use qtism\data\content\interactions\Prompt;
use qtism\data\content\interactions\SimpleChoice;
use qtism\data\content\interactions\SimpleChoiceCollection;

class OrderlistMapper extends AbstractQuestionTypeMapper
{
    public function convert(BaseQuestionType $questionType, $interactionIdentifier, $interactionLabel)
    {
        /** @var orderlist $question */
        $question = $questionType;

        $simpleChoiceCollection = new SimpleChoiceCollection();
        foreach ($question->get_list() as $key => $item) {
            $simpleChoice = new SimpleChoice('CHOICE_' . $key);
            $choiceContent = new FlowStaticCollection();
            foreach (QtiMarshallerUtil::unmarshallElement($item) as $component) {
                $choiceContent->attach($component);

            }
            $simpleChoice->setContent($choiceContent);
            $simpleChoiceCollection->attach($simpleChoice);
        }

        $interaction = new OrderInteraction($interactionIdentifier, $simpleChoiceCollection);
        $interaction->setLabel($interactionLabel);
        $interaction->setPrompt($this->buildPrompt($question->get_stimulus()));

        $interaction->setShuffle(false);
        $interaction->setMinChoices(1);
        $interaction->setMaxChoices(1);
        $interaction->setOrientation(Orientation::VERTICAL);

        return [$interaction, null, null];
    }

    private function buildPrompt($stimulusString)
    {
        $prompt = new Prompt();
        $contentCollection = new FlowStaticCollection();
        foreach ($this->convertStimulus($stimulusString) as $component) {
            $contentCollection->attach($component);
        }
        $prompt->setContent($contentCollection);
        return $prompt;
    }
}
