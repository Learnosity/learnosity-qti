<?php

namespace LearnosityQti\Processors\IMSCP\Out\MetadataEngines;

use LearnosityQti\Processors\IMSCP\Entities\Metadata;
use LearnosityQti\Utils\StringUtil;

class ValueMapEngine
{
    public function mapValueMap(Metadata $metadata, array $rules = [])
    {
        $rules = $this->validateRules($rules);

        $convertedTags = [];
        foreach ($rules as $patternPath => $rule) {
            $parameters = $rule['parameters'];
            $tagTypeRule = $rule['tagType'];
            $tagNameRule = $rule['tagName'];

            $matches = $metadata->matchAndRemoveByKey($patternPath);
            if (!empty($matches)) {
                $matchesGroups = $this->groupMatches($matches);
                foreach ($matchesGroups as $matchedGroup) {
                    $matchedParameters = $this->matchParameters($parameters, $matchedGroup);
                    // Now build tag type
                    $tagType = $this->replaceParameters($matchedParameters, $tagTypeRule);
                    // Then build tagname
                    $tagName = $this->replaceParameters($matchedParameters, $tagNameRule);
                    // Then the whole tags
                    $convertedTags[$tagType][] = $tagName;
                }
            }
        }
        return [$convertedTags, $metadata];
    }

    private function replaceParameters(array $matchedParameters, $string)
    {
        foreach ($matchedParameters as $paramKey => $paramValue) {
            $string = str_replace('{{' . $paramKey . '}}', $paramValue, $string);
        }
        return $string;
    }

    private function matchParameters(array $parameters, array $matchedGroup)
    {
        $matchedParameters = [];
        foreach ($parameters as $param => $paramPath) {
            foreach ($matchedGroup as $group) {
                $endString = $group['endString'];
                $value = $group['value'];

                // Only grab the first match!
                list ($matched, $parammatches) = StringUtil::matchString($paramPath, $endString);
                if ($matched === true) {
                    $matchedParameters[$param] = $value;
                }
            }
        }
        return $matchedParameters;
    }

    private function groupMatches(array $matches)
    {
        $matchesGroups = [];
        foreach ($matches as $path => $match) {
            $value = $match['value'];
            $valueMatches = $match['valueMatches'];
            $endMatchedString = $valueMatches[count($valueMatches) - 1][0];
            $startMatchedString = $this->rightTrim($path, $endMatchedString);
            $startMatchedString = chop($startMatchedString, '.'); // TODO: Why is this? Investigate
            $matchesGroups[$startMatchedString][] = [
                'endString' => $endMatchedString,
                'value' => $value
            ];
        }
        return $matchesGroups;
    }

    private function rightTrim($string, $substring)
    {
        if (substr($string, -strlen($substring)) === $substring) {
            return substr($string, 0, strlen($string) - strlen($substring));
        }
        return $string;
    }

    private function validateRules(array $rules)
    {
        // TODO: Need to do this!
        return $rules;
    }
}
