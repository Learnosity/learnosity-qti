<?php

namespace Learnosity\Processors\QtiV2\In\Processings;

use Learnosity\Entities\Item\item;
use qtism\data\AssessmentItem;

interface ProcessingInterface
{
    public function processAssessmentItem(AssessmentItem $assessmentItem);
    public function processItemAndQuestions(item $item, array $questions);
}
