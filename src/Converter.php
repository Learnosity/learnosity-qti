<?php

namespace LearnosityQti;

use Exception;
use LearnosityQti\Entities\Item\item;
use LearnosityQti\Entities\Question;
use LearnosityQti\Exceptions\InvalidQtiException;
use LearnosityQti\Exceptions\MappingException;
use LearnosityQti\Processors\IMSCP\In\ManifestMapper;
use LearnosityQti\Processors\IMSCP\Out\ManifestWriter;
use LearnosityQti\Processors\Learnosity\In\ItemMapper;
use LearnosityQti\Processors\Learnosity\In\QuestionMapper;
use LearnosityQti\Processors\QtiV2\In\TestMapper;
use LearnosityQti\Processors\QtiV2\In\SharedPassageMapper;
use LearnosityQti\Processors\QtiV2\Out\ItemWriter;
use LearnosityQti\Processors\QtiV2\Out\QuestionWriter;
use LearnosityQti\Services\LearnosityToQtiPreProcessingService;
use LearnosityQti\Services\LogService;
use LearnosityQti\Utils\FileSystemUtil;
use LearnosityQti\Utils\StringUtil;
use LearnosityQti\Utils\SimpleHtmlDom\SimpleHtmlDom;
use qtism\data\storage\xml\XmlDocument;
use qtism\data\storage\xml\XmlStorageException;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

class Converter
{
    const INPUT_FORMAT_QTIV2P1 = 'qtiv2p1';
    const OUTPUT_FORMAT_LRN_JSON = 'json';

    const LEARNOSITY_DATA_ITEM = 'item';
    const LEARNOSITY_DATA_QUESTION = 'question';
    const LEARNOSITY_DATA_QUESTION_DATA = 'questiondata';

    public static function convertImscpDirectoryToLearnosityDirectory($imscpDirectory, $learnosityDirectory, $baseAssetsUrl = '', $validate = true)
    {
        // Brute extract all images from the target IMSCP folder
        // TODO: Need more than just jpg and gif
        $learnosityImagesDirectory = $learnosityDirectory . '/Images';
        FileSystemUtil::createOrReplaceDir($learnosityImagesDirectory);
        $allImages = array_merge(
            self::extractFiles('*.jpg', $imscpDirectory, $learnosityImagesDirectory),
            self::extractFiles('*.jpeg', $imscpDirectory, $learnosityImagesDirectory),
            self::extractFiles('*.gif', $imscpDirectory, $learnosityImagesDirectory)
        );

        // Brute extract all the xml excepts imsmanifest.xml
        $itemReferences = [];
        $failedQtiXmlFilename = [];

        $learnosityJsonDirectory = $learnosityDirectory . '/Json';
        FileSystemUtil::createOrReplaceDir($learnosityJsonDirectory);
        $finder = new Finder();
        /** @var SplFileInfo $file */
        foreach ($finder->files()->in($imscpDirectory)->name('*.xml') as $file) {
            $filename = $file->getFilename();
            if ($filename === 'imsmanifest.xml') {
                continue;
            }
            $resultPath = $learnosityJsonDirectory . '/' . basename($filename, '.xml') . '.json';
            try {
                // Write the JSON result to a folder named with its original XML filename
                // FIXME: This will fail now that the signature of the following method has changed significantly
                list($item, $questions, $manifest) = self::convertQtiItemToLearnosity($file->getContents(), $baseAssetsUrl, $validate);
                $result = [
                    'meta' => [
                        'status' => 'success',
                        'manifest' => $manifest
                    ],
                    'data' => [
                        'item' => $item,
                        'questions' => $questions,
                    ]
                ];
                FileSystemUtil::writeJsonToFile($result, $resultPath);
                $itemReferences[] = $item['reference'];
            } catch (Exception $e) {
                $result = [
                    'meta' => [
                        'status' => 'failed',
                        'message' => $e->getMessage()
                    ],
                    'data' => []
                ];
                FileSystemUtil::writeJsonToFile($result, $resultPath);
                $failedQtiXmlFilename[] = $filename;
            }
        }
        return $itemReferences;
    }

    private static function extractFiles($filename, $searchDirectory, $resultDirectory)
    {
        $filenames = [];
        $finder = new Finder();
        /** @var SplFileInfo $file */
        foreach ($finder->files()->in($searchDirectory)->name($filename) as $file) {
            copy($file->getPathname(), $resultDirectory . '/' . $file->getFilename());
            $filenames[] = $file->getFilename();
        }
        return $filenames;
    }

