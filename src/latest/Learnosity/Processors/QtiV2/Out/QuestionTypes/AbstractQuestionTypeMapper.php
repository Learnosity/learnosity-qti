<?php

namespace Learnosity\Processors\QtiV2\Out\QuestionTypes;

use Learnosity\Entities\BaseQuestionType;
use Learnosity\Processors\QtiV2\Out\ContentCollectionBuilder;
use Learnosity\Utils\QtiMarshallerUtil;
use qtism\data\content\interactions\Prompt;

abstract class AbstractQuestionTypeMapper
{
    abstract public function convert(BaseQuestionType $questionType, $interactionIdentifier, $interactionLabel);

    public function getExtraContent()
    {
        return null;
    }

    protected function convertStimulusForPrompt($stimulusString)
    {
        $stimulusComponents = QtiMarshallerUtil::unmarshallElement($stimulusString);

        $prompt = new Prompt();
        $prompt->setContent(ContentCollectionBuilder::buildFlowStaticCollectionContent($stimulusComponents));
        return $prompt;
    }
}
