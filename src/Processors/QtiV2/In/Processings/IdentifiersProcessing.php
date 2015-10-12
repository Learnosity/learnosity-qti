<?php

namespace LearnosityQti\Processors\QtiV2\In\Processings;

use LearnosityQti\Entities\Item\item;
use LearnosityQti\Utils\UuidUtil;
use qtism\data\AssessmentItem;

class IdentifiersProcessing implements ProcessingInterface
{
    const USE_ASSESSMENT_ITEM_IDENTIFIER = 0;
    const RANDOMIZE_ITEM_REFERENCE = 1;

    private $mode;

    public function __construct()
    {
        $this->mode = self::USE_ASSESSMENT_ITEM_IDENTIFIER;
    }

    public function setIdentifierProcessingMode($mode)
    {
        $this->mode = $mode;
    }

    public function processAssessmentItem(AssessmentItem $assessmentItem)
    {
        // Randomize <assessmentItem>'s identifier, since it can later will be mapped to item reference
        // and also would be prepended as question reference
        if ($this->mode === self::RANDOMIZE_ITEM_REFERENCE) {
            $assessmentItem->setIdentifier(UuidUtil::generate());
            return $assessmentItem;
        } else {
            return $assessmentItem;
        }
    }

    public function processItemAndQuestions(item $item, array $questions)
    {
        return [$item, $questions];
    }
}
