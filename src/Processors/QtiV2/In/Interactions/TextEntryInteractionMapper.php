<?php

namespace Learnosity\Processors\QtiV2\In\Interactions;

use Learnosity\Entities\QuestionTypes\clozetext;
use Learnosity\Exceptions\MappingException;
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
        if (!$this->responseProcessingTemplate) {
            $this->exceptions[] =
                new MappingException(
                    'Response Processing Template is not defined so validation is not available.',
                    MappingException::WARNING
                );
            return null;
        }

        $validationBuilder = new TextEntryInteractionValidationBuilder(
            $this->responseProcessingTemplate,
            [$this->responseDeclaration],
            'clozetext'
        );

        $validationBuilder->init();
        $validation = $validationBuilder->buildValidation();
        $isCaseSensitive = $validationBuilder->isCaseSensitive();
        $this->exceptions = array_merge($this->exceptions, $validationBuilder->getExceptions());
        return $validation;
    }
}
