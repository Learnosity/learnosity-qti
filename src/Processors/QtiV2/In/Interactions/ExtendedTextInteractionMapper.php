<?php

namespace LearnosityQti\Processors\QtiV2\In\Interactions;

use LearnosityQti\Entities\QuestionTypes\longtext;
use LearnosityQti\Utils\QtiMarshallerUtil;
use LearnosityQti\Services\LogService;
use qtism\data\content\interactions\ExtendedTextInteraction as QtiExtendedTextInteraction;

class ExtendedTextInteractionMapper extends AbstractInteractionMapper
{
    public function getQuestionType()
    {
        /* @var QtiExtendedTextInteraction $interaction */
        $interaction = $this->interaction;
        $longtext = new longtext('longtextV2');

        LogService::log(
            'No validation mapping supported for this interaction. Ignoring any ' .
            '<responseProcessing> and <responseDeclaration> if any'
        );

        if (!empty($interaction->getPrompt())) {
            $promptContent = $interaction->getPrompt()->getContent();
            $longtext->set_stimulus(QtiMarshallerUtil::marshallCollection($promptContent));
        }

        if ($interaction->getPlaceholderText()) {
            $longtext->set_placeholder($interaction->getPlaceholderText());
        }

        /** As per QTI spec
         *  When multiple strings are accepted, expectedLength applies to each string.
         *  `expectedLength` works as a only as a 'hint' to student so we do not want to force a hard limit
         */
        if ($interaction->getExpectedLength() > 0) {
            $maxStrings = $interaction->getMaxStrings() > 0 ? $interaction->getMaxStrings() : 1;
            $expectedLength = $interaction->getExpectedLength() / 5;
            $longtext->set_max_length($maxStrings * $expectedLength);
            $longtext->set_submit_over_limit(true);
        }

        return $longtext;
    }
}
