<?php

namespace Learnosity\Processors\QtiV2\Out\QuestionTypes;

use Learnosity\Entities\BaseQuestionType;
use Learnosity\Entities\QuestionTypes\longtext;
use Learnosity\Services\LogService;
use qtism\data\content\FlowStaticCollection;
use qtism\data\content\interactions\ExtendedTextInteraction;
use qtism\data\content\interactions\Prompt;
use qtism\data\content\interactions\TextFormat;

class LongtextMapper extends AbstractQuestionTypeMapper
{
    public function convert(BaseQuestionType $questionType, $interactionIdentifier, $interactionLabel)
    {
        /** @var longtext $question */
        $question = $questionType;

        $interaction = new ExtendedTextInteraction($interactionIdentifier);
        $interaction->setLabel($interactionLabel);
        $interaction->setPrompt($this->buildPrompt($question->get_stimulus()));
        $interaction->setFormat(TextFormat::XHTML);
        $interaction->setMinStrings(1);
        $interaction->setMaxStrings(1);

        $placeholderText = $question->get_placeholder();
        if (!empty($placeholderText)) {
            $interaction->setPlaceholderText($placeholderText);
        }

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
