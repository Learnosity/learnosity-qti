<?php

namespace LearnosityQti\Processors\Learnosity\In;

use LearnosityQti\Entities\BaseQuestionType;
use LearnosityQti\Entities\Feature;
use LearnosityQti\Processors\Learnosity\In\EntityBuilder;

class FeatureMapper
{
    public function parse(array $featureJson)
    {
        // TODO: Some validation to check all the required keys exists
        // TODO: Type and data should definitely exists
        // Map the `data` attribute
        $featureTypeClassName = 'LearnosityQti\Entities\QuestionTypes\\' . $featureJson['data']['type'];
        /** @var BaseQuestionType $questionType */
        $featureType = EntityBuilder::build($featureTypeClassName, $featureJson['data']);
        return new Feature($featureJson['data']['type'], $featureJson['reference'], $featureType, $featureJson['content']);
    }
}
