<?php

namespace Learnosity\Mappers\QtiV2\Import\Interactions;

use Learnosity\Entities\QuestionTypes\tokenhighlight;
use Learnosity\Entities\QuestionTypes\tokenhighlight_validation;
use Learnosity\Entities\QuestionTypes\tokenhighlight_validation_alt_responses_item;
use Learnosity\Entities\QuestionTypes\tokenhighlight_validation_valid_response;
use Learnosity\Exceptions\MappingException;
use Learnosity\Mappers\QtiV2\Import\ResponseProcessingTemplate;
use Learnosity\Mappers\QtiV2\Import\Utils\QtiComponentUtil;
use Learnosity\Utils\ArrayUtil;
use qtism\data\content\InlineCollection;
use qtism\data\content\interactions\Hottext;
use qtism\data\content\interactions\Prompt;
use qtism\data\content\xhtml\text\Span;
use qtism\data\content\interactions\HottextInteraction as QtiHottextInteraction;
use qtism\data\state\MapEntry;
use qtism\data\state\ResponseDeclaration;
use qtism\data\state\Value;

class HottextInteraction extends AbstractInteraction
{
    public function getQuestionType()
    {
        /** @var QtiHottextInteraction $interaction */
        $interaction = $this->interaction;
        $question = new tokenhighlight('tokenhighlight', $this->buildTemplate($interaction), 'custom');

        if ($interaction->getPrompt() instanceof Prompt) {
            $promptContent = $interaction->getPrompt()->getContent();
            $question->set_stimulus(QtiComponentUtil::marshallCollection($promptContent));
        }

        $question->set_max_selection($interaction->getMaxChoices());

        $validation = $this->buildValidation();
        if ($validation) {
            $question->set_validation($validation);
        }
        return $question;
    }

    private function buildTemplate(QtiHottextInteraction $interaction)
    {
        $content = QtiComponentUtil::marshallCollection($interaction->getComponents());
        foreach ($interaction->getComponentsByClassName('hottext') as $hottext) {
            /** @var Hottext $hottext */
            $hottextString = QtiComponentUtil::marshall($hottext);

            $tokenSpan = new span();
            $tokenSpan->setClass('lrn_token');
            $inlineCollection = new InlineCollection();
            foreach ($hottext->getComponents() as $c) {
                $inlineCollection->attach($c);
            }
            $tokenSpan->setContent($inlineCollection);
            $content = str_replace($hottextString, QtiComponentUtil::marshall($tokenSpan), $content);
        }
        return $content;
    }

    private function buildValidation()
    {
        $interaction = $this->interaction;
        $responseDeclaration = $this->responseDeclaration;

        if (!empty($this->responseProcessingTemplate) && !empty($responseDeclaration)) {
            $template = $this->responseProcessingTemplate->getTemplate();
            if ($template === ResponseProcessingTemplate::MATCH_CORRECT) {
                return $this->buildMatchCorrectValidation($interaction, $responseDeclaration);
            } else if ($template === ResponseProcessingTemplate::MAP_RESPONSE) {
                return $this->buildMapResponseValidation($interaction, $responseDeclaration);
            } else {
                $this->exceptions[] = new MappingException('Does not support template ' . $template .
                    ' on <responseProcessing>');
            }
        }
        return null;
    }

    private function buildMatchCorrectValidation(QtiHottextInteraction $interaction, ResponseDeclaration $responseDeclaration)
    {
        $hottextComponents = array_flip(array_map(function ($component) {
            return $component->getIdentifier();
        }, $interaction->getComponentsByClassName('hottext')->getArrayCopy(true)));

        $validResponseValues = [];
        foreach ($responseDeclaration->getCorrectResponse()->getValues() as $value) {
            /** @var Value $value */
            $hottextIdentifier = $value->getValue();
            $validResponseValues[] = $hottextComponents[$hottextIdentifier];
        }

        $validation = new tokenhighlight_validation();
        $validation->set_scoring_type('exactMatch');
        $validResponse = new tokenhighlight_validation_valid_response();
        $validResponse->set_score(1);
        $validResponse->set_value($validResponseValues);
        $validation->set_valid_response($validResponse);

        return $validation;
    }

    private function buildMapResponseValidation(QtiHottextInteraction $interaction, ResponseDeclaration $responseDeclaration)
    {
        $hottextComponents = array_flip(array_map(function ($component) {
            return $component->getIdentifier();
        }, $interaction->getComponentsByClassName('hottext')->getArrayCopy(true)));

        $mapEntryValueMap = [];
        foreach ($responseDeclaration->getMapping()->getMapEntries() as $mapEntry) {
            /** @var MapEntry $mapEntry */
            $mapEntryValueMap[] = [
                'key' => $hottextComponents[$mapEntry->getMapKey()],
                'score' => $mapEntry->getMappedValue()
            ];
        }

        $combinations = ArrayUtil::combinations($mapEntryValueMap);
        $correctResponses = [];
        foreach ($combinations as $combination) {
            if (count($combination) > 0 && count($combination) <= $interaction->getMaxChoices()) {
                $correctResponses[] = [
                    'values' => array_column($combination, 'key'),
                    'score' => array_sum(array_column($combination, 'score'))
                ];
            }
        }
        usort($correctResponses, function($a, $b) {
            return $a['score'] < $b['score'];
        });

        $validation = new tokenhighlight_validation();
        $validation->set_scoring_type('exactMatch');

        foreach ($correctResponses as $key => $response) {
            // First response pair shall be mapped to `valid_response`
            if ($key === 0) {
                $validResponse = new tokenhighlight_validation_valid_response();
                $validResponse->set_value($response['values']);
                $validResponse->set_score($response['score']);
                $validation->set_valid_response($validResponse);
            } else {
                // Others go in `alt_responses`
                $altResponseItem = new tokenhighlight_validation_alt_responses_item();
                $altResponseItem->set_value($response['values']);
                $altResponseItem->set_score($response['score']);
                $altResponses[] = $altResponseItem;
            }
        }

        if (!empty($altResponses)) {
            $validation->set_alt_responses($altResponses);
        }

        return $validation;
    }
}
