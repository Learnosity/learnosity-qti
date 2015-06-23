<?php

namespace Learnosity\Mappers\QtiV2\Import\Interactions;


use Learnosity\Entities\QuestionTypes\clozedropdown;
use Learnosity\Entities\QuestionTypes\clozedropdown_validation;
use Learnosity\Entities\QuestionTypes\clozedropdown_validation_alt_responses_item;
use Learnosity\Entities\QuestionTypes\clozedropdown_validation_valid_response;
use Learnosity\Exceptions\MappingException;
use Learnosity\Mappers\QtiV2\Import\ResponseProcessingTemplate;
use Learnosity\Mappers\QtiV2\Import\Utils\QtiComponentUtil;
use qtism\data\state\MapEntry;

class InlineChoiceInteraction extends AbstractInteraction
{
    private $choicesMapping = [];
    private $caseSensitive = false;

    public function getQuestionType()
    {
        /* @var \qtism\data\content\interactions\InlineChoiceInteraction $interaction */
        $interaction = $this->validateInteraction($this->interaction);
        $template = '{{response}}';

        foreach ($interaction->getContent() as $inlineChoice) {
            $this->choicesMapping[$inlineChoice->getIdentifier()] = QtiComponentUtil::marshallCollection($inlineChoice->getContent());
        }

        $validation = $this->buildValidation();
        $question = new clozedropdown('clozedropdown', $template, [array_values($this->choicesMapping)]);
        if ($validation instanceof clozedropdown_validation) {
            $question->set_validation($validation);
        }
        $question->set_case_sensitive($this->caseSensitive);
        if ($this->caseSensitive) {
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

    private function buildValidation()
    {
        if (empty($this->responseProcessingTemplate)) {
            return null;
        }
        if ($this->responseProcessingTemplate->getTemplate() === ResponseProcessingTemplate::MATCH_CORRECT) {
            return $this->buildMatchCorrectValidation();
        } if ($this->responseProcessingTemplate->getTemplate() === ResponseProcessingTemplate::MAP_RESPONSE) {
            return $this->buildMapResponseValidation();
        } else {
            $this->exceptions[] = new MappingException('Does not support template ' . $this->responseProcessingTemplate->getTemplate() .
                ' on <responseProcessing>. Ignoring <responseProcessing>');
            return null;
        }
    }

    private function buildMatchCorrectValidation()
    {
        $validResponseValues = [];
        foreach ($this->responseDeclaration->getCorrectResponse()->getValues() as $key => $value) {
            $validResponseValues[] = (string) $this->choicesMapping[$value->getValue()];
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
    }

    private function buildMapResponseValidation()
    {
        $validation = new clozedropdown_validation();
        $validation->set_scoring_type('exactMatch');

        $altResponses = [];
        $mapEntries = $this->responseDeclaration->getMapping()->getMapEntries()->getArrayCopy(true);

        // Sort by score value, as the first/biggest would be used for `valid_response` object
        usort($mapEntries, function($a, $b) {
            return $a->getMappedValue() < $b->getMappedValue();
        });
        foreach ($mapEntries as $key => $mapEntry) {
            /** @var MapEntry $mapEntry */

            // If one of them case sensitive, the whole things then be it
            if ($mapEntry->isCaseSensitive()) {
                $this->caseSensitive = true;
            }

            // First response pair shall be mapped to `valid_response`
            if ($key === 0) {
                $validResponse = new clozedropdown_validation_valid_response();
                $validResponse->set_value([$this->choicesMapping[$mapEntry->getMapKey()]]);
                $validResponse->set_score($mapEntry->getMappedValue());
                $validation->set_valid_response($validResponse);
            } else {
                // Others go in `alt_responses`
                $altResponseItem = new clozedropdown_validation_alt_responses_item();
                $altResponseItem->set_value([$this->choicesMapping[$mapEntry->getMapKey()]]);
                $altResponseItem->set_score($mapEntry->getMappedValue());
                $altResponses[] = $altResponseItem;
            }
        }

        if (!empty($altResponses)) {
            $validation->set_alt_responses($altResponses);
        }
        return $validation;
    }
}
