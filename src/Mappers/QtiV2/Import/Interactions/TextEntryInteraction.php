<?php

namespace Learnosity\Mappers\QtiV2\Import\Interactions;

use Learnosity\Entities\QuestionTypes\clozetext;
use Learnosity\Exceptions\MappingException;
use Learnosity\Mappers\QtiV2\Import\ResponseProcessingTemplate;
use Learnosity\Mappers\QtiV2\Import\Validation\TextEntryValidationBuilder;
use qtism\data\state\MapEntry;
use qtism\data\state\Value;

class TextEntryInteraction extends AbstractInteraction
{
    public function getQuestionType()
    {
        $closetext = new clozetext('clozetext', '{{response}}');
        $expectedLength = $this->interaction->getExpectedLength();
        if ($expectedLength > 250) {
            $expectedLength = 250;
            $closetext->set_multiple_line(true);
        }
        $closetext->set_max_length($expectedLength);
        $validation = $this->buildValidation($isCaseSensitive);
        if ($validation) {
            $closetext->set_validation($validation);
        }
        $closetext->set_case_sensitive($isCaseSensitive);
        return $closetext;
    }

    private function buildValidation(&$isCaseSensitive)
    {
        $isCaseSensitive = true;
        $answers = [];
        if (!$this->responseProcessingTemplate) {
            $this->exceptions[] =
                new MappingException('Response Processing Template is not defined so validation is not available.',
                    MappingException::WARNING);
            return null;
        } else {
            switch ($this->responseProcessingTemplate->getTemplate()) {
                case ResponseProcessingTemplate::MATCH_CORRECT:
                    //we set all scores to 1 by default
                    $score = 1;
                    /* @var $value Value */
                    foreach ($this->responseDeclaration->getCorrectResponse()->getValues() as $value) {
                        $answers[] = [$value->getValue() => $score];
                    }
                    break;
                case ResponseProcessingTemplate::CC2_MAP_RESPONSE:
                case ResponseProcessingTemplate::MAP_RESPONSE:
                    /* @var $mapEntry MapEntry */
                    $highestScore = -1;
                    foreach ($this->responseDeclaration->getMapping()->getMapEntries() as $mapEntry) {
                        if ($isCaseSensitive) {
                            $mapEntry->isCaseSensitive();
                        }
                        if ($mapEntry->getMappedValue() > $highestScore) {
                            $highestScore = $mapEntry->getMappedValue();
                            array_unshift($answers, [$mapEntry->getMapKey() => $mapEntry->getMappedValue()]);
                        } else {
                            $answers[] = [$mapEntry->getMapKey() => $mapEntry->getMappedValue()];
                        }
                    }
                    break;
                default:
                    $this->exceptions[] =
                        new MappingException('Unrecognised response processing template. Validation is not available',
                            MappingException::WARNING);
                    return null;
            }
        }
        $validationBuilder = new TextEntryValidationBuilder();
        return $validationBuilder->buildValidation($answers);
    }
}
