<?php

namespace Learnosity\Mappers\QtiV2\Import\Interactions;


use Learnosity\Entities\QuestionTypes\clozedropdown;
use Learnosity\Entities\QuestionTypes\clozedropdown_validation;
use Learnosity\Exceptions\MappingException;
use Learnosity\Mappers\QtiV2\Import\Utils\QtiComponentUtil;
use Learnosity\Mappers\QtiV2\Import\Validation\InlineChoiceInteractionValidationBuilder;

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
                QtiComponentUtil::marshallCollection($inlineChoice->getContent());
        }

        $validationBuilder = new InlineChoiceInteractionValidationBuilder(
            [$interaction->getResponseIdentifier() => $this->choicesMapping],
            [$interaction->getResponseIdentifier() => $this->responseDeclaration],
            $this->responseProcessingTemplate
        );
        $validation = $validationBuilder->getValidation();
        $question = new clozedropdown('clozedropdown', $template, [array_values($this->choicesMapping)]);
        if ($validation instanceof clozedropdown_validation) {
            $question->set_validation($validation);
        }
        $isCaseSensitive = $validationBuilder->isCaseSensitive();
        $question->set_case_sensitive($isCaseSensitive);
        if ($isCaseSensitive) {
            $this->exceptions[] = new MappingException('Partial `caseSensitive` per response is not supported.
                Thus setting all validation as case sensitive');
        }

        $this->exceptions = array_merge($this->exceptions, $validationBuilder->getExceptions());
        return $question;
    }

    private function validateInteraction(\qtism\data\content\interactions\InlineChoiceInteraction $interaction)
    {
        if (!empty($interaction->mustShuffle())) {
            $this->exceptions[] = new MappingException('The attribute `shuffle` is not supported, thus is ignored');
        }
        if (!empty($interaction->isRequired())) {
            $this->exceptions[] = new MappingException('The attribute `required` is not supported, thus is ignored');
        }
        return $interaction;
    }
}
