<?php

namespace Learnosity\Processors\QtiV2\Out\QuestionTypes;

use Learnosity\Entities\BaseQuestionType;
use Learnosity\Entities\QuestionTypes\association;
use qtism\data\content\BlockCollection;

class AssociationMapper extends AbstractQuestionTypeMapper
{
    public function convert(BaseQuestionType $questionType, $identifier = 'RESPONSE')
    {
        /** @var association $question */
        $question = $questionType;
        $contentCollection = new BlockCollection();

        // Build stimulus components
        foreach ($this->convertStimulus($question->get_stimulus()) as $component) {
            $contentCollection->attach($component);
        }

        // Build <matchInteraction>

    }
}
