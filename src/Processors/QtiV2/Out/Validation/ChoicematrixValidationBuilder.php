<?php

namespace Learnosity\Processors\QtiV2\Out\Validation;

use Learnosity\Exceptions\MappingException;
use qtism\common\datatypes\DirectedPair;
use qtism\common\enums\BaseType;
use qtism\common\enums\Cardinality;
use qtism\data\state\CorrectResponse;
use qtism\data\state\ResponseDeclaration;
use qtism\data\state\Value;
use qtism\data\state\ValueCollection;

class ChoicematrixValidationBuilder extends AbstractQuestionValidationBuilder
{
    private $stemIndexIdentifierMap = [];
    private $optionIndexIdentifierMap = [];

    public function __construct(array $stemIndexIdentifierMap, array $optionIndexIdentifierMap)
    {
        $this->stemIndexIdentifierMap = $stemIndexIdentifierMap;
        $this->optionIndexIdentifierMap = $optionIndexIdentifierMap;
    }

    protected function buildResponseDeclaration($responseIdentifier, $validation)
    {
        $responseDeclaration = new ResponseDeclaration($responseIdentifier);
        $responseDeclaration->setCardinality(Cardinality::MULTIPLE);
        $responseDeclaration->setBaseType(BaseType::DIRECTED_PAIR);

        $score = floatval($validation->get_valid_response()->get_score());
        $value = $validation->get_valid_response()->get_value();

        // The validation in `choicematrix` has to be an array
        if (!is_array($value)) {
            throw new MappingException('Broken validation object. Response declaration mapping failed');
        }

        $responseDeclaration->setCorrectResponse(new CorrectResponse($this->buildValueCollection($value)));
        return $responseDeclaration;
    }

    private function buildValueCollection(array $learnosityValues)
    {
        // The validation in `choicematrix` relies on its key to describe the index of stem/option pair
        $valueCollection = new ValueCollection();
        foreach ($this->buildDirectedPairs($learnosityValues) as $pair) {
            $valueCollection->attach(new Value($pair));
        }
        return $valueCollection;
    }

    private function buildDirectedPairs(array $values)
    {
        $pairs = [];
        foreach ($values as $key => $value) {
            if (is_array($value)) {
                foreach ($value as $v) {
                    $stemIdentifier = $this->stemIndexIdentifierMap[$key];
                    $optionIdentifier = $this->optionIndexIdentifierMap[intval($v)];
                    $pairs[] = new DirectedPair($stemIdentifier, $optionIdentifier);
                }
            } else {
                $stemIdentifier = $this->stemIndexIdentifierMap[$key];
                $optionIdentifier = $this->optionIndexIdentifierMap[intval($value)];
                $pairs[] = new DirectedPair($stemIdentifier, $optionIdentifier);
            }
        }
        return $pairs;
    }
}
