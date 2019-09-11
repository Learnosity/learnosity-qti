<?php

namespace LearnosityQti\Processors\QtiV2\In\Interactions;

use LearnosityQti\Entities\QuestionTypes\orderlist;
use LearnosityQti\Utils\QtiMarshallerUtil;
use LearnosityQti\Processors\QtiV2\In\Validation\OrderInteractionValidationBuilder;
use LearnosityQti\Services\LogService;
use qtism\data\content\interactions\OrderInteraction as QtiOrderInteraction;
use qtism\data\content\interactions\SimpleChoice;

class OrderInteractionMapper extends AbstractInteractionMapper
{
    private $orderMapping;

    public function getQuestionType()
    {
        /* @var QtiOrderInteraction $interaction */
        $interaction = $this->interaction;
        if (!$this->validate($interaction)) {
            return null;
        }

        $list = [];
        $this->orderMapping = [];
        /** @var SimpleChoice $simpleChoice */
        foreach ($interaction->getSimpleChoices() as $simpleChoice) {
            $this->orderMapping[$simpleChoice->getIdentifier()] = count($this->orderMapping);
            $choiceContent = QtiMarshallerUtil::marshallCollection($simpleChoice->getContent());
            $listContent = trim(str_replace("\n", "", $choiceContent));
            $list[] = $listContent;
        }

        $question = new orderlist('orderlist', $list);
        $question->set_stimulus($this->getPrompt());

        // Build `validation` object
        $validation = $this->buildValidation();
        if ($validation) {
            $question->set_validation($validation);
        }
        return $question;
    }

    private function validate(QtiOrderInteraction $interaction)
    {
        if ($interaction->mustShuffle()) {
            LogService::log('Attribute `shuffle` is not supported');
        }
        foreach ($interaction->getSimpleChoices() as $simpleChoice) {
            /** @var SimpleChoice $simpleChoice */
            if ($simpleChoice->isFixed()) {
                LogService::log('Attribute `fixed` for ' . $simpleChoice->getIdentifier() . ' is not supported');
            }
        }
        return true;
    }

    private function buildValidation()
    {
        $validationBuilder = new OrderInteractionValidationBuilder(
            $this->orderMapping,
            $this->responseDeclaration
        );
        return $validationBuilder->buildValidation($this->responseProcessingTemplate);
    }
}
