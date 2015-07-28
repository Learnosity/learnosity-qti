<?php

namespace Learnosity\Processors\QtiV2\Out\QuestionTypes;

use Learnosity\Entities\BaseQuestionType;
use Learnosity\Entities\QuestionTypes\mcq;
use Learnosity\Entities\QuestionTypes\mcq_options_item;
use Learnosity\Entities\QuestionTypes\mcq_validation;
use Learnosity\Processors\QtiV2\In\Utils\QtiComponentUtil;
use Learnosity\Services\LogService;
use qtism\common\enums\BaseType;
use qtism\common\enums\Cardinality;
use qtism\common\utils\Format;
use qtism\data\content\Block;
use qtism\data\content\BlockCollection;
use qtism\data\content\FlowCollection;
use qtism\data\content\FlowStaticCollection;
use qtism\data\content\interactions\ChoiceInteraction;
use qtism\data\content\interactions\SimpleChoice;
use qtism\data\content\interactions\SimpleChoiceCollection;
use qtism\data\content\ItemBody;
use qtism\data\content\xhtml\text\Div;
use qtism\data\processing\ResponseProcessing;
use qtism\data\QtiComponentCollection;
use qtism\data\state\CorrectResponse;
use qtism\data\state\ResponseDeclaration;
use qtism\data\state\Value;
use qtism\data\state\ValueCollection;

class McqMapper extends AbstractQuestionTypeMapper
{
    public function convert(BaseQuestionType $questionType, $interactionIdentifier = 'RESPONSE')
    {
        /** @var mcq $question */
        $question = $questionType;
        $contentCollection = new BlockCollection();

        // Build stimulus components
        $stimulusComponents = $this->convertStimulus($question->get_stimulus());
        foreach ($stimulusComponents as $component) {
            $contentCollection->attach($component);
        }

        // Build <choiceInteraction>
        $valueIdentifierMap = [];
        $simpleChoiceCollection = new SimpleChoiceCollection();
        foreach ($question->get_options() as $option) {
            /** @var mcq_options_item $option */
            $choiceContent = new FlowStaticCollection();
            foreach (QtiComponentUtil::unmarshallElement($option->get_label()) as $component) {
                $choiceContent->attach($component);
            }

            $optionValue = $option->get_value();
            $choiceIdentifier = $this->buildChoiceIdentifier($optionValue);
            $valueIdentifierMap[$optionValue] = $choiceIdentifier;

            $choice = new SimpleChoice($choiceIdentifier);
            $choice->setContent($choiceContent);
            $simpleChoiceCollection->attach($choice);
        }
        $contentCollection->attach(new ChoiceInteraction($interactionIdentifier, $simpleChoiceCollection));

        // Build final <itemBody>, <responseDeclaration>, and its <responseProcessingTemplate>
        $itemBody = new ItemBody();
        $itemBody->setContent($contentCollection);
        list($responseDeclaration, $responseProcessingTemplate) = $this->buildResponseDeclaration(
            $interactionIdentifier,
            $valueIdentifierMap,
            $question->get_validation(),
            $question->get_multiple_responses()
        );
        return [$itemBody, $responseDeclaration, $responseProcessingTemplate];
    }

    private function buildChoiceIdentifier($originalOptionValue)
    {
        if (Format::isIdentifier($originalOptionValue, false)) {
            return $originalOptionValue;
        }
        return 'CHOICE_' . strval($originalOptionValue);
    }

    private function buildResponseDeclaration($identifier, array $valueIdentifierMap, mcq_validation $validation, $isMultipleResponse)
    {
        if (empty($validation)) {
            return [null, null];
        }
        $cardinality = ($isMultipleResponse) ? Cardinality::MULTIPLE : Cardinality::SINGLE;

        // If question only has `valid_response` with score of `1`, then it would be mapped to <correctResponse>
        // with `match_correct` template
        if (!empty($validation->get_valid_response()) &&
            empty($validation->get_alt_responses()) &&
            intval($validation->get_valid_response()->get_score()) === 1
        ) {
            $values = new ValueCollection();
            // TODO: Would this always be an array?
            foreach ($validation->get_valid_response()->get_value() as $value) {
                // TODO: Why do I have to stupidly check like this
                if (!empty($value)) {
                    $choiceIdentifier = $valueIdentifierMap[$value];
                    $values->attach(new Value($choiceIdentifier));
                }
            }

            // Build them`
            $responseDeclaration = null;
            $responseProcessing = null;
            if ($values->count() >= 1) {
                $responseDeclaration = new ResponseDeclaration($identifier, BaseType::IDENTIFIER, $cardinality);
                $responseProcessing = new ResponseProcessing();
                $responseDeclaration->setCorrectResponse(new CorrectResponse($values));
                $responseProcessing->setTemplate('http://www.imsglobal.org/question/qtiv2p1/rptemplates/match_correct.xmls');
            }
            return [$responseDeclaration, $responseProcessing];
        }

        LogService::log('Validation object could not be supported yet ~');
        // Otherwise, we would need to build the `MapResponse`
        return [null, null];
    }

    private function convertStimulus($stimulusString)
    {
        $stimulusComponents = QtiComponentUtil::unmarshallElement($stimulusString);

        // Check whether the content could all be attached as is
        $areBlockComponents = array_reduce($stimulusComponents->getArrayCopy(), function ($initial, $component) {
            return $initial && $component instanceof Block;
        }, true);
        if ($areBlockComponents) {
            return $stimulusComponents;
        }

        // Otherwise, build a `div` wrapper around it
        // This is a workaround for QTI spec restriction of <itemBody> which only allows Block objects
        LogService::log("Stimulus content would be wrapped in a `div` to workaround QTI spec restriction of `itemBody` which only allows a collection of Block objects");
        $divCollection = new FlowCollection();
        foreach ($stimulusComponents as $component) {
            $divCollection->attach($component);
        }
        $div = new Div();
        $div->setContent($divCollection);
        $collection = new QtiComponentCollection();
        $collection->attach($div);
        return $collection;
    }
}
