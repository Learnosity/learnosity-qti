<?php

namespace Learnosity\Processors\QtiV2\In\Interactions;

use Learnosity\Entities\QuestionTypes\orderlist;
use Learnosity\Entities\QuestionTypes\orderlist_validation;
use Learnosity\Entities\QuestionTypes\orderlist_validation_valid_response;
use Learnosity\Exceptions\MappingException;
use Learnosity\Processors\QtiV2\In\ResponseProcessingTemplate;
use Learnosity\Processors\QtiV2\In\Utils\QtiComponentUtil;
use Learnosity\Processors\QtiV2\In\Validation\OrderInteractionValidationBuilder;
use qtism\data\content\interactions\OrderInteraction as QtiOrderInteraction;
use qtism\data\content\interactions\SimpleChoice;
use qtism\data\state\Value;

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
            $list[] = QtiComponentUtil::marshallCollection($simpleChoice->getContent());
        }

        $validation = $this->buildValidation();

        $question = new orderlist('orderlist', $list);
        $question->set_stimulus($this->getPrompt());
        if ($validation) {
            $question->set_validation($validation);
        }
        return $question;
    }

    private function validate(QtiOrderInteraction $interaction)
    {
        if ($interaction->mustShuffle()) {
            $this->exceptions[] = new MappingException('Attribute shuffle is not supported');
        }

        $simpleChoiceCollection = $interaction->getSimpleChoices();

        /** @var SimpleChoice $simpleChoice */
        foreach ($simpleChoiceCollection as $simpleChoice) {
            if ($simpleChoice->isFixed()) {
                $this->exceptions[] = new MappingException(
                    'Attribute "Fixed" for ' . $simpleChoice->getIdentifier() . 'is not supported');
            }
        }

        return true;
    }

    private function buildValidation()
    {
        $validationBuilder = new OrderInteractionValidationBuilder(
            $this->responseProcessingTemplate,
            [$this->responseDeclaration],
            'orderlist'
        );
        $validationBuilder->init($this->orderMapping);
        $validation = $validationBuilder->buildValidation();
        $this->exceptions = array_merge($this->exceptions, $validationBuilder->getExceptions());
        return $validation;
    }
}
