<?php

namespace Learnosity;

use Learnosity\Entities\Item\item;
use Learnosity\Exceptions\MappingException;
use Learnosity\Processors\Learnosity\In\ItemMapper;
use Learnosity\Processors\Learnosity\In\QuestionMapper;
use Learnosity\Processors\QtiV2\Out\ItemWriter;
use Learnosity\Processors\QtiV2\Out\QuestionWriter;
use Learnosity\Services\LearnosityToQtiPreProcessingService;
use Learnosity\Services\LogService;
use qtism\data\storage\xml\XmlDocument;

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

    public static function convertLearnosityToQtiItem(array $data)
    {
        if ($data == null) {
            throw new MappingException('Invalid JSON');
        }

        // Guess whether this JSON is an item or a question by whether it has `type` or not
        $isQuestionJson = isset($data['type']) && is_string($data['type']);

        // Handle if just question
        if ($isQuestionJson) {
            $questionMapper = new QuestionMapper();
            $questionWriter = new QuestionWriter();

            // Pre-process question JSON to strips out common known issues
            $preprocessingService = new LearnosityToQtiPreProcessingService();
            list($processedQuestionJson) = $preprocessingService->process([$data]);
            list($xmlString, $messages) = $questionWriter->convert($questionMapper->parse($processedQuestionJson[0]));
            return [$xmlString, $messages];
        }

        // Handle if both item and question
        $questionsJson = $data['questions'];
        $itemJson = $data;
        $itemJson['questionReferences'] = array_column($itemJson['questions'], 'reference');
        unset($itemJson['questions']);

        // Pre-process these JSON
        // ie. strips out common HTML issues such closing <br> <img> tags, transform scrolling passage, &nbsp; replacement, etc
        $preprocessingService = new LearnosityToQtiPreProcessingService();
        list($questionsJson, $itemJson) = $preprocessingService->process($questionsJson, $itemJson);

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

        // Validate them before proceeding by feeding it back
        try {
            $document = new XmlDocument();
            $document->loadFromString($xmlString);
        } catch (\Exception $e) {
            LogService::log('Unknown error occurred. The QTI XML produced may not be valid');
        }

        return [$xmlString, $messages];
    }
}
