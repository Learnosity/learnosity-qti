<?php

namespace Learnosity\Processors\QtiV2\Out\QuestionTypes;

use Learnosity\Entities\BaseQuestionType;

abstract class AbstractQuestionTypeMapper
{
    abstract public function convert(BaseQuestionType $question, $identifier = 'RESPONSE');
}
