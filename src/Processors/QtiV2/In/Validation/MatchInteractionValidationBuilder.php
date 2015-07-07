<?php
namespace Learnosity\Processors\QtiV2\In\Validation;

use Learnosity\Exceptions\MappingException;
use Learnosity\Utils\ArrayUtil;
use qtism\common\datatypes\DirectedPair;
use qtism\data\state\MapEntry;
use qtism\data\state\ResponseDeclaration;

class MatchInteractionValidationBuilder extends BaseQtiValidationBuilder
{

    private $stemsMapping;
    private $optionsMapping;
    private $isMultipleResponse = false;
    private $totalScore = 0;

    public function init(array $stemsMapping, array $optionsMapping)
    {
        $this->stemsMapping = $stemsMapping;
        $this->optionsMapping = $optionsMapping;
        $this->scoringType = 'exactMatch';
    }

    protected function handleMatchCorrectTemplate()
    {
        assert(count($this->responseDeclarations) === 1);
        /** @var ResponseDeclaration $responseDeclaration */
        $responseDeclaration = $this->responseDeclarations[0];
        $this->totalScore = 1;
        foreach ($responseDeclaration->getCorrectResponse()->getValues() as $value) {
            if ($value->getValue() instanceof DirectedPair) {
                $k = $value->getValue()->getFirst() . ' ' . $value->getValue()->getSecond();
                $this->originalResponseData[] = [$k => $this->totalScore];
            }
        }
    }

    protected function handleMapResponseTemplate()
    {
        assert(count($this->responseDeclarations) === 1);
        /** @var ResponseDeclaration $responseDeclaration */
        $responseDeclaration = $this->responseDeclarations[0];
        /* @var $mapEntry MapEntry */
        $this->totalScore = 0;
        foreach ($responseDeclaration->getMapping()->getMapEntries() as $mapEntry) {
            /** @var MapEntry $mapEntry */
            $this->totalScore += $mapEntry->getMappedValue();
            if ($mapEntry->getMapKey() instanceof DirectedPair) {
                $this->originalResponseData[] = [$mapEntry->getMapKey()->getFirst() . ' ' .
                $mapEntry->getMapKey()->getSecond() => $mapEntry->getMappedValue()];
            }
        }
    }

    protected function handleCC2MapResponseTemplate()
    {
        $this->handleMapResponseTemplate();
    }

    protected function prepareOriginalResponseData()
    {
        $responseValue = [];
        foreach ($this->originalResponseData as $originalResponse) {
            $answerIDStr = array_keys($originalResponse)[0];
            $answerIDList = explode(' ', $answerIDStr);

            if (count($answerIDList) !== 2) {
                $this->exceptions[] =
                    new MappingException("Answer {$answerIDStr} is not valid", MappingException::CRITICAL);
                continue;
            }

            $stemIdentifier = isset($this->stemsMapping[$answerIDList[0]]) ? $answerIDList[0] : $answerIDList[1];
            $optionIdentifier = isset($this->optionsMapping[$answerIDList[0]]) ? $answerIDList[0] : $answerIDList[1];

            $stemMapIndex = $this->stemsMapping[$stemIdentifier];

            if (!isset($responseValue[$stemMapIndex])) {
                $responseValue[$stemMapIndex] = [];
            }

            if (!$this->isMultipleResponse && count($responseValue[$stemMapIndex]) === 1) {
                $this->isMultipleResponse = true;
            }
            $responseValue[$stemMapIndex][] = $this->optionsMapping[$optionIdentifier];
        }
        ksort($responseValue);

        $responseList = [];
        $responseList[] = [
            'score' => $this->totalScore,
            'value' => $responseValue
        ];

        $this->originalResponseData = $responseList;
    }

    public function isMultipleResponse()
    {
        return $this->isMultipleResponse;
    }
}
