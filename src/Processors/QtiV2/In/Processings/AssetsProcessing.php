<?php

namespace LearnosityQti\Processors\QtiV2\In\Processings;

use LearnosityQti\Entities\Item\item;
use qtism\data\AssessmentItem;
use qtism\data\content\xhtml\Img;
use qtism\data\content\xhtml\ObjectElement;

class AssetsProcessing implements ProcessingInterface
{
    private $baseAssetUrl = '';

    public function setBaseAssetUrl($baseAssetUrl)
    {
        $this->baseAssetUrl = $baseAssetUrl;
    }

    public function processAssessmentItem(AssessmentItem $assessmentItem)
    {
        foreach ($assessmentItem->getIterator() as $component) {
            if ($component instanceof Object) {
                /** @var Object $component */
                if ($this->isInternalUrl($component->getData())) {
                    $component->setData($this->baseAssetUrl . $component->getData());
                }
            } elseif ($component instanceof Img) {
                /** @var Img $component */
                if ($this->isInternalUrl($component->getSrc())) {
                    $component->setSrc($this->baseAssetUrl . basename($component->getSrc()));
                }
            }
        }
        return $assessmentItem;
    }

    private function isInternalUrl($string)
    {
        // Simple check if protocol present
        return !preg_match('#^(ht|f)tps?://#', $string);
    }

    public function processItemAndQuestions(item $item, array $questions)
    {
        return [$item, $questions];
    }
}
