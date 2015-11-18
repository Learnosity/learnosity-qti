<?php

namespace LearnosityQti\Processors\IMSCP\Out\MetadataEngines;

use LearnosityQti\Exceptions\MappingException;
use LearnosityQti\Processors\IMSCP\Entities\Metadata;

class SimpleMetadataEngine
{
    /**
     * `Simple` mapping rules assume that { "patternPath" : "tagType:tagName" }
     */
    public function mapTagsSimple(Metadata $metadata, $inputRules)
    {
        $rules = $this->buildAndValidateRules($inputRules);
        $convertedTags = [];

        foreach ($rules as $pathPattern => $rule) {
            $matches = $metadata->matchAndRemoveByKey($pathPattern);
            foreach ($matches as $key => $value) {
                $value = $value['value'];
                // Yap this guy got matched so let's fix convert it to tags
                $tagTypeRule = $rule['tagTypeRule'];
                $tagNameRule = $rule['tagNameRule'];

                // Build the tag name by replacing '{{value}}' at the rule with the actual value
                $tagName = str_replace('{{value}}', $value, $tagNameRule);
                // And simply match use the tag type rule as dah` tag type ~ simple :)
                $tagType = $tagTypeRule;

                // Let's save it!
                $convertedTags[$tagType][] = $tagName;
                $touchedFlatTagsKey[] = $key;
            }
        }
        return [$convertedTags, $metadata];
    }

    private function buildAndValidateRules(array $inputRules)
    {
        try {
            // Massage and validate the rules
            // TODO: Need validation and nice error messages
            $rules = [];
            foreach ($inputRules as $path => $rule) {
                $exploded = explode(":", $rule, 2);
                $tagTypeRule = $exploded[0];
                $tagNameRule = $exploded[1];
                $rules[$path] = [
                    'tagTypeRule' => $tagTypeRule,
                    'tagNameRule' => $tagNameRule,
                ];
            }
            return $rules;
        } catch (\Exception $e) {
            // TODO: Need to improve this!
            throw new MappingException('Invalid `simple` tag mapping rules');
        }
    }
}
