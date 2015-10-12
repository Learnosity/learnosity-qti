<?php

namespace LearnosityQti\Processors\Learnosity\Out;

use LearnosityQti\Entities\Question;

class QuestionWriter
{
    public function convert(Question $question)
    {
        return $question->to_array();
    }
}
