<?php

namespace Learnosity\Processors\QtiV2\In\Interactions;

use Learnosity\Entities\QuestionTypes\mcq;
use Learnosity\Entities\QuestionTypes\mcq_ui_style;
use Learnosity\Entities\QuestionTypes\mcq_validation;
use Learnosity\Entities\QuestionTypes\mcq_validation_valid_response;
use Learnosity\Exceptions\MappingException;
use Learnosity\Processors\QtiV2\In\ResponseProcessingTemplate;
use Learnosity\Processors\QtiV2\In\Utils\QtiComponentUtil;
use qtism\data\content\interactions\Orientation;
use qtism\data\content\interactions\Prompt;
use qtism\data\content\interactions\SimpleChoice;
use qtism\data\content\interactions\SimpleChoiceCollection;
use qtism\data\content\interactions\ChoiceInteraction as QtiChoiceInteraction;
use qtism\data\state\Value;

class ChoiceInteractionMapper extends AbstractInteractionMapper
{
    public function getQuestionType()
    {
        /* @var QtiChoiceInteraction $interaction */
        $interaction = $this->interaction;

        $options = $this->buildOptions($interaction->getSimpleChoices());
        $mcq = new mcq('mcq', $options);

        // Support for @shuffle
        $mcq->set_shuffle_options($interaction->mustShuffle());

        // Support for @orientation ('vertical' or 'horizontal')
        $uiStyle = new mcq_ui_style();
        if ($interaction->getOrientation() === Orientation::HORIZONTAL) {
            $uiStyle->set_type('horizontal');
            $uiStyle->set_columns(count($options));
            $mcq->set_ui_style($uiStyle);
        }

        // Support mapping for <prompt>
        if ($interaction->getPrompt() instanceof Prompt) {
            $promptContent = $interaction->getPrompt()->getContent();
            $mcq->set_stimulus(QtiComponentUtil::marshallCollection($promptContent));
        }

        // Partial support for @maxChoices
        $maxChoiceNum = $interaction->getMaxChoices();
        if ($maxChoiceNum > 1) {
            if ($maxChoiceNum !== count($options)) {
                // We do not support specifying amount of choices
                $this->exceptions[] = new MappingException(
                    "Allowing multiple responses of max " . count($options) . " options, however " .
                    "maxChoices of $maxChoiceNum would be ignored since we can't support exact number"
                );
            }
            $mcq->set_multiple_responses(true);
        }

        if (!empty($interaction->getMinChoices())) {
            $this->exceptions[] = new MappingException('Attribute minChoices is not support. Thus, ignored');
        }

        $validation = $this->buildValidation();
        if (!empty($validation)) {
            $mcq->set_validation($validation);
        }

        return $mcq;
    }

    private function buildOptions(SimpleChoiceCollection $simpleChoices)
    {
        /* @var $choice SimpleChoice */
        $options = [];
        foreach ($simpleChoices as $key => $choice) {
            // Store 'SimpleChoice' identifier to key for validation purposes
            // TODO: Filter $choice->getContent() because we ignore <printedVariable>, <feedbackBlock>,
            // TODO: <feedbackInline>, <templateInline>, <templateBlock>, and <include>
            $options[] = [
                'label' => QtiComponentUtil::marshallCollection($choice->getContent()),
                'value' => $choice->getIdentifier()
            ];
        }
        return $options;
    }

    private function buildValidation()
    {
        if (empty($this->responseProcessingTemplate)) {
            $this->exceptions[] =
                new MappingException('Response processing template is missing', MappingException::CRITICAL);
        } elseif (!$this->responseDeclaration) {
            $this->exceptions[] =
                new MappingException('Response Declaration is missing', MappingException::CRITICAL);
        } elseif ($this->responseProcessingTemplate->getTemplate() === ResponseProcessingTemplate::MATCH_CORRECT) {
            $correctResponse = $this->responseDeclaration->getCorrectResponse();

            $validResponseValues = [];
            /** @var Value $value */
            foreach ($correctResponse->getValues() as $value) {
                $validResponseValues[] = $value->getValue();
            }

            $validResponse = new mcq_validation_valid_response();
            $validResponse->set_score(1);
            $validResponse->set_value($validResponseValues);

            $validation = new mcq_validation();
            $validation->set_scoring_type('exactMatch');
            $validation->set_valid_response($validResponse);

            return $validation;
        } else {
            $this->exceptions[] =
                new MappingException(
                    'Does not support template ' . $this->responseProcessingTemplate->getTemplate() .
                    ' on <responseProcessing>',
                    MappingException::CRITICAL
                );
        }
    }
}
