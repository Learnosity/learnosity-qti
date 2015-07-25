<?php

namespace Learnosity\Processors\QtiV2\Out\QuestionTypes;

use Learnosity\Entities\QuestionTypes\mcq;
use Learnosity\Processors\QtiV2\In\Utils\QtiComponentUtil;
use qtism\data\content\BlockCollection;
use qtism\data\content\FlowCollection;
use qtism\data\content\ItemBody;
use qtism\data\content\xhtml\text\Div;

class McqMapper
{
    public function convert(mcq $mcq)
    {
        $contentCollection = new BlockCollection();
        $contentCollection->attach($this->convertStimulus($mcq->get_stimulus()));

        $itemBody = new ItemBody();
        $itemBody->setContent($contentCollection);

        return [$itemBody, null, null];
    }

    private function convertStimulus($stimulusString)
    {
        $stimulusComponents = QtiComponentUtil::unmarshallElement($stimulusString);

        // If stimulus already wrapped within a single `div` then return simply it
        if ($stimulusComponents->count() === 1 && $stimulusComponents->getArrayCopy()[0] instanceof Div) {
            return $stimulusComponents->getArrayCopy()[0];
        }

        // Otherwise, build a `div` wrapper around it
        // This is a workaround for QTI spec restriction of <itemBody> which only allows Block objects
        $divCollection = new FlowCollection();
        foreach ($stimulusComponents as $component) {
            $divCollection->attach($component);
        }
        $div = new Div();
        $div->setContent($divCollection);
        return $div;
    }
}
