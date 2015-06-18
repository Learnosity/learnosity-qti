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

        //TODO: Throw all the warnings to an array
        return $closetext;
    }
}
