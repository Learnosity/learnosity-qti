<?php

namespace Learnosity\Processors\QtiV2\Out\QuestionTypes;

use Learnosity\Entities\BaseQuestionType;
use Learnosity\Entities\QuestionTypes\orderlist;
use Learnosity\Processors\QtiV2\Out\Validation\OrderlistValidationBuilder;
use Learnosity\Utils\QtiMarshallerUtil;
use qtism\data\content\FlowStaticCollection;
use qtism\data\content\interactions\OrderInteraction;
use qtism\data\content\interactions\Orientation;
use qtism\data\content\interactions\SimpleChoice;
use qtism\data\content\interactions\SimpleChoiceCollection;

class OrderlistMapper extends AbstractQuestionTypeMapper
{
    public function convert(BaseQuestionType $questionType, $interactionIdentifier, $interactionLabel)
    {
        /** @var orderlist $question */
        $question = $questionType;

        $simpleChoiceCollection = new SimpleChoiceCollection();
        $indexIdentifiersMap = [];
        foreach ($question->get_list() as $key => $item) {
            $simpleChoice = new SimpleChoice('CHOICE_' . $key);
            $choiceContent = new FlowStaticCollection();
            foreach (QtiMarshallerUtil::unmarshallElement($item) as $component) {
                $choiceContent->attach($component);

            }
            $simpleChoice->setContent($choiceContent);
            $simpleChoiceCollection->attach($simpleChoice);
            $indexIdentifiersMap[$key] = $simpleChoice->getIdentifier();
        }

        $interaction = new OrderInteraction($interactionIdentifier, $simpleChoiceCollection);
        $interaction->setLabel($interactionLabel);
        $interaction->setPrompt($this->convertStimulusForPrompt($question->get_stimulus()));

        $interaction->setShuffle(false);
        $interaction->setOrientation(Orientation::VERTICAL);

        $builder = new OrderlistValidationBuilder($indexIdentifiersMap);
        list($responseDeclaration, $responseProcessing) = $builder->buildValidation($interactionIdentifier, $question->get_validation());

        return [$interaction, $responseDeclaration, $responseProcessing];
    }
}
