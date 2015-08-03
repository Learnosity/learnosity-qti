<?php

namespace Learnosity\Processors\QtiV2\Out\QuestionTypes;

use Learnosity\Entities\BaseQuestionType;
use Learnosity\Utils\QtiMarshallerUtil;
use Learnosity\Services\LogService;
use qtism\data\content\Block;
use qtism\data\content\FlowCollection;
use qtism\data\content\xhtml\text\Div;
use qtism\data\QtiComponentCollection;

abstract class AbstractQuestionTypeMapper
{
    abstract public function convert(BaseQuestionType $questionType, $interactionIdentifier, $interactionLabel);

    protected function convertStimulus($stimulusString)
    {
        $stimulusComponents = QtiMarshallerUtil::unmarshallElement($stimulusString);

        // Check whether the content could all be attached as is
        $areBlockComponents = array_reduce($stimulusComponents->getArrayCopy(), function ($initial, $component) {
            return $initial && $component instanceof Block;
        }, true);
        if ($areBlockComponents) {
            return $stimulusComponents;
        }

        // Otherwise, build a `div` wrapper around it
        // This is a workaround for QTI spec restriction of <itemBody> which only allows Block objects
        // TODO: Used to be itembody and now shall be just flow!
        LogService::log("Stimulus content would be wrapped in a `div` to workaround QTI spec restriction of `itemBody` which only allows a collection of Block objects");
        $divCollection = new FlowCollection();
        foreach ($stimulusComponents as $component) {
            $divCollection->attach($component);
        }
        $div = new Div();
        $div->setContent($divCollection);
        $collection = new QtiComponentCollection();
        $collection->attach($div);
        return $collection;
    }
}
