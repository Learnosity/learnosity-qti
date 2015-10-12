<?php

namespace LearnosityQti\Processors\QtiV2\Out\QuestionTypes;

use LearnosityQti\Entities\QuestionTypes\mcq_ui_style;
use LearnosityQti\Processors\QtiV2\Out\Validation\McqValidationBuilder;
use LearnosityQti\Entities\BaseQuestionType;
use LearnosityQti\Entities\QuestionTypes\mcq;
use LearnosityQti\Entities\QuestionTypes\mcq_options_item;
use LearnosityQti\Services\LogService;
use LearnosityQti\Utils\QtiMarshallerUtil;
use qtism\common\utils\Format;
use qtism\data\content\FlowStaticCollection;
use qtism\data\content\interactions\ChoiceInteraction;
use qtism\data\content\interactions\Orientation;
use qtism\data\content\interactions\SimpleChoice;
use qtism\data\content\interactions\SimpleChoiceCollection;

class McqMapper extends AbstractQuestionTypeMapper
{
    public function convert(BaseQuestionType $questionType, $interactionIdentifier, $interactionLabel)
    {
        /** @var mcq $question */
        $question = $questionType;

        // Build <choiceInteraction>
        $valueIdentifierMap = [];
        $simpleChoiceCollection = new SimpleChoiceCollection();
        foreach ($question->get_options() as $index => $option) {
            /** @var mcq_options_item $option */
            $choiceContent = new FlowStaticCollection();
            foreach (QtiMarshallerUtil::unmarshallElement($option->get_label()) as $component) {
                $choiceContent->attach($component);
            }

            // Use option['value'] as choice `identifier` if it has the correct format,
            // Otherwise, generate a valid using index such `CHOICE_1`, `CHOICE_2`, etc
            $originalOptionValue = $option->get_value();
            $choiceIdentifier = Format::isIdentifier($originalOptionValue, false) ? $originalOptionValue : 'CHOICE_' . $index;
            // Store this reference in a map
            $valueIdentifierMap[$originalOptionValue] = $choiceIdentifier;

            $choice = new SimpleChoice($choiceIdentifier);
            $choice->setContent($choiceContent);
            $simpleChoiceCollection->attach($choice);
        }

        // Build final interaction and its corresponding <responseDeclaration>, and its <responseProcessingTemplate>
        $interaction = new ChoiceInteraction($interactionIdentifier, $simpleChoiceCollection);
        $interaction->setLabel($interactionLabel);
        $interaction->setMinChoices(1);
        $interaction->setMaxChoices($question->get_multiple_responses() ? $simpleChoiceCollection->count() : 1);

        // Build the prompt
        $interaction->setPrompt($this->convertStimulusForPrompt($question->get_stimulus()));

        // Set shuffle options
        $interaction->setShuffle($question->get_shuffle_options() ? true : false);

        // Set the layout
        if ($question->get_ui_style() instanceof mcq_ui_style &&
            $question->get_ui_style()->get_type() === 'horizontal' &&
            intval($question->get_ui_style()->get_columns()) === count($question->get_options())) {
            $interaction->setOrientation(Orientation::HORIZONTAL);
        } else {
            $interaction->setOrientation(Orientation::VERTICAL);
            LogService::log('ui_style` is ignored and `choiceInteraction` is assumed and set as `vertical`');
        }

        if (empty($question->get_validation())) {
            return [$interaction, null, null];
        }

        $builder = new McqValidationBuilder($question->get_multiple_responses(), $valueIdentifierMap);
        list($responseDeclaration, $responseProcessing) = $builder->buildValidation($interactionIdentifier, $question->get_validation());

        return [$interaction, $responseDeclaration, $responseProcessing];
    }
}
