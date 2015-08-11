<?php

namespace Learnosity\Processors\QtiV2\In\Processings;

use Learnosity\Entities\BaseQuestionType;
use Learnosity\Entities\Item\item;
use Learnosity\Entities\Question;
use qtism\data\AssessmentItem;
use qtism\data\content\Math;

class MathsProcessing implements ProcessingInterface
{
    private $hasMathML = false;

    public function processAssessmentItem(AssessmentItem $assessmentItem)
    {
        $itemBody = $assessmentItem->getItemBody();
        // TODO: Tidy up, yea remove those mathML stuffs
        foreach ($itemBody->getIterator() as $component) {
            if ($component instanceof Math) {
                $element = $component->getXml()->documentElement;
                $element->removeAttributeNS($element->namespaceURI, $element->prefix);
                $component->setXmlString($element->ownerDocument->saveHTML());
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
