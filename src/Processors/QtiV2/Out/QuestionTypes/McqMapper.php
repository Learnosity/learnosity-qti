<?php

namespace Learnosity\Processors\QtiV2\Out\QuestionTypes;

use Learnosity\Entities\BaseQuestionType;
use Learnosity\Entities\QuestionTypes\mcq;
use Learnosity\Entities\QuestionTypes\mcq_options_item;
use Learnosity\Entities\QuestionTypes\mcq_validation;
use Learnosity\Utils\QtiMarshallerUtil;
use Learnosity\Services\LogService;
use qtism\common\enums\BaseType;
use qtism\common\enums\Cardinality;
use qtism\common\utils\Format;
use qtism\data\content\FlowStaticCollection;
use qtism\data\content\interactions\ChoiceInteraction;
use qtism\data\content\interactions\Prompt;
use qtism\data\content\interactions\SimpleChoice;
use qtism\data\content\interactions\SimpleChoiceCollection;
use qtism\data\processing\ResponseProcessing;
use qtism\data\state\CorrectResponse;
use qtism\data\state\ResponseDeclaration;
use qtism\data\state\Value;
use qtism\data\state\ValueCollection;

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
        $interaction->setPrompt($this->buildPrompt($question->get_stimulus()));

        if (empty($question->get_validation())) {
            return [$interaction, null, null];
        }

        list($responseDeclaration, $responseProcessingTemplate) = $this->buildResponseDeclaration(
            $interactionIdentifier,
            $valueIdentifierMap,
            $question->get_validation(),
            $question->get_multiple_responses()
        );
        return [$interaction, $responseDeclaration, $responseProcessingTemplate];
    }

    private function buildPrompt($stimulusString)
    {
        $prompt = new Prompt();
        $contentCollection = new FlowStaticCollection();
        foreach ($this->convertStimulus($stimulusString) as $component) {
            $contentCollection->attach($component);
        }
        $prompt->setContent($contentCollection);
        return $prompt;
    }

    private function buildResponseDeclaration($identifier, array $valueIdentifierMap, mcq_validation $validation, $isMultipleResponse)
    {
        $cardinality = ($isMultipleResponse) ? Cardinality::MULTIPLE : Cardinality::SINGLE;

        // If question only has `valid_response` with score of `1`, then it would be mapped to <correctResponse>
        // with `match_correct` template
        $type = $validation->get_scoring_type();
        if (!empty($validation->get_valid_response()) &&
            empty($validation->get_alt_responses()) &&
            intval($validation->get_valid_response()->get_score()) === 1
        ) {
            $values = new ValueCollection();
            // TODO: Would this always be an array?
            foreach ($validation->get_valid_response()->get_value() as $value) {
                // TODO: Why do I have to stupidly check like this
                if (isset($value) && in_array($value, array_keys($valueIdentifierMap))) {
                    $choiceIdentifier = $valueIdentifierMap[$value];
                    $values->attach(new Value($choiceIdentifier));
                } else {
                    LogService::log('Invalid `value` in validation object. Failed mapping it');
                }
            }

            // Build them`
            $responseDeclaration = null;
            $responseProcessing = null;
            if ($values->count() >= 1) {
                $responseDeclaration = new ResponseDeclaration($identifier, BaseType::IDENTIFIER, $cardinality);
                $responseProcessing = new ResponseProcessing();
                $responseDeclaration->setCorrectResponse(new CorrectResponse($values));
                $responseProcessing->setTemplate('http://www.imsglobal.org/question/qtiv2p1/rptemplates/match_correct.xml');
            }
            return [$responseDeclaration, $responseProcessing];
        }
    }
}
