<?php

namespace LearnosityQti\Processors\QtiV2\In\MergedInteractions;

use LearnosityQti\Entities\QuestionTypes\clozetext;
use LearnosityQti\Entities\QuestionTypes\clozetext_response_container;
use LearnosityQti\Utils\QtiMarshallerUtil;
use LearnosityQti\Processors\QtiV2\In\Validation\TextEntryInteractionValidationBuilder;
use qtism\data\content\interactions\TextEntryInteraction;
use qtism\data\content\ItemBody;

class MergedTextEntryInteractionMapper extends AbstractMergedInteractionMapper
{
    const MAX_CHAR_LIMIT         = 250;
    const CHAR_LIMIT_DEFAULT     = self::MAX_CHAR_LIMIT;
    const RESPONSE_WIDTH_DEFAULT = '150px';

    protected $options = [];

    private $interactionComponents;

    public function getQuestionType()
    {
        // we assume the function maintain the order of the xml element
        $this->interactionComponents = $this->itemBody->getComponentsByClassName('textEntryInteraction', true);
        $interactionXmls = [];
        $interactionIdentifiers = [];
        /** @var TextEntryInteraction $component */
        foreach ($this->interactionComponents as $component) {
            $interactionXmls[] = QtiMarshallerUtil::marshall($component);
            $interactionIdentifiers[] = $component->getResponseIdentifier();
        }

        $closetext = new clozetext('clozetext', $this->buildTemplate($this->itemBody, $interactionXmls));

        // Build validation rules if relevant
        $validation = $this->buildValidation($interactionIdentifiers, $isCaseSensitive);
        if ($validation) {
            $closetext->set_validation($validation);
        }

        // Respect the case sensitivity determined from the validation
        $closetext->set_case_sensitive($isCaseSensitive);

        // Define a sane character limit for the text entry box
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
        if ($this->isUsingExpectedLengthAsMaxCharLimit()) {
            $charLimit = $this->getExpectedLength();
        }
        $charLimit = min(static::MAX_CHAR_LIMIT, $charLimit);

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
        $expectedLength = $this->getExpectedLength();
        if ($expectedLength > 0 && $this->isUsingExpectedLengthAsResponseWidth()) {
            $responseWidth = (ceil($expectedLength) * 10) . 'px';
        }

        return $responseWidth;
    }

    /**
     * @return boolean true if expected length will determine response char limit
     */
    protected function isUsingExpectedLengthAsMaxCharLimit()
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

    /**
     * Retrieves the maximum expected length of the text entry interaction(s)
     * to be mapped.
     *
     * @return int the max expected length of all interactions, or -1 if none defined
     */
    private function getExpectedLength()
    {
        $maxExpectedLength = -1;
        /** @var TextEntryInteraction $component */
        foreach ($this->interactionComponents as $component) {
            $length = $component->getExpectedLength();
            if ($maxExpectedLength < $length) {
                $maxExpectedLength = $length;
            }
        }

        return $maxExpectedLength;
    }

    private function buildTemplate(ItemBody $itemBody, array $interactionXmls)
    {
        // Build item's HTML content
        $content = QtiMarshallerUtil::marshallCollection($itemBody->getComponents());
        foreach ($interactionXmls as $interactionXml) {
            $content = str_replace($interactionXml, '{{response}}', $content);
        }
        return $content;
    }

    public function getItemContent()
    {
        return '<span class="learnosity-response question-' . $this->questionReference . '"></span>';
    }

    public function buildValidation(array $interactionIdentifiers, &$isCaseSensitive)
    {
        $validationBuilder = new TextEntryInteractionValidationBuilder(
            $interactionIdentifiers,
            $this->responseDeclarations,
            null,
            $this->outcomeDeclarations
        );
        $validation = $validationBuilder->buildValidation($this->responseProcessingTemplate);
        $isCaseSensitive = $validationBuilder->isCaseSensitive();
        return $validation;
    }
}
