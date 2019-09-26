<?php

namespace LearnosityQti\Processors\Learnosity\In;

use LearnosityQti\Entities\BaseQuestionType;
use LearnosityQti\Entities\Question;
use LearnosityQti\Utils\UuidUtil;

class QuestionMapper
{
    public function parse(array $questionJson)
    {
        // TODO: Some validation to check all the required keys exists
        // TODO: Type and data should definitely exists
        // Map the `data` attribute
        $questionTypeClassName = 'LearnosityQti\Entities\QuestionTypes\\' . $questionJson['data']['type'];

        /** @var BaseQuestionType $questionType */
        $questionType = EntityBuilder::build($questionTypeClassName, $questionJson['data']);
        if (isset($questionJson['feature'])) {
            $feature = $questionJson['feature'];
        } else {
            $feature = '';
        }
        return new Question($questionJson['data']['type'], $questionJson['reference'], $questionType, $questionJson['itemreference'], $questionJson['content'], $feature);
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
