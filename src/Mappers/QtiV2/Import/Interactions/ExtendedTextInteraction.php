<?php

namespace Learnosity\Mappers\QtiV2\Import\Interactions;

use Learnosity\Entities\QuestionTypes\longtext;
use Learnosity\Mappers\QtiV2\Import\Utils\QtiComponentUtil;
use qtism\data\content\interactions\ExtendedTextInteraction as QtiExtendedTextInteraction;

class ExtendedTextInteraction extends AbstractInteraction
{
    public function getQuestionType()
    {
        /* @var QtiExtendedTextInteraction $interaction */
        $interaction = $this->interaction;
        $longtext = new longtext('longtext');

        /**
         * Mapping <blockInteraction>
         * Contains:
         *      prompt [0..1]
         */
        if (!empty($interaction->getPrompt())) {
            // TODO: Shall put warning on ignored maxChoice, minChoice, class, xmllang, showHide, fixed, templateIdentifier etc
            $promptContent = $interaction->getPrompt()->getContent();
            $longtext->set_stimulus(QtiComponentUtil::marshallCollection($promptContent));
        }

        /**
         * Mapping <stringInteraction>
         * Attributes:
         *      base [0..1]: integer = 10
         *      stringIdentifier [0..1]: identifier
         *      expectedLength [0..1]: integer
         *      patternMask [0..1]: string
         *      placeholderText [0..1]: string
         */
        // TODO: Shall ignore base (always assume 10) and patternMask
        // TODO: Shall use stringIdentifier as part of question reference
        if ($interaction->getExpectedLength()) {
            $longtext->set_max_length($interaction->getExpectedLength());
            $longtext->set_submit_over_limit(true);
        }
        if ($interaction->getPlaceholderText()) {
            $longtext->set_placeholder($interaction->getPlaceholderText());
        }

        /**
         * Mapping <extendedTextInteraction>
         * Attributes:
         *      maxStrings [0..1]: integer
         *      expectedLines [0..1]: integer
         */
        // TODO: Shall ignore maxString, we only support 1 string (textbox) thus always assume 1
        // TODO: Shall ignore expectedLines, we can't provide hints based on expectedLines
        // TODO: but yes, we can roughly calculate min/max textbox height based on this?

        /**
         * Other assumptions:
         *      `expectedLength` works as a only as a 'hint' to student so we do not want to force a hard limit
         */
        $longtext->set_submit_over_limit(false);

        return $longtext;
    }
}
