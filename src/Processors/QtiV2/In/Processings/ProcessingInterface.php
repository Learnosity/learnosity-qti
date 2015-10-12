<?php

namespace LearnosityQti\Processors\QtiV2\In\Processings;

use LearnosityQti\Entities\Item\item;
use qtism\data\AssessmentItem;

interface ProcessingInterface
{
    public function processAssessmentItem(AssessmentItem $assessmentItem);
    public function processItemAndQuestions(item $item, array $questions);
}
