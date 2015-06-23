<?php

namespace Learnosity\Mappers\QtiV2\Import\Interactions;

use Learnosity\Entities\QuestionTypes\longtext;
use Learnosity\Exceptions\MappingException;
use Learnosity\Mappers\QtiV2\Import\Utils\QtiComponentUtil;
use qtism\data\content\interactions\ExtendedTextInteraction as QtiExtendedTextInteraction;

class ExtendedTextInteraction extends AbstractInteraction
{
    public function getQuestionType()
    {
        /* @var QtiExtendedTextInteraction $interaction */
        $interaction = $this->interaction;
        $longtext = new longtext('longtext');

        $this->exceptions[] = new MappingException('No validation mapping supported for this interaction. Ignoring any
                <responseProcessing> and <responseDeclaration> if any');

        if (!empty($interaction->getPrompt())) {
            $promptContent = $interaction->getPrompt()->getContent();
            $longtext->set_stimulus(QtiComponentUtil::marshallCollection($promptContent));
        }

        if ($interaction->getPlaceholderText()) {
            $longtext->set_placeholder($interaction->getPlaceholderText());
        }

        /** As per QTI spec
         *  When multiple strings are accepted, expectedLength applies to each string.
         *  `expectedLength` works as a only as a 'hint' to student so we do not want to force a hard limit
         */
        if ($interaction->getExpectedLength()) {
            $maxStrings = $interaction->getMaxStrings() > 0 ? $interaction->getMaxStrings() : 1;
            $expectedLength = $interaction->getExpectedLength();
            $longtext->set_max_length($maxStrings * $expectedLength);
            $longtext->set_submit_over_limit(true);
        }

        return $longtext;
    }
}
