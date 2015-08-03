<?php

namespace Learnosity\Processors\QtiV2\Out\QuestionTypes;

use Learnosity\Entities\BaseQuestionType;
use Learnosity\Entities\QuestionTypes\shorttext;
use qtism\data\content\FlowStaticCollection;
use qtism\data\content\interactions\ExtendedTextInteraction;
use qtism\data\content\interactions\Prompt;
use qtism\data\content\interactions\TextFormat;

class ShorttextMapper extends AbstractQuestionTypeMapper
{
    public function convert(BaseQuestionType $questionType, $interactionIdentifier, $interactionLabel)
    {
        /** @var shorttext $question */
        $question = $questionType;

        $interaction = new ExtendedTextInteraction($interactionIdentifier);
        $interaction->setLabel($interactionLabel);

        // Build the prompt
        $interaction->setPrompt($this->buildPrompt($question->get_stimulus()));

        // Build placeholder
        $placeholderText = $question->get_placeholder();
        if (!empty($placeholderText)) {
            $interaction->setPlaceholderText($placeholderText);
        }

        $interaction->setMaxStrings(1);
        $interaction->setMinStrings(1);
        $interaction->setFormat(TextFormat::PLAIN);

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
