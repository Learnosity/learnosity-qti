<?php

namespace LearnosityQti\Processors\QtiV2\Out\QuestionTypes;

use LearnosityQti\Entities\BaseQuestionType;
use LearnosityQti\Processors\QtiV2\Out\ContentCollectionBuilder;
use LearnosityQti\Utils\QtiMarshallerUtil;
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
        $stimulusString = strip_tags($stimulusString); 
        $stimulusComponents = QtiMarshallerUtil::unmarshallElement($stimulusString);
        $prompt = new Prompt();
        $prompt->setContent(ContentCollectionBuilder::buildFlowStaticCollectionContent($stimulusComponents));
        return $prompt;
    }
}
