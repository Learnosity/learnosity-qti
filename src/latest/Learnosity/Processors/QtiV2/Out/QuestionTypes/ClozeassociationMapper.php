<?php

namespace Learnosity\Processors\QtiV2\Out\QuestionTypes;

use Learnosity\Entities\BaseQuestionType;
use Learnosity\Entities\QuestionTypes\clozeassociation;
use Learnosity\Processors\QtiV2\Out\ContentCollectionBuilder;
use Learnosity\Processors\QtiV2\Out\Validation\ClozeassociationValidationBuilder;
use Learnosity\Utils\QtiMarshallerUtil;
use qtism\data\content\interactions\GapChoiceCollection;
use qtism\data\content\interactions\GapMatchInteraction;
use qtism\data\content\interactions\GapText;
use qtism\data\content\TextOrVariableCollection;
use qtism\data\content\TextRun;

class ClozeassociationMapper extends AbstractQuestionTypeMapper
{
    const GAP_IDENTIFIER_PREFIX = 'GAP_';
    const GAPCHOICE_IDENTIFIER_PREFIX = 'CHOICE_';

    public function convert(BaseQuestionType $questionType, $interactionIdentifier, $interactionLabel)
    {
        //TODO: Need validation a question shall have at least 1 {{response}} and 1 item in `possible_responses`
        /** @var clozeassociation $question */
        $question = $questionType;

        // Replace {{ response }} with `gap` elements
        $index = 0;
        $template = $results = preg_replace_callback('/{{response}}/', function ($match) use (&$index) {
            $gapIdentifier = self::GAP_IDENTIFIER_PREFIX . $index;
            $replacement = '<gap identifier="' . $gapIdentifier . '"/>';
            $index++;
            return $replacement;
        }, $question->get_template());
        $content = ContentCollectionBuilder::buildBlockStaticCollectionContent(QtiMarshallerUtil::unmarshallElement($template));

        // Map `possible_responses` to gaps
        // TODO: Detect `img`
        $gapChoices = new GapChoiceCollection();
        $possibleResponses = $question->get_possible_responses();
        $matchMax = $question->get_duplicate_responses() ? count($possibleResponses) : 1;
        foreach ($possibleResponses as $index => $possibleResponse) {
            $gapChoice = new GapText(self::GAPCHOICE_IDENTIFIER_PREFIX . $index, $matchMax);
            $gapChoiceContent = new TextOrVariableCollection();
            $gapChoiceContent->attach(new TextRun($possibleResponse));
            $gapChoice->setContent($gapChoiceContent);
            $gapChoices->attach($gapChoice);
        }

        $interaction = new GapMatchInteraction($interactionIdentifier, $gapChoices, $content);
        $interaction->setLabel($interactionLabel);
        $interaction->setPrompt($this->convertStimulusForPrompt($question->get_stimulus()));

        $validationBuilder = new ClozeassociationValidationBuilder($possibleResponses);
        list($responseDeclaration, $responseProcessing) = $validationBuilder->buildValidation($interaction->getResponseIdentifier(), $question->get_validation());

        return [$interaction, $responseDeclaration, $responseProcessing];
    }
}
