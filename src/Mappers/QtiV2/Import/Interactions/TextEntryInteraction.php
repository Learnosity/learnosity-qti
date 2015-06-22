<?php

namespace Learnosity\Mappers\QtiV2\Import\Interactions;

use Learnosity\Entities\QuestionTypes\clozetext;
use Learnosity\Entities\QuestionTypes\clozetext_validation;
use Learnosity\Entities\QuestionTypes\clozetext_validation_alt_responses_item;
use Learnosity\Entities\QuestionTypes\clozetext_validation_valid_response;
use Learnosity\Exceptions\MappingException;
use Learnosity\Mappers\QtiV2\Import\ResponseProcessingTemplate;
use qtism\data\state\MapEntry;
use qtism\data\state\Value;

class TextEntryInteraction extends AbstractInteraction
{
    public function getQuestionType()
    {
        $closetext = new clozetext('clozetext', '{{response}}');

        $expectedLength = $this->interaction->getExpectedLength();
        if ($expectedLength > 250) {
            $expectedLength = 250;
            $closetext->set_multiple_line(true);
        }
        $closetext->set_max_length($expectedLength);

        $validation = $this->buildValidation($isCaseSensitive);
        if ($validation) {
            $closetext->set_validation($validation);
        }
        $closetext->set_case_sensitive($isCaseSensitive);

        return $closetext;
    }

    private function buildValidation(&$isCaseSensitive)
    {
        $isCaseSensitive = true;
        $validation = null;
        $validResponse = null;
        $altResponses = [];
        $answers = [];
        if (!$this->responseProcessingTemplate) {
            $this->exceptions[] =
                new MappingException('Response Processing Template is not defined so validation is not available.',
                    MappingException::WARNING);
            return null;
        } else {

            switch ($this->responseProcessingTemplate->getTemplate()) {
                case ResponseProcessingTemplate::MATCH_CORRECT:
                    //we set all scores to 1 by default
                    $score = 1;
                    /* @var $value Value */
                    foreach ($this->responseDeclaration->getCorrectResponse()->getValues() as $value) {
                        $answers[] = [$value->getValue() => $score];
                    }
                    break;
                case ResponseProcessingTemplate::MAP_RESPONSE:
                    /* @var $mapEntry MapEntry */
                    $highestScore = -1;
                    foreach ($this->responseDeclaration->getMapping()->getMapEntries() as $mapEntry) {
                        if ($isCaseSensitive) {
                            $mapEntry->isCaseSensitive();
                        }
                        if ($mapEntry->getMappedValue() > $highestScore) {
                            $highestScore = $mapEntry->getMappedValue();
                            array_unshift($answers, [$mapEntry->getMapKey() => $mapEntry->getMappedValue()]);
                        } else {
                            $answers[] = [$mapEntry->getMapKey() => $mapEntry->getMappedValue()];
                        }
                    }
                    break;
                default:
                    $this->exceptions[] =
                        new MappingException('Unrecognised response processing template. Validation is not available',
                            MappingException::WARNING);
                    return null;
            }

        }

        if (count($answers) > 0) {
            for ($i = 0; $i < count($answers); $i++) {
                $scoreGlobal = 1;
                $value = [];
                foreach ($answers[$i] as $answer => $score) {
                    $value[] = $answer;
                    $scoreGlobal = $score;
                }
                if($i===0) {
                    $validResponse = new clozetext_validation_valid_response();
                    $validResponse->set_value($value);
                    $validResponse->set_score($scoreGlobal);
                }else {
                    $altResponse = new clozetext_validation_alt_responses_item();
                    $altResponse->set_value($value);
                    $altResponse->set_score($scoreGlobal);
                    $altResponses[] = $altResponse;
                }
            }

        }

        if ($validResponse) {
            $validation = new clozetext_validation();
            $validation->set_scoring_type('exactMatch');
            $validation->set_valid_response($validResponse);
        }

        if ($altResponses && $validation) {
            $validation->set_alt_responses($altResponses);
        }

        return $validation;
    }
}
