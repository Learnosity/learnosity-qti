<?php

namespace Learnosity\Processors\QtiV2\In\Validation;

use Learnosity\Exceptions\MappingException;
use Learnosity\Processors\Learnosity\In\ValidationBuilder;
use Learnosity\Processors\QtiV2\In\ResponseProcessingTemplate;
use Learnosity\Utils\ArrayUtil;
use qtism\common\datatypes\DirectedPair;
use qtism\data\state\MapEntry;
use qtism\data\state\Mapping;
use qtism\data\state\ResponseDeclaration;

abstract class BaseGapMatchInteractionValidationBuilder extends BaseQtiValidationBuilder
{
    private $isDuplicatedResponse = false;
    private $gapIdentifiers;
    private $possibleResponses;

    abstract public function getValidationClassName();

    public function init(array $gapIdentifiers, array $possibleResponses)
    {
        $this->gapIdentifiers = $gapIdentifiers;
        $this->possibleResponses = $possibleResponses;
        $this->scoringType = 'exactMatch';
    }

    public function isDuplicatedResponse()
    {
        return $this->isDuplicatedResponse;
    }

    protected function handleMatchCorrectTemplate()
    {
        assert(count($this->responseDeclarations)===1);
        /** @var ResponseDeclaration $responseDeclaration */
        $responseDeclaration = $this->responseDeclarations[0];
        $gapIdentifiersIndexMap = array_flip($this->gapIdentifiers);
        $validResponses = [];
        $responseIndexSet = [];
        foreach ($responseDeclaration->getCorrectResponse()->getValues() as $value) {
            /** @var DirectedPair $valuePair */
            $valuePair = $value->getValue();
            $responseValue = $this->possibleResponses[$valuePair->getFirst()];
            $responseIndex = $gapIdentifiersIndexMap[$valuePair->getSecond()];
            if (!$this->isDuplicatedResponse) {
                if (!isset($responseIndexSet[$responseIndex])) {
                    $responseIndexSet[$responseIndex] = true;
                } else {
                    $this->isDuplicatedResponse = true;
                }
            }
            // Build valid response array in the correct order matching the `gap` elements
            $validResponses[$responseIndex][] = $responseValue;
        }
        if (count($this->gapIdentifiers) !== count($validResponses)) {
            $this->exceptions[] =
                new MappingException(
                    'Amount of Gap Identifiers ' . count($this->gapIdentifiers) . ' does not match the amount ' .
                    count($validResponses) . ' for responseDeclaration',
                    MappingException::CRITICAL
                );
            return;
        }

        ksort($validResponses);
        $combinationValidResponse = ArrayUtil::mutateResponses($validResponses);

        $responseList = [];
        foreach ($combinationValidResponse as $resp) {
            $responseList[] = [
                'score' => 1,
                'value' => $resp
            ];
        }
        $this->originalResponseData = $responseList;
    }

    protected function handleMapResponseTemplate()
    {
        assert(count($this->responseDeclarations)===1);
        /** @var ResponseDeclaration $responseDeclaration */
        $responseDeclaration = $this->responseDeclarations[0];
        $this->isDuplicatedResponse = false;
        $responseIndexSet = [];
        $mapEntries = $responseDeclaration->getMapping()->getMapEntries();
        $gapMapping = [];
        /** @var MapEntry $mapEntry */
        foreach ($mapEntries as $mapEntry) {
            $mapKey = $mapEntry->getMapKey();
            if ($mapKey instanceof DirectedPair) {
                if (isset($this->possibleResponses[$mapKey->getFirst()])) {
                    $responseIndex = $mapKey->getFirst();
                    $gapIndex = $mapKey->getSecond();
                } else {
                    $responseIndex = $mapKey->getSecond();
                    $gapIndex = $mapKey->getFirst();
                }
                if (!isset($gapMapping[$gapIndex])) {
                    $gapMapping[$gapIndex] = [];
                }
                if (!$this->isDuplicatedResponse) {
                    if (!isset($responseIndexSet[$responseIndex])) {
                        $responseIndexSet[$responseIndex] = true;
                    } else {
                        $this->isDuplicatedResponse = true;
                    }
                }
                // wrap the identifier=>score into an array as the identifier can be duplicated (Duplicated Response)
                $gapMapping[$gapIndex][] = [[$responseIndex => $mapEntry->getMappedValue()]];
            }
        }
        $responseValue = [];
        foreach ($this->gapIdentifiers as $key => $value) {
            if (!isset($gapMapping[$value])) {
                $this->exceptions[] = new MappingException(
                    'Gap Identifier ' . $value . ' does not exist',
                    MappingException::CRITICAL
                );
                return;
            }
            $responseValue[$key] = $gapMapping[$value];
        }
        if (count($responseValue) === 0) {
            return;
        }
        $responseValue = ArrayUtil::mutateResponses($responseValue);
        // we make sure the first item is having the highest score
        usort($responseValue, function ($a, $b) {
            return array_sum(ArrayUtil::arrayValsMulti($a)) < array_sum(ArrayUtil::arrayValsMulti($b));
        });

        $responseList = [];
        foreach ($responseValue as $resp) {
            $responseIDList = ArrayUtil::arrayKeysMulti($resp);
            $responseList[] = [
                'score' => array_sum(ArrayUtil::arrayValsMulti($resp)),
                'value' => $this->getGapValueList($responseIDList, $this->possibleResponses)
            ];
        }
        $this->originalResponseData = $responseList;
    }

    protected function handleCC2MapResponseTemplate()
    {
        $this->handleMapResponseTemplate();
    }

    protected function prepareOriginalResponseData()
    {
        // no operation required as originalResponseData has been processed separately
    }

    private function getGapValueList(array $gapKeys, array $possibleResponses)
    {
        $gapValue = [];
        foreach ($gapKeys as $gapKey) {
            if (isset($possibleResponses[$gapKey])) {
                $gapValue[] = $possibleResponses[$gapKey];
            }
        }
        return $gapValue;
    }
}
