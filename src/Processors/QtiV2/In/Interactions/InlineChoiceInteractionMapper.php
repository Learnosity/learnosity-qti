<?php

namespace Learnosity\Processors\QtiV2\In\Interactions;


use Learnosity\Entities\QuestionTypes\clozedropdown;
use Learnosity\Entities\QuestionTypes\clozedropdown_validation;
use Learnosity\Exceptions\MappingException;
use Learnosity\Processors\QtiV2\In\Utils\QtiComponentUtil;
use Learnosity\Processors\QtiV2\In\Validation\InlineChoiceInteractionValidationBuilder;

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

//        $validationBuilder = new InlineChoiceInteractionValidationBuilder(
//            [$interaction->getResponseIdentifier() => $this->choicesMapping],
//            [$interaction->getResponseIdentifier() => $this->responseDeclaration],
//            $this->responseProcessingTemplate
//        );
//        $validation = $validationBuilder->getValidation();
        $isCaseSensitive = false;
        $question = new clozedropdown('clozedropdown', $template, [array_values($this->choicesMapping)]);
        $validation = $this->buildValidation($isCaseSensitive);
        if ($validation) {
            $question->set_validation($validation);
        }
        $question->set_case_sensitive($isCaseSensitive);
        if ($isCaseSensitive) {
            $this->exceptions[] = new MappingException('Partial `caseSensitive` per response is not supported.
                Thus setting all validation as case sensitive');
        }

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

    private function buildValidation(&$isCaseSensitive)
    {
        if (!$this->responseProcessingTemplate) {
            $this->exceptions[] =
                new MappingException(
                    'Response Processing Template is not defined so validation is not available.',
                    MappingException::WARNING
                );
            return null;
        }

        $validationBuilder = new InlineChoiceInteractionValidationBuilder(
            $this->responseProcessingTemplate,
            [$this->interaction->getResponseIdentifier() => $this->responseDeclaration],
            'clozedropdown'
        );
        $validationBuilder->init([$this->interaction->getResponseIdentifier() => $this->choicesMapping]);
        $validation = $validationBuilder->buildValidation();
        $isCaseSensitive = $validationBuilder->isCaseSensitive();
        $this->exceptions = array_merge($this->exceptions, $validationBuilder->getExceptions());
        return $validation;
    }
}
