<?php

namespace Learnosity\Mappers\QtiV2\Export;

use Learnosity\Entities\Item;
use Learnosity\Entities\Question;
use Learnosity\Exceptions\MappingException;
use qtism\data\AssessmentItem;
use qtism\data\storage\xml\XmlCompactDocument;

class ItemWriter
{
    public function convert(Item $item, array $questions)
    {
        // TODO: Stop development because now we want to focus on import, can do export later
        die;

        $questions = $this->getRelatedQuestions($item, $questions);

        $identifier = $item->get_reference();
        $title = !empty($item->get_description()) ? $item->get_description() : $identifier;
        $assessmentItem = new AssessmentItem($identifier, $title, false);
    }

    private function getRelatedQuestions(Item $item, array $questions)
    {
        $questionsWithReferences = [];
        foreach ($questions as $question) {
            $questionsWithReferences[$question->get_reference()] = $question;
        }

        $relatedQuestionReferences = $item->get_questionReferences();
        if (!in_array($item->get_questionReferences(), array_map('strval', array_keys($questionsWithReferences)))) {
            throw new MappingException('Missing question references', MappingException::CRITICAL);
        }

        return array_intersect($relatedQuestionReferences, $questions);
    }
}
