<?php

namespace Learnosity\Processors\QtiV2\Out\QuestionTypes;

use Learnosity\Entities\BaseQuestionType;
use Learnosity\Entities\QuestionTypes\mcq;
use Learnosity\Entities\QuestionTypes\mcq_options_item;
use Learnosity\Processors\QtiV2\In\Utils\QtiComponentUtil;
use Learnosity\Services\LogService;
use qtism\data\content\Block;
use qtism\data\content\BlockCollection;
use qtism\data\content\FlowCollection;
use qtism\data\content\FlowStaticCollection;
use qtism\data\content\interactions\ChoiceInteraction;
use qtism\data\content\interactions\SimpleChoice;
use qtism\data\content\interactions\SimpleChoiceCollection;
use qtism\data\content\ItemBody;
use qtism\data\content\xhtml\text\Div;

class McqMapper extends AbstractQuestionTypeMapper
{
    public function convert(BaseQuestionType $questionType, $interactionIdentifier = 'RESPONSE')
    {
        /** @var mcq $question */
        $question = $questionType;
        $contentCollection = new BlockCollection();

        // Build stimulus
        $contentCollection->attach($this->convertStimulus($question->get_stimulus()));

        // Build <choiceInteraction>
        $simpleChoiceCollection = new SimpleChoiceCollection();
        foreach ($question->get_options() as $option) {
            /** @var mcq_options_item $option */

            $choiceContent = new FlowStaticCollection();
            foreach (QtiComponentUtil::unmarshallElement($option->get_label()) as $component) {
                $choiceContent->attach($component);
            }

            $choice = new SimpleChoice('CHOICE_' . strval($option->get_value()));
            $choice->setContent($choiceContent);
            $simpleChoiceCollection->attach($choice);
        }
        $contentCollection->attach(new ChoiceInteraction($interactionIdentifier, $simpleChoiceCollection));

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

        // Check whether the content could all be attached as is
        $areBlockComponents = array_reduce($stimulusComponents->getArrayCopy(), function ($areBlockComponents, $component) {
            return $areBlockComponents && $component instanceof Block;
        }, true);
        if ($areBlockComponents) {
            return $stimulusComponents;
        }

        // Otherwise, build a `div` wrapper around it
        // This is a workaround for QTI spec restriction of <itemBody> which only allows Block objects
        LogService::log('Stimulus content would be wrapped in a <div> to workaround QTI spec restriction of <itemBody> which only allows a collection of Block objects');
        $divCollection = new FlowCollection();
        foreach ($stimulusComponents as $component) {
            $divCollection->attach($component);
        }
        $div = new Div();
        $div->setContent($divCollection);
        return $div;
    }
}
