<?php

namespace Learnosity\Processors\Learnosity\In\ValidationBuilder;

class ValidResponse
{
    private $value;
    private $score;

    public function __construct($score, array $value = [])
    {
        $this->score = floatval($score);
        $this->value = $value;
    }

    public function getScore()
    {
        return $this->score;
    }

    public function addValue($value)
    {
        // TODO: Not sure about this one, added check here because no case
        // TODO: that I have encountered has array of array at the validation value
        if (is_array($value)) {
            throw new \Exception('Valid response value item shall not be an array');
        }
        $this->value[] = $value;
    }

    public function getValue()
    {
        return $this->value;
    }
}
