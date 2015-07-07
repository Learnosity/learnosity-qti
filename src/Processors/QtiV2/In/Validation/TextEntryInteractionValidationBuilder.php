<?php
namespace Learnosity\Processors\QtiV2\In\Validation;

use Learnosity\Entities\QuestionTypes\clozetext_validation;
use Learnosity\Entities\QuestionTypes\clozetext_validation_alt_responses_item;
use Learnosity\Entities\QuestionTypes\clozetext_validation_valid_response;
use qtism\data\state\MapEntry;
use qtism\data\state\ResponseDeclaration;
use qtism\data\state\Value;

class TextEntryInteractionValidationBuilder extends BaseQtiValidationBuilder
{
    private $isCaseSensitive;

    public function init()
    {
        $this->isCaseSensitive = true;
        $this->scoringType = 'exactMatch';
    }

    protected function handleMatchCorrectTemplate()
    {
        assert(count($this->responseDeclarations)===1);
        /** @var ResponseDeclaration $responseDeclaration */
        $responseDeclaration = $this->responseDeclarations[0];
        //we set all scores to 1 by default
        $score = 1;
        /* @var $value Value */
        foreach ($responseDeclaration->getCorrectResponse()->getValues() as $value) {
            $this->originalResponseData[] = [$value->getValue() => $score];
        }
    }

    protected function handleMapResponseTemplate()
    {
        assert(count($this->responseDeclarations)===1);
        /** @var ResponseDeclaration $responseDeclaration */
        $responseDeclaration = $this->responseDeclarations[0];
        /* @var $mapEntry MapEntry */
        $highestScore = -1;
        foreach ($responseDeclaration->getMapping()->getMapEntries() as $mapEntry) {
            if ($this->isCaseSensitive) {
                $mapEntry->isCaseSensitive();
            }
            if ($mapEntry->getMappedValue() > $highestScore) {
                $highestScore = $mapEntry->getMappedValue();
                array_unshift($this->originalResponseData, [$mapEntry->getMapKey() => $mapEntry->getMappedValue()]);
            } else {
                $this->originalResponseData[] = [$mapEntry->getMapKey() => $mapEntry->getMappedValue()];
            }
        }
    }

    protected function handleCC2MapResponseTemplate()
    {
        $this->handleMapResponseTemplate();
    }

    protected function prepareOriginalResponseData()
    {
        $responseList = [];

        for ($i = 0; $i < count($this->originalResponseData); $i++) {
            $scoreGlobal = 0;
            $value = [];
            foreach ($this->originalResponseData[$i] as $answer => $score) {
                $value[] = $answer;
                $scoreGlobal += $score;
            }
            $responseList[] = [
                'score' => $scoreGlobal,
                'value' => $value
            ];
        }

        $this->originalResponseData = $responseList;
    }

    public function isCaseSensitive() {
        return $this->isCaseSensitive;
    }
}
