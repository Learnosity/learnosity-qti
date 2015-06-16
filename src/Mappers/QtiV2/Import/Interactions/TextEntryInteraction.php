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

        // TODO: template is ugly, shall we be smart and check for surrounding text so text shouldn't be
        // TODO: at item level
        // TODO: Shall ignore base (always assume 10) and patternMask
        // TODO: Shall use stringIdentifier as part of question reference
        if ($interaction->getExpectedLength()) {
            // TODO: we ignore this because this was supposed to simply provide hints so can't use `max_length`
            // TODO: since it is a validity constraint
        }
        if ($interaction->getPlaceholderText()) {
            // TODO:: No support for placeholder text
        }
        return $closetext;
    }
}
