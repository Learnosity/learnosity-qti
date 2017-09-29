<?php

namespace LearnosityQti\Processors\QtiV2\In\Interactions;

use LearnosityQti\Entities\QuestionTypes\clozetext;
use LearnosityQti\Entities\QuestionTypes\clozetext_response_container;
use LearnosityQti\Processors\QtiV2\In\Validation\TextEntryInteractionValidationBuilder;

class TextEntryInteractionMapper extends AbstractInteractionMapper
{
    const CHAR_LIMIT_DEFAULT     = 250;
    const RESPONSE_WIDTH_DEFAULT = '150px';

    protected $options = [];

    public function getQuestionType()
    {
        $closetext = new clozetext('clozetext', '{{response}}');

        // Define a character limit for the text entry box
        $charLimit = $this->getCharacterLimit();
        $closetext->set_max_length($charLimit);

        // Configure multiline if the character limit exceeds the base character limit
        if ($charLimit > static::CHAR_LIMIT_DEFAULT) {
            $closetext->set_multiple_line(true);
        }

        // Try to calculate the global response box width based on expected length
        $clozeResponseContainer = new clozetext_response_container();
        $clozeResponseContainer->set_width($this->getResponseWidth());
        $closetext->set_response_container($clozeResponseContainer);

        // Build validation rules if relevant
        $validation = $this->buildValidation($isCaseSensitive);
        if ($validation) {
            $closetext->set_validation($validation);
        }

        // Respect the case sensitivity determined from the validation
        $closetext->set_case_sensitive($isCaseSensitive);

        return $closetext;
    }

    /**
     * Sets all the configuration options defined in the given key-value
     * array at once.
     *
     * @param array $options - options to set
     */
    public function setOptions(array $options)
    {
        $this->options = array_merge($this->options, $options);
    }

    /**
     * Returns the response character limit used for the resulting question.
     *
     * @return int
     */
    protected function getCharacterLimit()
    {
        $charLimit = static::CHAR_LIMIT_DEFAULT;
        if ($this->isUsingExpectedLengthAsCharLimit()) {
            $charLimit = $this->interaction->getExpectedLength();
        }

        return $charLimit;
    }

    /**
     * Get a configuration option defined using setOptions($opts).
     *
     * @param  string $key          - Key for the value to get
     * @param  mixed  $defaultValue - Optional value to return if nothing is set
     *
     * @return mixed
     */
    protected function getOption($key, $defaultValue = null)
    {
        $value = $defaultValue;
        if (isset($this->options[$key])) {
            $value = $this->options[$key];
        }

        return $value;
    }

    /**
     * Returns the width used for response containers in the resulting
     * question, as a string described in pixels.
     *
     * @return string
     */
    protected function getResponseWidth()
    {
        $responseWidth = static::RESPONSE_WIDTH_DEFAULT;
        $expectedLength = $this->interaction->getExpectedLength();
        if ($expectedLength > 0 && $this->isUsingExpectedLengthAsResponseWidth()) {
            $responseWidth = (ceil($expectedLength) * 10) . 'px';
        }

        return $responseWidth;
    }

    /**
     * @return boolean true if expected length will determine response char limit
     */
    protected function isUsingExpectedLengthAsCharLimit()
    {
        return (bool)$this->getOption('expected_length_as_max_char_limit', false);
    }

    /**
     * @return boolean true if expected length will determine response container width
     */
    protected function isUsingExpectedLengthAsResponseWidth()
    {
        return (bool)$this->getOption('expected_length_as_response_width', true);
    }

    private function buildValidation(&$isCaseSensitive)
    {
        $validationBuilder = new TextEntryInteractionValidationBuilder(
            [$this->interaction->getResponseIdentifier()],
            [$this->interaction->getResponseIdentifier() => $this->responseDeclaration]
        );
        $validation = $validationBuilder->buildValidation($this->responseProcessingTemplate);
        $isCaseSensitive = $validationBuilder->isCaseSensitive();
        return $validation;
    }
}
