<?php

namespace Learnosity\Mappers\Learnosity\Import;

use Learnosity\Entities\BaseQuestionType;
use Learnosity\Entities\Question;

class QuestionMapper
{
    public function parse(array $questionJson)
    {
        // TODO: Some validation to check all the required keys exists
        // TODO: Type and data should definitely exists
        // Map the `data` attribute
        $questionTypeClassName = 'Learnosity\Entities\QuestionTypes\\' . $questionJson['type'];

        /** @var BaseQuestionType $questionType */
        $questionType = EntityBuilder::build($questionTypeClassName, $questionJson['data']);
        return new Question($questionJson['type'], $questionJson['reference'], $questionType);
    }
}