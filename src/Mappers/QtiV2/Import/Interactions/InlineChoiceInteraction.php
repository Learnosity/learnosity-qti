<?php

namespace Learnosity\Mappers\QtiV2\Import\Interactions;


use Learnosity\Entities\QuestionTypes\clozedropdown;
use Learnosity\Entities\QuestionTypes\clozedropdown_validation;
use Learnosity\Entities\QuestionTypes\clozedropdown_validation_alt_responses_item;
use Learnosity\Entities\QuestionTypes\clozedropdown_validation_valid_response;
use Learnosity\Exceptions\MappingException;
use Learnosity\Mappers\QtiV2\Import\ResponseProcessingTemplate;
use Learnosity\Mappers\QtiV2\Import\Utils\QtiComponentUtil;

class InlineChoiceInteraction extends AbstractInteraction
{
    private $choicesMapping = [];

    public function getQuestionType()
    {

        /* @var \qtism\data\content\interactions\InlineChoiceInteraction $interaction */
        $interaction = $this->validateInteraction($interaction);
        $template = '{{response}}';

        foreach ($interaction->getContent() as $inlineChoice) {
            $this->choicesMapping[$inlineChoice->getIdentifier()] = QtiComponentUtil::marshallCollection($inlineChoice->getContent());
        }

        $validation = $this->buildValidation();
        $question = new clozedropdown('clozedropdown', $template, [array_values($this->choicesMapping)]);
        if ($validation instanceof clozedropdown_validation) {
            $question->set_validation($validation);
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

    private function buildValidation()
    {
        if (empty($this->responseProcessingTemplate)) {
            return null;
        }
        if ($this->responseProcessingTemplate->getTemplate() === ResponseProcessingTemplate::MATCH_CORRECT) {
            $correctResponse = $this->responseDeclaration->getCorrectResponse();
            if (!empty($correctResponse->getInterpretation())) {
                // TODO: should warn that this is ignored
            }

            $validResponseValues = [];
            foreach ($correctResponse->getValues() as $key => $value) {
                $validResponseValues[] = (string)$this->choicesMapping[$value->getValue()];
            }

            $validation = new clozedropdown_validation();
            $validation->set_scoring_type('exactMatch');

            // First response pair shall be mapped to `valid_response`
            $firstValidResponseValue = array_shift($validResponseValues);
            $validResponse = new clozedropdown_validation_valid_response();
            $validResponse->set_score(1);
            $validResponse->set_value([$firstValidResponseValue]);
            $validation->set_valid_response($validResponse);

            // Others go in `alt_responses`
            $altResponses = [];
            foreach ($validResponseValues as $otherResponseValues) {
                $item = new clozedropdown_validation_alt_responses_item();
                $item->set_score(1);
                $item->set_value([$otherResponseValues]);
                $altResponses[] = $item;
            }
            $validation->set_alt_responses($altResponses);

            return $validation;
        } else {
            throw new MappingException('Does not support template ' . $this->responseProcessingTemplate->getTemplate() .
                ' on <responseProcessing>', MappingException::CRITICAL);
        }
    }
}
