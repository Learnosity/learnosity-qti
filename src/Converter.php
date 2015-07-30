<?php

namespace Learnosity;

use Learnosity\Entities\Item\item;
use Learnosity\Processors\Learnosity\In\ItemMapper;
use Learnosity\Processors\Learnosity\In\QuestionMapper;
use Learnosity\Processors\QtiV2\Out\ItemWriter;
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
        $data = json_decode($jsonString, true);

        // Guess whether this JSON is an item or a question by whether it has `type` or not
        $isQuestionJson = isset($data['type']) && is_string($data['type']);

        // Handle if just question
        if ($isQuestionJson) {
            $questionMapper = new QuestionMapper();
            $questionWriter = new QuestionWriter();
            list($xmlString, $messages) = $questionWriter->convert($questionMapper->parse($data));
            return [$xmlString, $messages];
        }

        // Handle if both item and question
        $questionsJson = $data['questions'];
        $itemJson = $data;
        $itemJson['questionReferences'] = array_column($itemJson['questions'], 'reference');
        unset($itemJson['questions']);

        // Map those bad boys to Learnosity entities
        $itemMapper = new ItemMapper();
        $questionMapper = new QuestionMapper();
        $item = $itemMapper->parse($itemJson);
        $questions = array_map(function ($questionJson) use ($questionMapper) {
            return $questionMapper->parse($questionJson);
        }, $questionsJson);

        // Write em` to QTI
        $itemWriter = new ItemWriter();
        list($xmlString, $messages) = $itemWriter->convert($item, $questions);
        return [$xmlString, $messages];
    }
}
