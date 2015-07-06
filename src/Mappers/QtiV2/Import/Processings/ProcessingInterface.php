<?php

namespace Learnosity\Mappers\QtiV2\Import\Processings;

use Learnosity\Entities\Item\item;
use qtism\data\AssessmentItem;

interface ProcessingInterface
{
    public function processAssessmentItem(AssessmentItem $assessmentItem);
    public function processItemAndQuestions(item $item, array $questions);
    public function getExceptions();
}