    public static function convertQtiManifestToLearnosity($xmlString, array $rules = [])
    {
        /** @var ManifestMapper $manifestMapper */
        $manifestMapper = AppContainer::getApplicationContainer()->get('imscp_manifest_mapper');
        /** @var ManifestWriter $manifestWriter */
        $manifestWriter = AppContainer::getApplicationContainer()->get('learnosity_manifest_writer');

        try {
            LogService::flush();

            $manifest = $manifestMapper->parse($xmlString);
            list($activities, $activitiesTags, $itemsTags) = $manifestWriter->convert($manifest, $rules);

            $messages = LogService::flush();
            return [$activities, $activitiesTags, $itemsTags, $messages];
        } catch (Exception $e) {
            throw new MappingException($e->getMessage());
        }
    }

    public static function convertQtiTestToLearnosity($xmlString, $validate = true)
    {
        $testMapper = AppContainer::getApplicationContainer()->get('qtiv2_test_mapper');
        $activityWriter = AppContainer::getApplicationContainer()->get('learnosity_activity_writer');

        try {
            /** @var TestMapper $testMapper */
            list($activity, $exceptions) = $testMapper->parse($xmlString, $validate);
        } catch (XmlStorageException $e) {
            // Check invalid schema error message and intercept to rethrow as known `InvalidQtiException` exception
            $exceptionMessage = $e->getMessage();
            if (StringUtil::startsWith($exceptionMessage, 'The document could not be validated with schema')) {
                $exceptionMessage = preg_replace('/The document could not be validated with schema(.*)/', 'The document could not be validated with standard QTI schema: ', $exceptionMessage);
                throw new InvalidQtiException($exceptionMessage);
            } else {
                throw $e;
            }
        }

        $activityData = $activityWriter->convert($activity);
        return [$activityData, $exceptions];
    }

    public static function convertQtiPassageToLearnosity($xmlString)
    {
        $widgetWriter = AppContainer::getApplicationContainer()->get('learnosity_question_writer');
        $passageMapper = new SharedPassageMapper();

        $widget = null;
        // Parse `em
        try {
            $result = $passageMapper->parseXml($xmlString);
            if (!empty($result['features'])) {
                $widget = array_values($result['features'])[0];
            }
        } catch (XmlStorageException $e) {
            // Check invalid schema error message and intercept to rethrow as known `InvalidQtiException` exception
            $exceptionMessage = $e->getMessage();
            if (StringUtil::startsWith($exceptionMessage, 'The document could not be validated with XML Schema')) {
                $exceptionMessage = preg_replace('/The document could not be validated with schema(.*)/', 'The document could not be validated with standard QTI schema: ', $exceptionMessage);
                throw new InvalidQtiException($exceptionMessage);
            } else {
                throw $e;
            }
        }

        // Conversion to JSON
        $widgetData = [];
        if ($widget instanceof Question) {
            $widgetData = $widgetWriter->convert($widget);
        }

        return [$widgetData, $exceptions];
    }

    public static function convertQtiItemToLearnosity($xmlString, $baseAssetsUrl = '', $validate = true, $filePath = null, $customItemReference = null, $metadata = [])
    {
        $itemMapper = AppContainer::getApplicationContainer()->get('qtiv2_item_mapper');
        $itemWriter = AppContainer::getApplicationContainer()->get('learnosity_item_writer');
        $questionWriter = AppContainer::getApplicationContainer()->get('learnosity_question_writer');

        // Parse `em
        try {
            $sourceDirectoryPath = null;
            if (isset($filePath)) {
                $sourceDirectoryPath = dirname($filePath);
            }
            // TODO: Handle additional (related) items being passed back
            $result = $itemMapper->parse($xmlString, $validate, $sourceDirectoryPath, $metadata);
            $item = $result['item'];
            $questions = $result['questions'];
            $features = $result['features'];
            $messages = $result['messages'];
            $rubricItem = isset($result['rubric']) ? $result['rubric'] : null;
            if (!empty($customItemReference)) {
                $item->set_reference($customItemReference);
            }
            if (!empty($rubricItem)) {
                $rubricItem->set_reference($item->get_reference().'_rubric');
                foreach ($questions as $question) {
                    $questionMetadata = $question->get_data()->get_metadata();
                    if (!empty($questionMetadata)) {
                        $questionMetadata->set_rubric_reference($rubricItem->get_reference());
                    }
                }
            }
        } catch (XmlStorageException $e) {
            // Check invalid schema error message and intercept to rethrow as known `InvalidQtiException` exception
            $exceptionMessage = $e->getMessage();
            if (StringUtil::startsWith($exceptionMessage, 'The document could not be validated with XML Schema')) {
                $exceptionMessage = preg_replace('/The document could not be validated with schema(.*)/', 'The document could not be validated with standard QTI schema: ', $exceptionMessage);
                throw new InvalidQtiException($exceptionMessage);
            } else {
                throw $e;
            }
        }

        // Conversion to JSON
        $itemData = [];
        if ($item instanceof item) {
            $itemData = $itemWriter->convert($item);
        }

        // Support additional (related) items being passed back
        $rubricItemData = [];
        if ($rubricItem instanceof item) {
            $rubricItemData = $itemWriter->convert($rubricItem);
        }

        $questionsData = [];
        if (is_array($questions)) {
            foreach ($questions as $question) {
                $questionsData[] = $questionWriter->convert($question);
            }
        }
        $featuresData = [];
        if (is_array($features)) {
            foreach ($features as $feature) {
                $featuresData[] = $questionWriter->convert($feature);
            }
        }

        return [
            'item' => $itemData,
            'questions' => $questionsData,
            'features' => $featuresData,
            'messages' => $messages,
            'rubric' => $rubricItemData,
        ];
    }

