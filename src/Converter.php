<?php

namespace Learnosity;

use Learnosity\Entities\Item\item;
use Learnosity\Processors\Learnosity\In\QuestionMapper;
use Learnosity\Processors\QtiV2\Out\QuestionWriter;

class Converter
{
    const INPUT_FORMAT_QTIV2P1 = 'qtiv2p1';
    const OUTPUT_FORMAT_LRN_JSON = 'json';

    public static function convertQtiItemToLearnosity($xmlString, $baseAssetsUrl = '')
    {
        $itemMapper = AppContainer::getApplicationContainer()->get('qtiv2_item_mapper');
        $itemWriter = AppContainer::getApplicationContainer()->get('learnosity_item_writer');
        $questionWriter = AppContainer::getApplicationContainer()->get('learnosity_question_writer');
        $assetsProcessing = AppContainer::getApplicationContainer()->get('assets_processing');
        $assetsProcessing->setBaseAssetUrl($baseAssetsUrl);

        // Parse `em
        list($item, $questions, $exceptions) = $itemMapper->parse($xmlString);

        // Conversion to JSON
        $itemData = [];
        if ($item instanceof item) {
            $itemData = $itemWriter->convert($item);
        }

        $questionsData = [];
        if (is_array($questions)) {
            foreach ($questions as $question) {
                $questionsData[] = $questionWriter->convert($question);
            }
        }

        return [$itemData, $questionsData, $exceptions];
    }

    public static function convertLearnosityToQtiItem($jsonString)
    {
        // TODO: determine whether this is item or question
        $questionMapper = new QuestionMapper();
        $question = $questionMapper->parse(json_decode($jsonString, true));
        $questionWriter = new QuestionWriter();
        list($xmlString, $messages) = $questionWriter->convert($question);
        return [$xmlString, $messages];
    }
}
