<?php

namespace LearnosityQti\Processors\QtiV2\In\Processings;

use LearnosityQti\Entities\BaseQuestionType;
use LearnosityQti\Entities\Item\item;
use LearnosityQti\Entities\Question;
use qtism\data\AssessmentItem;
use qtism\data\content\Math;

class MathsProcessing implements ProcessingInterface
{
    private $hasMathML = false;

    public function processAssessmentItem(AssessmentItem $assessmentItem)
    {
        $itemBody = $assessmentItem->getItemBody();
        foreach ($itemBody->getIterator() as $component) {
            if ($component instanceof Math) {
                $element = $component->getXml()->documentElement;
                // Remove prefix if exists for conversion
                // ie. <m:math> to just <math>
                $element->removeAttributeNS($element->namespaceURI, $element->prefix);
                $component->setXmlString($element->ownerDocument->saveXML());
                // Remove MathML namespace declaration
                $component->setTargetNamespace('');
                $this->hasMathML = true;
            }
        }
        $assessmentItem->setItemBody($itemBody);
        return $assessmentItem;
    }

    public function processItemAndQuestions(item $item, array $questions)
    {
        if ($this->hasMathML) {
            /** @var question $question */
            foreach ($questions as &$question) {
                /** @var BaseQuestionType $questionType */
                $questionType = $question->get_data();
                if (method_exists($questionType, 'set_is_math')) {
                    $questionType->set_is_math(true);
                }
            }
        }
        return [$item, $questions];
    }
}
