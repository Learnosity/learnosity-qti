<?php

namespace Learnosity\Processors\QtiV2\Out\QuestionTypes;

use Learnosity\Entities\BaseQuestionType;
use Learnosity\Entities\QuestionTypes\tokenhighlight;
use Learnosity\Processors\QtiV2\Out\ContentCollectionBuilder;
use Learnosity\Processors\QtiV2\Out\Validation\TokenhighlightValidationBuilder;
use Learnosity\Utils\QtiMarshallerUtil;
use Learnosity\Utils\SimpleHtmlDom\SimpleHtmlDom;
use qtism\data\content\interactions\HottextInteraction;

class TokenhighlightMapper extends AbstractQuestionTypeMapper
{
    public function convert(BaseQuestionType $question, $interactionIdentifier, $interactionLabel)
    {
        /** @var tokenhighlight $question */
        // Grab those `template` and convert those highlights to <hottext>
        $html = new SimpleHtmlDom();
        $html->load($question->get_template());
        $tokens = $html->find('span.lrn_token');
        $indexIdentifierMap = [];
        foreach ($tokens as $key => &$span) {
            $span->outertext = '<hottext identifier="TOKEN_' . intval($key) . '">' .  $span->innertext . '</hottext>';
            $indexIdentifierMap[$key] = 'TOKEN_' . intval($key);
        }
        $htmlContent = $html->save();
        $contentComponents = QtiMarshallerUtil::unmarshallElement($htmlContent);
        $contentCollection = ContentCollectionBuilder::buildBlockStaticCollectionContent($contentComponents);

        // Build the interaction
        $interaction = new HottextInteraction($interactionIdentifier, $contentCollection);
        $interaction->setLabel($interactionLabel);
        $interaction->setPrompt($this->convertStimulusForPrompt($question->get_stimulus()));

        // Learnosity does not enforce number of choices, thus using default such the min choice would be 1
        // and max would be the `max_selection` if set, otherwise use token count
        $interaction->setMinChoices(1);
        $interaction->setMaxChoices(is_int($question->get_max_selection()) ? $question->get_max_selection() : count($tokens));

        // Build validation
        $builder = new TokenhighlightValidationBuilder($indexIdentifierMap);
        list($responseDeclaration, $responseProcessing) = $builder->buildValidation($interaction->getResponseIdentifier(), $question->get_validation());
        return [$interaction, $responseDeclaration, $responseProcessing];
    }
}
