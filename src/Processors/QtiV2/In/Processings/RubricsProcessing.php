<?php

namespace LearnosityQti\Processors\QtiV2\In\Processings;

use LearnosityQti\Entities\Item\item;
use LearnosityQti\Services\LogService;
use qtism\data\AssessmentItem;
use qtism\data\content\BlockCollection;
use qtism\data\content\ItemBody;
use qtism\data\content\RubricBlock;
use qtism\data\QtiComponent;

class RubricsProcessing implements ProcessingInterface
{
    public function processAssessmentItem(AssessmentItem $assessmentItem)
    {
        // TODO: Yea, we ignore rubric but what happen if the rubric is deep inside nested
        $newCollection = new BlockCollection();
        $itemBodyNew = new ItemBody();

        /** @var QtiComponent $component */
        foreach ($assessmentItem->getItemBody()->getContent() as $key => $component) {
            if (!($component instanceof RubricBlock)) {
                $newCollection->attach($component);
            } else {
                LogService::log('Does not support <rubricBlock>. Ignoring <rubricBlock>');
            }
        }
        $itemBodyNew->setContent($newCollection);
        $assessmentItem->setItemBody($itemBodyNew);
        return $assessmentItem;
    }

    public function processItemAndQuestions(item $item, array $questions)
    {
        return [$item, $questions];
    }
}
