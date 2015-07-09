<?php

namespace Learnosity\Processors\QtiV2\In\Interactions;

use Learnosity\Entities\QuestionTypes\mcq;
use Learnosity\Entities\QuestionTypes\mcq_ui_style;
use Learnosity\Exceptions\MappingException;
use Learnosity\Processors\QtiV2\In\Utils\QtiComponentUtil;
use Learnosity\Processors\QtiV2\In\Validation\ChoiceInteractionValidationBuilder;
use qtism\data\content\interactions\Orientation;
use qtism\data\content\interactions\Prompt;
use qtism\data\content\interactions\SimpleChoice;
use qtism\data\content\interactions\SimpleChoiceCollection;
use qtism\data\content\interactions\ChoiceInteraction as QtiChoiceInteraction;

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

        // Ignoring @minChoices
        if (!empty($interaction->getMinChoices())) {
            $this->exceptions[] = new MappingException('Attribute minChoices is not support. Thus, ignored');
        }

        // Build validation
        $validationBuilder = new ChoiceInteractionValidationBuilder(
            $this->responseDeclaration,
            $optionsMap = array_column($options, 'label', 'value')
        );
        $validation = $validationBuilder->buildValidation($this->responseProcessingTemplate);
        $this->exceptions = array_merge($this->exceptions, $validationBuilder->getExceptions());

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
            $options[] = [
                'label' => QtiComponentUtil::marshallCollection($choice->getContent()),
                'value' => $choice->getIdentifier()
            ];
        }
        return $options;
    }
}
