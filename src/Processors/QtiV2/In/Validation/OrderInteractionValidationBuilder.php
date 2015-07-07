<?php
namespace Learnosity\Processors\QtiV2\In\Validation;

use Learnosity\Exceptions\MappingException;
use qtism\data\state\ResponseDeclaration;
use qtism\data\state\Value;

class OrderInteractionValidationBuilder extends BaseQtiValidationBuilder
{
    private $orderMapping;

    public function init(array $orderMapping)
    {
        $this->orderMapping = $orderMapping;
        $this->scoringType = 'exactMatch';
    }

    protected function handleMatchCorrectTemplate()
    {
        assert(count($this->responseDeclarations) === 1);
        /** @var ResponseDeclaration $responseDeclaration */
        $responseDeclaration = $this->responseDeclarations[0];
        foreach ($responseDeclaration->getCorrectResponse()->getValues() as $value) {
            if ($value instanceof Value) {
                $this->originalResponseData[$value->getValue()] = count($this->originalResponseData);
            }
        }
    }

    protected function handleMapResponseTemplate()
    {
        $this->exceptions[] =
            new MappingException('Unrecognised response processing template. Validation is not available');
    }

    protected function handleCC2MapResponseTemplate()
    {
        $this->handleMapResponseTemplate();
    }

    protected function prepareOriginalResponseData()
    {
        $responseValue = [];

        foreach ($this->orderMapping as $mappingIdentifier => $index) {
            if (!isset($this->originalResponseData[$mappingIdentifier])) {
                $this->exceptions [] = new MappingException(
                    'Cannot locate ' . $mappingIdentifier . ' in responseDeclaration'
                );
                continue;
            }
            $answerIndex = $this->originalResponseData[$mappingIdentifier];
            $responseValue[$answerIndex] = $index;
        }
        ksort($responseValue);

        $responseList = [];
        $responseList[] = [
            'score' => 1,
            'value' => $responseValue
        ];

        $this->originalResponseData = $responseList;
    }
}
