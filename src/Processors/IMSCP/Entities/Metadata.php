<?php

namespace LearnosityQti\Processors\IMSCP\Entities;

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
            $match = $this->match_string($pathPattern, $key);
            if ($match === true) {
                $matches[$key] = $value;
            }
        }
        $this->removeKeys(array_keys($matches));
        return $matches;
    }

    /**
     * http://stackoverflow.com/questions/5622085/match-string-with-asterisk
     */
    private function match_string($pattern, $str)
    {
        $pattern = preg_replace('/([^*])/e', 'preg_quote("$1", "/")', $pattern);
        $pattern = str_replace('*', '.*', $pattern);
        return (bool) preg_match('/^' . $pattern . '$/i', $str);
    }

    public function trimValues()
    {
        foreach ($this->flattenedMetadatas as $key => &$value)
        {
            // Clean up tabs, newlines and left/right spaces and so on...
            $value = trim(preg_replace('/\s+/', ' ', $value));
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
