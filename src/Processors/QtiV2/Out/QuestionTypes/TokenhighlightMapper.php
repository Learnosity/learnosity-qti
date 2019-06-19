<?php

namespace LearnosityQti\Processors\QtiV2\Out\QuestionTypes;

use LearnosityQti\Entities\BaseQuestionType;
use LearnosityQti\Entities\QuestionTypes\tokenhighlight;
use LearnosityQti\Processors\QtiV2\Out\ContentCollectionBuilder;
use LearnosityQti\Processors\QtiV2\Out\Validation\TokenhighlightValidationBuilder;
use LearnosityQti\Utils\QtiMarshallerUtil;
use LearnosityQti\Utils\SimpleHtmlDom\SimpleHtmlDom;
use qtism\data\content\interactions\HottextInteraction;

class TokenhighlightMapper extends AbstractQuestionTypeMapper
{
    public function convert(BaseQuestionType $question, $interactionIdentifier, $interactionLabel)
    {
        $metadata = $question->get_metadata();
        $feedbackOptions = [];

        if (isset($metadata) && !empty($metadata->get_distractor_rationale())) {
            $feedbackOptions['genral_feedback'] = $metadata->get_distractor_rationale();
        }

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
        list($responseDeclaration, $responseProcessing) = $builder->buildValidation($interaction->getResponseIdentifier(), $question->get_validation(), $feedbackOptions);
        return [$interaction, $responseDeclaration, $responseProcessing];
    }
}
