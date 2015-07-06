<?php

namespace Learnosity\Mappers\QtiV2\Import\Interactions;

use Learnosity\Entities\QuestionTypes\choicematrix;
use Learnosity\Entities\QuestionTypes\choicematrix_ui_style;
use Learnosity\Entities\QuestionTypes\choicematrix_validation;
use Learnosity\Entities\QuestionTypes\choicematrix_validation_valid_response;
use Learnosity\Exceptions\MappingException;
use Learnosity\Mappers\QtiV2\Import\ResponseProcessingTemplate;
use Learnosity\Mappers\QtiV2\Import\Utils\QtiComponentUtil;
use qtism\common\datatypes\DirectedPair;
use qtism\data\content\interactions\MatchInteraction as QtiMatchInteraction;
use qtism\data\content\interactions\SimpleAssociableChoice;
use qtism\data\content\interactions\SimpleMatchSet;
use qtism\data\state\MapEntry;

class MatchInteractionMapper extends AbstractInteractionMapper
{
    private $stemMapping = [];
    private $optionsMapping = [];

    public function getQuestionType()
    {
        /* @var QtiMatchInteraction $interaction */
        $interaction = $this->interaction;

        if ($interaction->mustShuffle()) {
            $this->exceptions[] = new MappingException('Shuffle attribute is not supported', MappingException::WARNING);
        }
        $simpleMatchSetCollection = $interaction->getSimpleMatchSets();

        $stems = $this->buildOptions($simpleMatchSetCollection[0], $this->stemMapping);
        $options = $this->buildOptions($simpleMatchSetCollection[1], $this->optionsMapping);

        $isMultipleResponse = false;
        $validation = $this->buildValidation($isMultipleResponse);

        if ($interaction->getMaxAssociations() !== count($stems)) {
            $this->exceptions[] =
                new MappingException('Max Association number not equals to number of stems is not supported');
        }

        $uiStyle = new choicematrix_ui_style();
        $uiStyle->set_type('table');

        $question = new choicematrix('choicematrix', $isMultipleResponse, $options, $stems);
        $question->set_stimulus($this->getPrompt());
        $question->set_ui_style($uiStyle);
        if ($validation) {
            $question->set_validation($validation);
        }
        return $question;
    }

    private function buildOptions(SimpleMatchSet $simpleMatchSet, &$mapping)
    {
        $options = [];
        $choiceCollection = $simpleMatchSet->getSimpleAssociableChoices();
        /** @var SimpleAssociableChoice $choice */
        foreach ($choiceCollection as $choice) {
            $contentStr = QtiComponentUtil::marshallCollection($choice->getContent());
            $options[] = $contentStr;
            $mapping[$choice->getIdentifier()] = count($options) - 1;
        }

        return $options;
    }

    private function buildValidation(&$isMultipleResponse)
    {
        $answers = [];
        // Match Interaction support different score definition for each mapping.
        // However, choiceMatrix question type only support 1 score for all mappings
        $totalScore = 1;

        if (!$this->responseProcessingTemplate) {
            $this->exceptions[] =
                new MappingException('Response Processing Template is not defined so validation is not available.');
            return null;
        } else {
            switch ($this->responseProcessingTemplate->getTemplate()) {
                case ResponseProcessingTemplate::MATCH_CORRECT:
                    $score = 1;
                    foreach ($this->responseDeclaration->getCorrectResponse()->getValues() as $value) {
                        if ($value->getValue() instanceof DirectedPair) {
                            $answers[] = [$value->getValue()->getFirst() . ' ' .
                                          $value->getValue()->getSecond() => $score];
                        }
                    }
                    break;
                case ResponseProcessingTemplate::MAP_RESPONSE:
                    /* @var $mapEntry MapEntry */
                    $totalScore = 0;
                    foreach ($this->responseDeclaration->getMapping()->getMapEntries() as $mapEntry) {
                        /** @var MapEntry $mapEntry */
                        $totalScore += $mapEntry->getMappedValue();
                        if ($mapEntry->getMapKey() instanceof DirectedPair) {
                            $answers[] = [$mapEntry->getMapKey()->getFirst() . ' ' .
                                          $mapEntry->getMapKey()->getSecond() => $mapEntry->getMappedValue()];
                        }
                    }
                    break;
                default:
                    $this->exceptions[] =
                        new MappingException('Unrecognised response processing template. Validation is not available');
                    return null;
            }
        }

        $responseValue = [];
        foreach ($answers as $answer) {
            $answerIDStr = array_keys($answer)[0];
            $answerIDList = explode(' ', $answerIDStr);

            if (count($answerIDList) !== 2) {
                $this->exceptions[] =
                    new MappingException("Answer {$answerIDStr} is not valid", MappingException::CRITICAL);
                continue;
            }

            $stemIdentifier = isset($this->stemMapping[$answerIDList[0]]) ? $answerIDList[0] : $answerIDList[1];
            $optionIdentifier = isset($this->optionsMapping[$answerIDList[0]]) ? $answerIDList[0] : $answerIDList[1];

            $stemMapIndex = $this->stemMapping[$stemIdentifier];

            if (!isset($responseValue[$stemMapIndex])) {
                $responseValue[$stemMapIndex] = [];
            }

            if (!$isMultipleResponse && count($responseValue[$stemMapIndex]) === 1) {
                $isMultipleResponse = true;
            }
            $responseValue[$stemMapIndex][] = $this->optionsMapping[$optionIdentifier];
        }
        ksort($responseValue);
        $validResponse = new choicematrix_validation_valid_response();
        $validResponse->set_value($responseValue);
        $validResponse->set_score($totalScore);

        $validation = new choicematrix_validation();
        $validation->set_valid_response($validResponse);
        $validation->set_scoring_type('exactMatch');

        return $validation;
    }
}
