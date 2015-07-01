<?php

namespace Learnosity\Mappers\QtiV2\Import\Interactions;

use Learnosity\Entities\QuestionTypes\orderlist;
use Learnosity\Entities\QuestionTypes\orderlist_validation;
use Learnosity\Entities\QuestionTypes\orderlist_validation_valid_response;
use Learnosity\Exceptions\MappingException;
use Learnosity\Mappers\QtiV2\Import\ResponseProcessingTemplate;
use Learnosity\Mappers\QtiV2\Import\Utils\QtiComponentUtil;
use qtism\data\content\interactions\OrderInteraction as QtiOrderInteraction;
use qtism\data\content\interactions\SimpleChoice;
use qtism\data\state\Value;

class OrderInteraction extends AbstractInteraction
{
    private $orderMapping;

    public function getQuestionType()
    {
        /* @var QtiOrderInteraction $interaction */
        $interaction = $this->interaction;
        if (!$this->validate($interaction)) {
            return null;
        }

        $list = [];
        $this->orderMapping = [];
        /** @var SimpleChoice $simpleChoice */
        foreach ($interaction->getSimpleChoices() as $simpleChoice) {
            $this->orderMapping[$simpleChoice->getIdentifier()] = count($this->orderMapping);
            $list[] = QtiComponentUtil::marshallCollection($simpleChoice->getContent());
        }

        $validation = $this->buildValidation();

        $question = new orderlist('orderlist', $list);
        $question->set_stimulus($this->getPrompt());
        if ($validation) {
            $question->set_validation($validation);
        }
        return $question;
    }

    private function validate(QtiOrderInteraction $interaction)
    {
        if ($interaction->mustShuffle()) {
            $this->exceptions[] = new MappingException('Attribute shuffle is not supported');
        }

        $simpleChoiceCollection = $interaction->getSimpleChoices();

        /** @var SimpleChoice $simpleChoice */
        foreach ($simpleChoiceCollection as $simpleChoice) {
            if ($simpleChoice->isFixed()) {
                $this->exceptions[] = new MappingException(
                    'Attribute "Fixed" for ' . $simpleChoice->getIdentifier() . 'is not supported');
            }
        }

        return true;
    }

    private function buildValidation()
    {
        $answers = [];
        $totalScore = 1;

        if (!$this->responseProcessingTemplate) {
            $this->exceptions[] =
                new MappingException('Response Processing Template is not defined so validation is not available.');
            return null;
        } else {
            switch ($this->responseProcessingTemplate->getTemplate()) {
                case ResponseProcessingTemplate::MATCH_CORRECT:
                    foreach ($this->responseDeclaration->getCorrectResponse()->getValues() as $value) {
                        if ($value instanceof Value) {
                            $answers[$value->getValue()] = count($answers);
                        }
                    }
                    break;
                case ResponseProcessingTemplate::MAP_RESPONSE:
                default:
                    $this->exceptions[] =
                        new MappingException('Unrecognised response processing template. Validation is not available');
                    return null;
            }
        }

        $responseValue = [];

        foreach ($this->orderMapping as $mappingIdentifier => $index) {

            if (!isset($answers[$mappingIdentifier])) {
                $this->exceptions [] = new MappingException(
                    'Cannot locate ' . $mappingIdentifier . ' in responseDeclaration');
                continue;
            }
            $answerIndex = $answers[$mappingIdentifier];
            $responseValue[$answerIndex] = $index;
        }
        ksort($responseValue);

        $validResponse = new orderlist_validation_valid_response();
        $validResponse->set_value($responseValue);
        $validResponse->set_score($totalScore);

        $validation = new orderlist_validation();
        $validation->set_valid_response($validResponse);
        $validation->set_scoring_type('exactMatch');

        return $validation;
    }
}