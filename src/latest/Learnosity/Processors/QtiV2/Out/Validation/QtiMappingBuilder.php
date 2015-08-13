<?php

namespace Learnosity\Processors\QtiV2\Out\Validation;

use Learnosity\Exceptions\MappingException;
use qtism\data\state\MapEntry;
use qtism\data\state\MapEntryCollection;
use qtism\data\state\Mapping;

class QtiMappingBuilder
{
    public function buildWithBaseTypeIdentifier($validation, array $valueIdentifierMap)
    {
        $mapEntryCollection = $this->buildMapEntryCollection($validation);
        foreach ($mapEntryCollection as $mapEntry) {
            /** @var MapEntry $mapEntry */
            if (isset($valueIdentifierMap[$mapEntry->getMapKey()])) {
                $mapEntry->setMapKey($valueIdentifierMap[$mapEntry->getMapKey()]);
            } else {
                throw new MappingException('Found `value` without matching `identifier`');
            }
        }
        return new Mapping($mapEntryCollection);
    }

    public function build($validation)
    {
        return new Mapping($this->buildMapEntryCollection($validation));
    }

    private function buildMapEntryCollection($validation)
    {
        // Handle `valid_response`
        $mapEntries = $this->buildMapEntries($validation->get_valid_response());

        // Handle `alt_responses`
        if (count($validation->get_alt_responses()) > 0) {
            foreach ($validation->get_alt_responses() as $alt) {
                $mapEntries = array_merge($mapEntries, $this->buildMapEntries($alt));
            }
        }
        $mapEntryCollection = new MapEntryCollection();
        foreach ($mapEntries as $mapEntry) {
            $mapEntryCollection->attach($mapEntry);
        }
        return $mapEntryCollection;
    }


    /**
     * Build an array of QTI MapEntry object given Learnosity's `valid/alt_response` in which its `values`
     * which can be array, string, or whatever
     */
    private function buildMapEntries($response)
    {
        $mapEntries = [];
        if (is_array($response->get_value())) {
            foreach ($response->get_value() as $value) {
                $mapEntries[] = new MapEntry($value, floatval($response->get_score()));
            }
        } else {
            $mapEntries[] = new MapEntry($response->get_value(), floatval($response->get_score()));
        }
        return $mapEntries;
    }
}
