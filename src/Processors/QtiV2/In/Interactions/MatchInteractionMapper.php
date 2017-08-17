<?php

namespace LearnosityQti\Processors\QtiV2\In\Interactions;

use LearnosityQti\Entities\QuestionTypes\choicematrix;
use LearnosityQti\Entities\QuestionTypes\choicematrix_ui_style;
use LearnosityQti\Utils\QtiMarshallerUtil;
use LearnosityQti\Processors\QtiV2\In\Validation\MatchInteractionValidationBuilder;
use LearnosityQti\Services\LogService;
use qtism\data\content\interactions\MatchInteraction as QtiMatchInteraction;
use qtism\data\content\interactions\SimpleAssociableChoice;
use qtism\data\content\interactions\SimpleMatchSet;

class MatchInteractionMapper extends AbstractInteractionMapper
{
    private $stemMapping = [];
    private $optionsMapping = [];

    public function getQuestionType()
    {
        /* @var QtiMatchInteraction $interaction */
        $interaction = $this->interaction;

        if ($interaction->mustShuffle()) {
            LogService::log('Shuffle attribute is not supported');
        }
        $simpleMatchSetCollection = $interaction->getSimpleMatchSets();

        $stems = $this->buildOptions($simpleMatchSetCollection[0], $this->stemMapping);
        $options = $this->buildOptions($simpleMatchSetCollection[1], $this->optionsMapping);

        // Build validation
        $validationBuilder = new MatchInteractionValidationBuilder(
            $this->stemMapping,
            $this->optionsMapping,
            $this->responseDeclaration
        );
        $validation = $validationBuilder->buildValidation($this->responseProcessingTemplate);

        if ($interaction->getMaxAssociations() !== count($stems)) {
            LogService::log('Max Association number not equals to number of stems is not supported');
        }

        $uiStyle = new choicematrix_ui_style();
        $uiStyle->set_type('table');

        $isMultipleResponse = $this->isMultipleResponse($interaction);
        $question = new choicematrix('choicematrix', $options, $stems);
        $question->set_multiple_responses($isMultipleResponse);
        $question->set_stimulus($this->getPrompt());
        $question->set_ui_style($uiStyle);
        if ($validation) {
            $question->set_validation($validation);
        }
        return $question;
    }

    private function isMultipleResponse(QtiMatchInteraction $interaction)
    {
        // We determine whether the question shall be mapped to `multiple_responses` as true
        // if any the source choices (stems or options) can be mapped to be more than 1
        foreach ($interaction->getSourceChoices()->getSimpleAssociableChoices() as $choice) {
            /** @var SimpleAssociableChoice $choice */
            if ($choice->getMatchMax() !== 1) {
                return true;
            }
        }
        return false;
    }

    private function buildOptions(SimpleMatchSet $simpleMatchSet, &$mapping)
    {
        $options = [];
        $choiceCollection = $simpleMatchSet->getSimpleAssociableChoices();
        /** @var SimpleAssociableChoice $choice */
        foreach ($choiceCollection as $choice) {
            $contentStr = QtiMarshallerUtil::marshallCollection($choice->getContent());
            $options[] = $contentStr;
            $mapping[$choice->getIdentifier()] = count($options) - 1;
        }

        return $options;
    }
}