    public static function convertLearnosityToQtiItem(array $data)
    {
        $jsonType = self::guessLearnosityJsonDataType($data);

        // Handle `item` which contains both a single item and one or more questions/features
        if ($jsonType === self::LEARNOSITY_DATA_ITEM) {
            list($xmlString, $messages) = self::convertLearnosityItem($data);
        // Handle if just question
        } else if ($jsonType === self::LEARNOSITY_DATA_QUESTION) {
            list($xmlString, $messages) = self::convertLearnosityQuestion($data);
        // Handle if just question data
        } else if ($jsonType === self::LEARNOSITY_DATA_QUESTION_DATA) {
            list($xmlString, $messages) = self::convertLearnosityQuestionData($data);
        } else {
            throw new \Exception('Unknown JSON format');
        }

        // Validate them before proceeding by feeding it back
        try {
            $document = new XmlDocument();
            $document->loadFromString($xmlString);
        } catch (\Exception $e) {
            LogService::log('Unknown error occurred. The QTI XML produced may not be valid');
        }

        return [$xmlString, $messages];
    }

    private static function convertLearnosityQuestion(array $questionJson)
    {
        $preprocessingService = new LearnosityToQtiPreProcessingService();
        $questionMapper = new QuestionMapper();
        $questionWriter = new QuestionWriter();

        $question = $questionMapper->parse($preprocessingService->processJson($questionJson));
        return $questionWriter->convert($question);
    }

    private static function convertLearnosityQuestionData(array $questionDataJson)
    {
        $preprocessingService = new LearnosityToQtiPreProcessingService();
        $questionMapper = new QuestionMapper();
        $questionWriter = new QuestionWriter();

        $question = $questionMapper->parseDataOnly($preprocessingService->processJson($questionDataJson));
        return $questionWriter->convert($question);
    }

    private static function convertLearnosityItem(array $itemJson)
    {
        // Separate question(s) and item
        $itemJson['questionReferences'] = array_column($itemJson['questions'], 'reference');
        $questionsJson = $itemJson['questions'];
        unset($itemJson['questions']);

        // Pre-process these JSON
        $preprocessingService = new LearnosityToQtiPreProcessingService($questionsJson);
        $questionsJson = $preprocessingService->processJson($questionsJson);
        $itemJson = $preprocessingService->processJson($itemJson);

        // Map those bad boys to Learnosity entities
        $itemMapper = new ItemMapper();
        $questionMapper = new QuestionMapper();
        $item = $itemMapper->parse($itemJson);
        $questions = [];
        foreach ($questionsJson as $question) {
            if (!in_array($question['data']['type'], ['audioplayer', 'videoplayer', 'sharedpassage'])) {
                $questions[] = $questionMapper->parse($question);
            }
        }

        // Write em` to QTI
        $itemWriter = new ItemWriter();
        return $itemWriter->convert($item, $questions);
    }

    private static function guessLearnosityJsonDataType(array $data)
    {
        if ($data == null) {
            throw new MappingException('Invalid JSON');
        }

        // Guess this JSON is an `item`
        if (!isset($data['type'])) {
            if (!isset($data['reference']) && !isset($data['content'])) {
                throw new MappingException('Invalid `item` JSON. Neither `reference` nor `content` shall not be empty');
            }
            return self::LEARNOSITY_DATA_ITEM;
        }

        // Guess this JSON is a `question`
        if (isset($data['data'])) {
            if (!isset($data['reference'])) {
                throw new MappingException('Invalid `item` JSON. Key `reference` shall not be empty');
            }
            return self::LEARNOSITY_DATA_QUESTION;
        }

        // Guess this JSON is a `questiondata`
        return self::LEARNOSITY_DATA_QUESTION_DATA;
    }
}
