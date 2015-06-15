<?php

namespace Learnosity\Mappers\QtiV2\Import\Interactions;


use Learnosity\Entities\QuestionTypes\clozedropdown;
use Learnosity\Entities\QuestionTypes\clozedropdown_validation;
use Learnosity\Entities\QuestionTypes\clozedropdown_validation_valid_response;
use Learnosity\Exceptions\MappingException;
use Learnosity\Mappers\QtiV2\Import\ResponseProcessingTemplate;
use Learnosity\Mappers\QtiV2\Import\Utils\QtiComponentUtil;
use qtism\data\content\interactions\InlineChoice;

class InlineChoiceInteraction extends AbstractInteraction
{
    public function getQuestionType()
    {
        /* @var \qtism\data\content\interactions\InlineChoiceInteraction $interaction */
        $interaction = $this->interaction;

        $this->choicesMapping = [];
        // todo
        $template = '{{response}}';

        foreach ($interaction->getContent() as $i) {

            if ($i instanceof InlineChoice) {
                $this->choicesMapping[$i->getIdentifier()] = QtiComponentUtil::marshallCollection($i->getContent());
            }

        }

        $validation = $this->buildValidation();
        $question = new clozedropdown('clozedropdown', $template, [array_values($this->choicesMapping)]);
        $question->set_validation($validation);
        return $question;
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
                $optionIndex = $this->choicesMapping[$value->getValue()];
                $validResponseValues[] = (string)$optionIndex;
            }

            $validation = new clozedropdown_validation();
            $validation->set_scoring_type('exactMatch');
            $validResponse = new clozedropdown_validation_valid_response();
            $validResponse->set_score(1);
            $validResponse->set_value($validResponseValues);
            $validation->set_valid_response($validResponse);
            return $validation;
        } else {
            throw new MappingException('Does not support template ' . $this->responseProcessingTemplate->getTemplate() .
                ' on <responseProcessing>', MappingException::CRITICAL);
        }

    }
}
