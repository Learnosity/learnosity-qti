<?php

namespace Learnosity\Mappers\QtiV2\Import\MergedInteractions;

use Learnosity\Entities\QuestionTypes\clozetext;
use Learnosity\Entities\QuestionTypes\clozetext_validation;
use Learnosity\Entities\QuestionTypes\clozetext_validation_alt_responses_item;
use Learnosity\Entities\QuestionTypes\clozetext_validation_valid_response;
use Learnosity\Exceptions\MappingException;
use Learnosity\Mappers\QtiV2\Import\Utils\QtiComponentUtil;
use Learnosity\Utils\ArrayUtil;
use qtism\data\content\interactions\Interaction;
use qtism\data\content\ItemBody;
use qtism\data\state\MapEntry;
use qtism\data\state\ResponseDeclaration;

class MergedTextEntryInteraction extends AbstractMergedInteraction
{
    public function getQuestionType()
    {
        // we assume the function maintain the order of the xml element
        $interactionComponents = $this->itemBody->getComponentsByClassName('textEntryInteraction', true);

        $interactionXmls = [];
        $interactionIdentifiers = [];
        /** @var Interaction $component */
        foreach ($interactionComponents as $component) {
            $interactionXmls[] = QtiComponentUtil::marshall($component);
            $interactionIdentifiers[] = $component->getResponseIdentifier();
        }

        $validation = $this->buildValidation($interactionIdentifiers);

        //TODO: Throw all the warnings to an array
        $closetext = new clozetext('clozetext', $this->buildTemplate($this->itemBody, $interactionXmls));
        $closetext->set_validation($validation);
        return $closetext;
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

    public function buildValidation(array $interactionIdentifiers)
    {
        $originalResponseData = [];
        foreach ($interactionIdentifiers as $interactionIdentifier) {
            if (!isset($this->responseDeclarations[$interactionIdentifier])) {
                throw new MappingException("Unable to locate {$interactionIdentifier}" . 'in response declarations');
            }
            /* @var $responseElement ResponseDeclaration */
            $responseElement = $this->responseDeclarations[$interactionIdentifier];
            $mapEntryElements = $responseElement->getMapping()->getMapEntries();

            $interactionResponse = [];
            /* @var $mapEntryElement MapEntry */
            foreach ($mapEntryElements as $mapEntryElement) {
                $interactionResponse [] = $mapEntryElement->getMapKey();
            }
            $originalResponseData[] = $interactionResponse;
        }

        $mutatedOriginalResponses = ArrayUtil::mutateResponses($originalResponseData);

        $validResponse = new clozetext_validation_valid_response();
        $validResponse->set_score(1);
        $altResponses = [];

        for ($i = 0; $i < count($mutatedOriginalResponses); $i++) {
            if ($i === 0) {
                $validResponse->set_value($mutatedOriginalResponses[$i]);
            } else {
                $altResponse = new clozetext_validation_alt_responses_item();
                $altResponse->set_score(1);
                $altResponse->set_value($mutatedOriginalResponses[$i]);
                $altResponses[] = $altResponse;
            }
        }

        $validation = new clozetext_validation();
        $validation->set_scoring_type('exactMatch');
        $validation->set_valid_response($validResponse);
        $validation->set_alt_responses($altResponses);

        return $validation;
    }
}
