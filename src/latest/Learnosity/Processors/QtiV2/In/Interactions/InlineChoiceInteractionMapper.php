<?php

namespace Learnosity\Processors\QtiV2\In\Interactions;

use Learnosity\Entities\QuestionTypes\clozedropdown;
use Learnosity\Utils\QtiMarshallerUtil;
use Learnosity\Processors\QtiV2\In\Validation\InlineChoiceInteractionValidationBuilder;
use Learnosity\Services\LogService;
use qtism\data\content\interactions\InlineChoiceInteraction;

class InlineChoiceInteractionMapper extends AbstractInteractionMapper
{
    private $choicesMapping = [];

    public function getQuestionType()
    {
        /* @var \qtism\data\content\interactions\InlineChoiceInteraction $interaction */
        $interaction = $this->validateInteraction($this->interaction);
        $template = '{{response}}';

        foreach ($interaction->getContent() as $inlineChoice) {
            $this->choicesMapping[$inlineChoice->getIdentifier()] =
                QtiMarshallerUtil::marshallCollection($inlineChoice->getContent());
        }

        $question = new clozedropdown('clozedropdown', $template, [array_values($this->choicesMapping)]);
        $validation = $this->buildValidation();
        if ($validation) {
            $question->set_validation($validation);
        }
        return $question;
    }

    private function validateInteraction(InlineChoiceInteraction $interaction)
    {
        if (!empty($interaction->mustShuffle())) {
            LogService::log('The attribute `shuffle` is not supported, thus is ignored');
        }
        if (!empty($interaction->isRequired())) {
            LogService::log('The attribute `required` is not supported, thus is ignored');
        }
        return $interaction;
    }

    private function buildValidation()
    {
        $validationBuilder = new InlineChoiceInteractionValidationBuilder(
            [$this->interaction->getResponseIdentifier() => $this->responseDeclaration],
            [$this->interaction->getResponseIdentifier() => $this->choicesMapping]
        );
        return $validationBuilder->buildValidation($this->responseProcessingTemplate);
    }
}
