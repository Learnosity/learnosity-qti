<?php

namespace Learnosity\Processors\QtiV2\In\Interactions;

use Learnosity\Entities\QuestionTypes\choicematrix;
use Learnosity\Entities\QuestionTypes\choicematrix_ui_style;
use Learnosity\Exceptions\MappingException;
use Learnosity\Processors\QtiV2\In\Utils\QtiComponentUtil;
use Learnosity\Processors\QtiV2\In\Validation\MatchInteractionValidationBuilder;
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
            $this->exceptions[] = new MappingException('Shuffle attribute is not supported', MappingException::WARNING);
        }
        $simpleMatchSetCollection = $interaction->getSimpleMatchSets();

        $stems = $this->buildOptions($simpleMatchSetCollection[0], $this->stemMapping);
        $options = $this->buildOptions($simpleMatchSetCollection[1], $this->optionsMapping);

        $isMultipleResponse = false;
        $validation = $this->buildValidation($isMultipleResponse);

        if ($interaction->getMaxAssociations() !== count($stems)) {
            $this->exceptions[] =
                new MappingException('Max Association number not equals to number of stems is not supported');
        }

        $uiStyle = new choicematrix_ui_style();
        $uiStyle->set_type('table');

        $question = new choicematrix('choicematrix', $isMultipleResponse, $options, $stems);
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
            $contentStr = QtiComponentUtil::marshallCollection($choice->getContent());
            $options[] = $contentStr;
            $mapping[$choice->getIdentifier()] = count($options) - 1;
        }

        return $options;
    }

    private function buildValidation(&$isMultipleResponse)
    {
        $validationBuilder = new MatchInteractionValidationBuilder(
            $this->responseProcessingTemplate,
            [$this->responseDeclaration],
            'choicematrix'
        );
        $validationBuilder->init($this->stemMapping, $this->optionsMapping);
        $validation = $validationBuilder->buildValidation();
        $isMultipleResponse = $validationBuilder->isMultipleResponse();
        $this->exceptions = array_merge($this->exceptions, $validationBuilder->getExceptions());
        return $validation;
    }
}
