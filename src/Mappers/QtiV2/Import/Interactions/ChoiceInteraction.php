<?php

namespace Learnosity\Mappers\QtiV2\Import\Interactions;

use Learnosity\Entities\QuestionTypes\mcq;
use Learnosity\Entities\QuestionTypes\mcq_validation;
use Learnosity\Entities\QuestionTypes\mcq_validation_valid_response;
use Learnosity\Exceptions\MappingException;
use Learnosity\Mappers\QtiV2\Import\Documentation\QtiDoc;
use Learnosity\Mappers\QtiV2\Import\Documentation\SupportStatus;
use Learnosity\Mappers\QtiV2\Import\ResponseProcessingTemplate;
use Learnosity\Mappers\QtiV2\Import\Utils\QtiComponentUtil;
use qtism\data\content\interactions\SimpleChoice;
use qtism\data\content\interactions\SimpleChoiceCollection;
use qtism\data\content\interactions\ChoiceInteraction as QtiChoiceInteraction;

class ChoiceInteraction extends AbstractInteraction
{
    private $choicesMapping;

    public static function getDocumentation()
    {
        $documentation = [
            '@notes' => "
                The element 'choiceInteraction' maps to our 'mcq' question.
            ",
            '@attributes' => [
                'xmlbase' => QtiDoc::row(SupportStatus::NO),
                'id' => QtiDoc::row(SupportStatus::NO),
                'class' => QtiDoc::row(SupportStatus::NO),
                'xmllang' => QtiDoc::row(SupportStatus::NO),
                'label' => QtiDoc::row(SupportStatus::NO),
                'responseIdentifier' => QtiDoc::row(SupportStatus::NO),
                'shuffle' => QtiDoc::row(SupportStatus::NO),
                'maxChoices' => QtiDoc::row(SupportStatus::NO),
                'minChoices' => QtiDoc::row(SupportStatus::NO),
                'orientation' => QtiDoc::row(SupportStatus::NO)
            ],
            'prompt' => QtiDoc::row(SupportStatus::YES),
            'simpleChoice' => [
                '@attributes' => [
                    'id' => QtiDoc::row(SupportStatus::NO),
                    'class' => QtiDoc::undefined(),
                    'xmllang' => QtiDoc::undefined(),
                    'label' => QtiDoc::undefined(),
                    'identifier' => QtiDoc::undefined(),
                    'fixed' => QtiDoc::undefined(),
                    'templateIdentifier' => QtiDoc::undefined(),
                    'showHide' => QtiDoc::undefined(),
                ]
            ]
        ];
        $documentation['simpleChoice'] = array_merge($documentation['simpleChoice'], QtiDoc::defaultFlowStaticRow());
        return $documentation;
    }

    public function getQuestionType()
    {
        /* @var QtiChoiceInteraction $interaction */
        $interaction = $this->interaction;

        $options = $this->buildOptions($interaction->getSimpleChoices());
        $mcq = new mcq('mcq', $options);
        $mcq->set_shuffle_options($interaction->mustShuffle());

        if (!empty($interaction->getPrompt())) {
            // TODO: Shall put warning on ignored maxChoice, minChoice, class, xmllang, showHide, fixed, templateIdentifier etc
            $promptContent = $interaction->getPrompt()->getContent();
            $mcq->set_stimulus(QtiComponentUtil::marshallCollection($promptContent));
        }
        $maxChoiceNum = $interaction->getMaxChoices();
        if ($maxChoiceNum > 1) {
            if ($maxChoiceNum !== count($options)) {
                // We do not support specifying amount of choices
                $this->exceptions[] = new MappingException("Max Choice " . $maxChoiceNum . "is not supported");
            }
            $mcq->set_multiple_responses(true);
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
            $this->choicesMapping[$choice->getIdentifier()] = $key;
            $options[] = [
                'label' => QtiComponentUtil::marshallCollection($choice->getContent()),
                'value' => (string)$key
            ];
            // TODO: Put warning because we ignore <printedVariable>, <feedbackBlock>,
            // TODO: <feedbackInline>, <templateInline>, <tempplateBlock>
            // TODO: Also <include>
        }
        return $options;
    }

    private function buildValidation()
    {
        if (empty($this->responseProcessingTemplate)) {
            return null;
        }
        if ($this->responseProcessingTemplate->getTemplate() === ResponseProcessingTemplate::MATCH_CORRECT) {
            $correctResponse = $this->responseDeclaration->getCorrectResponse();
            if (!empty($correctResponse->getInterpretation())) {
                // TODO: should warn that this is ignored
            }

            $validResponseValues = [];
            foreach ($correctResponse->getValues() as $key => $value) {
                $optionIndex = $this->choicesMapping[$value->getValue()];
                $validResponseValues[] = (string)$optionIndex;
            }

            $validResponse = new mcq_validation_valid_response();
            $validResponse->set_score(1);
            $validResponse->set_value($validResponseValues);

            $validation = new mcq_validation();
            $validation->set_scoring_type('exactMatch');
            $validation->set_valid_response($validResponse);

            return $validation;
        } else {
            throw new MappingException('Does not support template ' . $this->responseProcessingTemplate->getTemplate() .
                ' on <responseProcessing>', MappingException::CRITICAL);
        }
    }
}
