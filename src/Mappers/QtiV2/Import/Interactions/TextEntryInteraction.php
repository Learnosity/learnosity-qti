<?php

namespace Learnosity\Mappers\QtiV2\Import\Interactions;

use Learnosity\Entities\QuestionTypes\clozetext;
use qtism\data\content\interactions\TextEntryInteraction as QtiTextEntryInteraction;

class TextEntryInteraction extends AbstractInteraction
{
    public function getQuestionType()
    {
        /* @var QtiTextEntryInteraction $interaction */
        $interaction = $this->interaction;
        $closetext = new clozetext('clozetext', '{{response}}');

        /**
         * Mapping <inlineInteraction>
         */
        // TODO: Nothing here, we put everything on the item level

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
            $closetext->set_max_length($interaction->getExpectedLength());
        }
        if ($interaction->getPlaceholderText()) {
            // TODO:: No support for placeholder text
        }

        return $closetext;
    }
}
