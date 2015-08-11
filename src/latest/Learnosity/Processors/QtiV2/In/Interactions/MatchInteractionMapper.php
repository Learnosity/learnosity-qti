<?php

namespace Learnosity\Processors\QtiV2\In\Interactions;

use Learnosity\Entities\QuestionTypes\choicematrix;
use Learnosity\Entities\QuestionTypes\choicematrix_ui_style;
use Learnosity\Utils\QtiMarshallerUtil;
use Learnosity\Processors\QtiV2\In\Validation\MatchInteractionValidationBuilder;
use Learnosity\Services\LogService;
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

        $isMultipleResponse = false;
        $validation = $this->buildValidation($isMultipleResponse);

        if ($interaction->getMaxAssociations() !== count($stems)) {
            LogService::log('Max Association number not equals to number of stems is not supported');
        }

        $uiStyle = new choicematrix_ui_style();
        $uiStyle->set_type('table');

        $question = new choicematrix('choicematrix', $options, $isMultipleResponse, $stems);
        $question->set_stimulus($this->getPrompt());
        $question->set_ui_style($uiStyle);
        if ($validation) {
            $question->set_validation($validation);
        }
        return $question;
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

    private function buildValidation(&$isMultipleResponse)
    {
        $validationBuilder = new MatchInteractionValidationBuilder(
            $this->stemMapping,
            $this->optionsMapping,
            $this->responseDeclaration
        );
        $validation = $validationBuilder->buildValidation($this->responseProcessingTemplate);
        $isMultipleResponse = $validationBuilder->isMultipleResponse();
        return $validation;
    }
}
