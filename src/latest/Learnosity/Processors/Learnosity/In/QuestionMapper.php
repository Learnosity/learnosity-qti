<?php

namespace Learnosity\Processors\Learnosity\In;

use Learnosity\Entities\BaseQuestionType;
use Learnosity\Entities\Question;
use Learnosity\Utils\UuidUtil;

class QuestionMapper
{
    public function parse(array $questionJson)
    {
        // TODO: Some validation to check all the required keys exists
        // TODO: Type and data should definitely exists
        // Map the `data` attribute
        $questionTypeClassName = 'Learnosity\Entities\QuestionTypes\\' . $questionJson['data']['type'];

        /** @var BaseQuestionType $questionType */
        $questionType = EntityBuilder::build($questionTypeClassName, $questionJson['data']);
        return new Question($questionJson['data']['type'], $questionJson['reference'], $questionType);
    }

    public function parseDataOnly(array $questionDataJson)
    {
        $questionJson = [
            'reference' => UuidUtil::generate(),
            'data' => $questionDataJson
        ];
        return $this->parse($questionJson);
    }
}
