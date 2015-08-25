<?php

namespace Learnosity\Processors\QtiV2\Out\Validation;

use Learnosity\Exceptions\MappingException;
use qtism\data\state\CorrectResponse;
use qtism\data\state\Value;
use qtism\data\state\ValueCollection;

class QtiCorrectResponseBuilder
{
    public function buildWithBaseTypeIdentifier($validation, $valueIdentifierMap)
    {
        $valueCollection = $this->buildValueCollection($validation);
        foreach ($valueCollection as $value) {
            /** @var Value $value */
            if (isset($valueIdentifierMap[$value->getValue()])) {
                $value->setValue($valueIdentifierMap[$value->getValue()]);
            } else {
                throw new MappingException('Found `value` without matching `identifier`');
            }
        }
        return new CorrectResponse($valueCollection);
    }

    public function build($validation)
    {
        return new CorrectResponse($this->buildValueCollection($validation));
    }

    private function buildValueCollection($validation)
    {
        // Handle `valid_response`
        $values = $this->buildValues($validation->get_valid_response()->get_value());

        // Handle `alt_responses`
        if (count($validation->get_alt_responses()) >= 1) {
            foreach ($validation->get_alt_responses() as $alt) {
                $values = array_merge($values, $this->buildValues($alt->get_value()));
            }
        }
        $valueCollection = new ValueCollection();
        foreach ($values as $value) {
            /** @var Value $value */
            $valueCollection->attach($value);
        }
        return $valueCollection;
    }

    /**
     * Build an array of QTI Value object given Learnosity's validation `values` which can be array, string, or whatever
     */
    private function buildValues($learnosityValues)
    {
        $values = [];
        if (is_array($learnosityValues)) {
            foreach ($learnosityValues as $value) {
                $values[] = new Value($value);
            }
        } else {
            $values[] = new Value($learnosityValues);
        }
        return $values;
    }
}
