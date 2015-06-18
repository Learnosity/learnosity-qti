<?php

namespace Learnosity\Mappers\QtiV2\Import\MergedInteractions;

use Learnosity\Entities\QuestionTypes\clozedropdown;
use Learnosity\Entities\QuestionTypes\clozedropdown_validation;
use Learnosity\Entities\QuestionTypes\clozedropdown_validation_alt_responses_item;
use Learnosity\Entities\QuestionTypes\clozedropdown_validation_valid_response;
use Learnosity\Exceptions\MappingException;
use Learnosity\Mappers\QtiV2\Import\ResponseProcessingTemplate;
use Learnosity\Mappers\QtiV2\Import\Utils\QtiComponentUtil;
use Learnosity\Utils\ArrayUtil;
use qtism\data\content\interactions\InlineChoice;
use qtism\data\content\interactions\Interaction;
use qtism\data\content\ItemBody;
use qtism\data\state\ResponseDeclaration;

class MergedInlineChoiceInteraction extends AbstractMergedInteraction
{
    public function getQuestionType()
    {
        $interactionComponents = $this->itemBody->getComponentsByClassName('inlineChoiceInteraction', true);

        //TODO: Throw all the warnings to an array
        $interactionXmls = [];
        $possibleResponsesMap = [];
        foreach ($interactionComponents as $component) {
            /** @var Interaction $component */
            $interactionXmls[] = QtiComponentUtil::marshall($component);
            /** @var InlineChoice $inlineChoice */
            foreach ($component->getComponents() as $inlineChoice) {
                $possibleResponsesMap[$component->getResponseIdentifier()][$inlineChoice->getIdentifier()] =
                    QtiComponentUtil::marshallCollection($inlineChoice->getContent());
            }
        }

        $template = $this->buildTemplate($this->itemBody, $interactionXmls);
        $validation = $this->buildValidation($possibleResponsesMap, $this->responseDeclarations);

        $possibleResponses = [];
        foreach ($possibleResponsesMap as $possibleResponse) {
            $possibleResponses[] = array_values($possibleResponse);
        }
        $clozedropdown = new clozedropdown('clozedropdown', $template, $possibleResponses);
        $clozedropdown->set_validation($validation);

        return $clozedropdown;
    }

    private function buildValidation(array $possibleResponses, array $responseDeclarations)
    {
        if (empty($this->responseProcessingTemplate)) {
            return null;
        }
        if ($this->responseProcessingTemplate->getTemplate() === ResponseProcessingTemplate::MATCH_CORRECT) {
            /** @var ResponseDeclaration $responseDeclaration */
            $validResponsesValues = [];
            foreach ($responseDeclarations as $responseIdentifier => $responseDeclaration) {
                $values = [];
                foreach ($responseDeclaration->getCorrectResponse()->getValues() as $value) {
                    $values[] = $possibleResponses[$responseIdentifier][$value->getValue()];
                }
                $validResponsesValues[] = $values;
            }
            $combinationsValidResponseValues = ArrayUtil::combinations($validResponsesValues);

            // First response pair shall be mapped to `valid_response`
            $firstValidResponseValue = array_shift($combinationsValidResponseValues);
            $validResponse = new clozedropdown_validation_valid_response();
            $validResponse->set_score(1);
            if (is_array($firstValidResponseValue)) {
                $validResponse->set_value($firstValidResponseValue);
            } else {
                $validResponse->set_value([$firstValidResponseValue]);
            }

            // Others go in `alt_responses`
            $altResponses = [];
            foreach ($combinationsValidResponseValues as $otherResponseValues) {
                $item = new clozedropdown_validation_alt_responses_item();
                $item->set_score(1);
                $item->set_value($otherResponseValues);
                $altResponses[] = $item;
            }

            $validation = new clozedropdown_validation();
            $validation->set_scoring_type('exactMatch');
            $validation->set_valid_response($validResponse);
            if (!empty($altResponses)) {
                $validation->set_alt_responses($altResponses);
            }

            return $validation;
        } else {
            throw new MappingException('Does not support template ' . $this->responseProcessingTemplate->getTemplate() .
                ' on <responseProcessing>', MappingException::CRITICAL);
        }
    }

    private function buildTemplate(ItemBody $itemBody, array $interactionXmls)
    {
        // Build item's HTML content
        $content = QtiComponentUtil::marshallCollection($itemBody->getComponents());
        foreach ($interactionXmls as $interactionXml) {
            $content = str_replace($interactionXml, '{{response}}', $content);
        }
        return $content;
    }

    public function getItemContent()
    {
        return '<span class="learnosity-response question-' . $this->questionReference . '"></span>';
    }
}
