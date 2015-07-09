<?php

namespace Learnosity\Processors\QtiV2\In\Interactions;

use Learnosity\Entities\QuestionTypes\clozetext;
use Learnosity\Processors\QtiV2\In\Validation\TextEntryInteractionValidationBuilder;

class TextEntryInteractionMapper extends AbstractInteractionMapper
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
        $validationBuilder = new TextEntryInteractionValidationBuilder(
            [$this->interaction->getResponseIdentifier()],
            [$this->interaction->getResponseIdentifier() => $this->responseDeclaration]
        );
        $validation = $validationBuilder->buildValidation($this->responseProcessingTemplate);
        $isCaseSensitive = $validationBuilder->isCaseSensitive();
        $this->exceptions = array_merge($this->exceptions, $validationBuilder->getExceptions());
        return $validation;
    }
}
