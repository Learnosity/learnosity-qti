<?php

namespace Learnosity\Processors\QtiV2\In\MergedInteractions;

use Learnosity\Entities\QuestionTypes\clozetext;
use Learnosity\Exceptions\MappingException;
use Learnosity\Processors\QtiV2\In\ResponseProcessingTemplate;
use Learnosity\Processors\QtiV2\In\Utils\QtiComponentUtil;
use Learnosity\Processors\QtiV2\In\Validation\MergedTextEntryInteractionValidationBuilder;
use Learnosity\Processors\QtiV2\In\Validation\TextEntryInteractionValidationBuilder;
use Learnosity\Utils\ArrayUtil;
use qtism\data\content\interactions\TextEntryInteraction;
use qtism\data\content\ItemBody;
use qtism\data\state\MapEntry;
use qtism\data\state\ResponseDeclaration;

class MergedTextEntryInteractionMapper extends AbstractMergedInteractionMapper
{
    private $interactionComponents;

    public function getQuestionType()
    {
        // we assume the function maintain the order of the xml element
        $this->interactionComponents = $this->itemBody->getComponentsByClassName('textEntryInteraction', true);
        $interactionXmls = [];
        $interactionIdentifiers = [];
        /** @var TextEntryInteraction $component */
        foreach ($this->interactionComponents as $component) {
            $interactionXmls[] = QtiComponentUtil::marshall($component);
            $interactionIdentifiers[] = $component->getResponseIdentifier();
        }
        $validation = $this->buildValidation($interactionIdentifiers, $isCaseSensitive);
        $closetext = new clozetext('clozetext', $this->buildTemplate($this->itemBody, $interactionXmls));
        if ($validation) {
            $closetext->set_validation($validation);
        }
        $isMultiLine = false;
        $maxLength = $this->getExpectedLength($isMultiLine);
        $closetext->set_max_length($maxLength);
        $closetext->set_multiple_line($isMultiLine);
        $closetext->set_case_sensitive($isCaseSensitive);
        return $closetext;
    }

    private function getExpectedLength(&$isMultiLine)
    {
        $maxExpectedLength = -1;
        /** @var TextEntryInteraction $component */
        foreach ($this->interactionComponents as $component) {
            $length = $component->getExpectedLength();
            if ($maxExpectedLength < $length) {
                $maxExpectedLength = $length;
            }
        }
        if ($maxExpectedLength > 250) {
            $maxExpectedLength = 250;
            $isMultiLine = true;
        }
        return $maxExpectedLength;
    }

    private function buildTemplate(ItemBody $itemBody, array $interactionXmls)
    {
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

    public function buildValidation(array $interactionIdentifiers, &$isCaseSensitive)
    {
        if (!($this->responseProcessingTemplate instanceof ResponseProcessingTemplate)) {
            $this->exceptions[] =
                new MappingException(
                    'Response declaration is not defined',
                    MappingException::CRITICAL
                );
            return null;
        }

        $validationBuilder = new MergedTextEntryInteractionValidationBuilder(
            $this->responseProcessingTemplate,
            $this->responseDeclarations,
            'clozetext'
        );
        $validationBuilder->init($interactionIdentifiers);
        $validation = $validationBuilder->buildValidation();
        $this->exceptions = array_merge($this->exceptions, $validationBuilder->getExceptions());
        $isCaseSensitive = $validationBuilder->isCaseSensitive();
        return $validation;
    }
}
