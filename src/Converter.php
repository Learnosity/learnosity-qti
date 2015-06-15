<?php

namespace Learnosity;

use Learnosity\Mappers\Learnosity\Export\QuestionWriter;

class Converter
{
    const INPUT_FORMAT_QTIV2P1 = 'qtiv2p1';
    const OUTPUT_FORMAT_LRN_JSON = 'json';

    public static function convertQtiItemToLearnosity($data)
    {
        $itemMapper = AppContainer::getApplicationContainer()->get('qtiv2_item_mapper');
        $itemWriter = AppContainer::getApplicationContainer()->get('learnosity_item_writer');

        list($item, $questions) = $itemMapper->parse($data);
        $itemData = $itemWriter->convert($item);

        $questionsData = [];
        foreach ($questions as $question) {
            $questionConverter = new QuestionWriter();
            $questionsData[] = $questionConverter->convert($question);
        }
        return [$itemData, $questionsData];
    }
} 
