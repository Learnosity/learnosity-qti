<?php

namespace LearnosityQti\Processors\IMSCP\Entities;

use LearnosityQti\Utils\StringUtil;

class Metadata
{
    private $flattenedMetadatas = [];

    /**
     * SL: Can make a real tree when I have time or necessary ~~~
     * For now, lets just do flat string matching
     */
    public function __construct(array $metadatas)
    {
        //$this->metadatas = $metadatas;
        $this->flattenedMetadatas = $this->array_flat($metadatas);
    }

    public function matchAndRemoveByKey($pathPattern)
    {
        $matches = [];
        foreach ($this->flattenedMetadatas as $key => $value)
        {
            list($valueMatched, $valueMatches) = StringUtil::matchString($pathPattern, $key);
            if ($valueMatched === true) {
                $matches[$key] = [
                    'value' => $value,
                    'valueMatched' => $valueMatched,
                    'valueMatches' => $valueMatches
                ];
            }
        }
        $this->removeKeys(array_keys($matches));
        return $matches;
    }

    public function trimValues()
    {
        foreach ($this->flattenedMetadatas as $key => &$value)
        {
            // Clean up tabs, newlines and left/right spaces and so on...
            $value = trim(preg_replace('/\s+/', ' ', $value));

            // Trim to 240 chars
            $value = substr($value, 0, 100);
        }
    }

    public function isEmpty()
    {
        return count($this->flattenedMetadatas) <= 0;
    }

    public function removeKeys(array $keys)
    {
        $this->flattenedMetadatas = array_diff_key($this->flattenedMetadatas, array_flip($keys));
        return $this->flattenedMetadatas;
    }

    public function getFlattenedMetadata()
    {
        return $this->flattenedMetadatas;
    }

    /**
     * http://stackoverflow.com/questions/9546181/flatten-multidimensional-array-concatenating-keys
     */
    private function array_flat($array, $prefix = '')
    {
        $result = [];
        foreach ($array as $key => $value) {
            if (is_numeric($key)) {
                $new_key = $prefix . "[$key]";
            } else {
                $new_key = $prefix . (empty($prefix) ? '' : '.') . $key;
            }
            $value = empty($value) ? '' : $value;
            if (is_array($value)) {
                $result = array_merge($result, $this->array_flat($value, $new_key));
            } else {
                $result[$new_key] = $value;
            }
        }
        return $result;
    }
}
