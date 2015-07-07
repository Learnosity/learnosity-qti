<?php

namespace Learnosity\Processors\Learnosity\Out;

use Learnosity\Entities\Question;

class QuestionWriter
{
    public function __construct()
    {
    }

    public function convert(Question $question)
    {
        return $question->to_array();
    }
}
