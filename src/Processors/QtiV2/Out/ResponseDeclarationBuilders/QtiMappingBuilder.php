<?php

namespace LearnosityQti\Processors\QtiV2\Out\ResponseDeclarationBuilders;

use LearnosityQti\Exceptions\MappingException;
use qtism\data\state\MapEntry;
use qtism\data\state\MapEntryCollection;
use qtism\data\state\Mapping;

class QtiMappingBuilder
{
    // TODO: Should remove support for alt_responses
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
        $mapping = new Mapping($mapEntryCollection);
        $mapping->setLowerBound(0.0);
        $mapping->setUpperBound($this->getUpperBound($validation));
        $mapping->setDefaultValue(0.0);
        return $mapping;
    }

    public function build($validation)
    {
        $mapping = new Mapping($this->buildMapEntryCollection($validation));
        $mapping->setLowerBound(0.0);
        $mapping->setUpperBound($this->getUpperBound($validation));
        $mapping->setDefaultValue(0.0);
        return $mapping;
    }

    private function getUpperBound($validation)
    {
        $scores = [];

        // Grab score from `valid_response`
        $scores[] = floatval($validation->get_valid_response()->get_score());

        // Also, grab scores `alt_responses`
        if (count($validation->get_alt_responses()) > 0) {
            foreach ($validation->get_alt_responses() as $alt) {
                $scores[] = floatval($alt->get_score());
             }
        }

        // Return the max value
        return floatval(max($scores));
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
                // TODO: the score shall be divided to total score on multiple cardinality
                $mapEntries[] = new MapEntry($value, floatval($response->get_score()));
            }
        } else {
            $mapEntries[] = new MapEntry($response->get_value(), floatval($response->get_score()));
        }
        return $mapEntries;
    }
}
