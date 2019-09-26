<?php

namespace LearnosityQti\Processors\QtiV2\Out\QuestionTypes;

use LearnosityQti\Entities\BaseQuestionType;
use LearnosityQti\Entities\QuestionTypes\choicematrix;
use LearnosityQti\Processors\QtiV2\Out\ContentCollectionBuilder;
use LearnosityQti\Processors\QtiV2\Out\Validation\ChoicematrixValidationBuilder;
use LearnosityQti\Utils\QtiMarshallerUtil;
use qtism\data\content\FlowCollection;
use qtism\data\content\FeedbackInline;
use qtism\data\content\InlineCollection;
use qtism\data\content\interactions\MatchInteraction;
use qtism\data\content\interactions\SimpleAssociableChoice;
use qtism\data\content\interactions\SimpleAssociableChoiceCollection;
use qtism\data\content\interactions\SimpleMatchSet;
use qtism\data\content\interactions\SimpleMatchSetCollection;
use qtism\data\content\TextRun;
use qtism\data\content\xhtml\text\Div;

class ChoicematrixMapper extends AbstractQuestionTypeMapper
{
    public function convert(BaseQuestionType $question, $interactionIdentifier, $interactionLabel)
    {
        /** @var choicematrix $question */
        // Is multiple response ?
        $isMultipleResponses = !empty($question->get_multiple_responses()) ? $question->get_multiple_responses() : false;
        $optionCount = count($question->get_options());
        $stemCount = count($question->get_stems());

        // Check if distractor_rationale_response_level exists
        $feedbackOptions = [];
        $metadata = $question->get_metadata();
        if (isset($metadata)) {
            if (!empty($metadata->get_distractor_rationale())) {
                $feedbackOptions['general_feedback'] = $metadata->get_distractor_rationale();
            }
        }

        // Append the two sets of choices, the first set defines the source choices and the second set the targets
        $simpleMatchCollection = new SimpleMatchSetCollection();
        list($stemCollection, $stemIndexIdentifierMap) = $this->buildStemCollection($question, $isMultipleResponses, $optionCount);
        list($optionCollection, $optionIndexIdentifierMap) = $this->buildOptionCollection($question, $stemCount);
        $simpleMatchCollection->attach(new SimpleMatchSet($stemCollection));
        $simpleMatchCollection->attach(new SimpleMatchSet($optionCollection));

        // Build the interaction
        $interaction = new MatchInteraction($interactionIdentifier, $simpleMatchCollection);
        $interaction->setPrompt($this->convertStimulusForPrompt($question->get_stimulus()));
        $interaction->setLabel($interactionLabel);
        $interaction->setShuffle(false); // No support for shuffling

        // If multiple response set then student is allowed to put 1 association (tick 1 box) or (optionCount * stemCount) association (tick all the boxes)
        $interaction->setMaxAssociations($isMultipleResponses ? ($optionCount * $stemCount) : $stemCount);
        $interaction->setMinAssociations($isMultipleResponses ? 1 : $stemCount);

        $builder = new ChoicematrixValidationBuilder($stemIndexIdentifierMap, $optionIndexIdentifierMap);
        list($responseDeclaration, $responseProcessing) = $builder->buildValidation($interactionIdentifier, $question->get_validation(), 1, $feedbackOptions);

        return [$interaction, $responseDeclaration, $responseProcessing];
    }

    private function buildStemCollection(choicematrix $question, $isMultipleResponses, $optionCount, $feedbackOptions = array())
    {
        $stemIndexIdentifierMap = [];
        $stemCollection = new SimpleAssociableChoiceCollection();
        foreach ($question->get_stems() as $key => $stemValue) {
            // Learnosity's 'choicematrix' always have its stem to have a max of 1 associable choice (unless it's multiple response)
            // Also, it won't validate upon empty response, thus setting match min to 1
            $stemChoice = new SimpleAssociableChoice('STEM_' . $key, $isMultipleResponses ? $optionCount : 1);
            $stemChoice->setMatchMin(1);
            $stemChoice->setContent(ContentCollectionBuilder::buildFlowStaticCollectionContent(QtiMarshallerUtil::unmarshallElement($stemValue)));
            $stemCollection->attach($stemChoice);
            $stemIndexIdentifierMap[$key] = $stemChoice->getIdentifier();
        }
        return [$stemCollection, $stemIndexIdentifierMap];
    }

    private function buildOptionCollection(choicematrix $question, $stemCount)
    {
        $optionIndexIdentifierMap = [];
        $optionCollection = new SimpleAssociableChoiceCollection();
        foreach ($question->get_options() as $key => $optionValue) {
            // Learnosity's `choicematrix` always have its options to have any number of associable choice, thus setting to stems count
            // Same as above, won't validate upon empty response, thus setting match min to 1
            $optionChoice = new SimpleAssociableChoice('OPTION_' . $key, $stemCount);
            $optionChoice->setMatchMin(1);
            $optionChoice->setContent(ContentCollectionBuilder::buildFlowStaticCollectionContent(QtiMarshallerUtil::unmarshallElement($optionValue)));
            $optionCollection->attach($optionChoice);
            $optionIndexIdentifierMap[$key] = $optionChoice->getIdentifier();
        }
        return [$optionCollection, $optionIndexIdentifierMap];
    }
}
