<?php

namespace Learnosity;

use Learnosity\Entities\Activity\activity;
use Learnosity\Entities\Item\item;
use Learnosity\Exceptions\MappingException;
use Learnosity\Processors\IMSCP\Entities\Manifest;
use Learnosity\Processors\IMSCP\Entities\Resource;
use Learnosity\Processors\IMSCP\In\ManifestMapper;
use Learnosity\Processors\Learnosity\In\QuestionMapper;
use Learnosity\Processors\QtiV2\In\TestMapper;
use Learnosity\Processors\QtiV2\Out\QuestionWriter;
use Learnosity\Utils\FileSystemUtil;

class Converter
{
    const INPUT_FORMAT_QTIV2P1 = 'qtiv2p1';
    const OUTPUT_FORMAT_LRN_JSON = 'json';

    public static function convertQtiItemToLearnosity($xmlString)
    {
        $itemMapper = AppContainer::getApplicationContainer()->get('qtiv2_item_mapper');
        $itemWriter = AppContainer::getApplicationContainer()->get('learnosity_item_writer');
        $questionWriter = AppContainer::getApplicationContainer()->get('learnosity_question_writer');

        list($item, $questions, $exceptions) = $itemMapper->parse($xmlString);
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
        $xmlString = $questionWriter->convert($question);
        return $xmlString;
    }

    public static function parseIMSCPPackage($srcPath, $outputPath = '/tmp')
    {
        if (FileSystemUtil::getPathType($srcPath) === FileSystemUtil::PATH_TYPE_DIRECTORY) {

            // Parse imsmanifest.xml file
            $manifestFile = FileSystemUtil::readFile($srcPath . DIRECTORY_SEPARATOR . 'imsmanifest.xml');

            /* @var $manifestMapper ManifestMapper */
            $manifestMapper = AppContainer::getApplicationContainer()->get('imscp_manifest_mapper');
            $manifest = $manifestMapper->parse($manifestFile->getContents());

            if (!($manifest instanceof Manifest)) {
                throw new MappingException('The manifest file is not valid');
            }

            $workPath = FileSystemUtil::createWorkingFolder($outputPath, 'IMSCP', $manifest->getIdentifier());

            /* @var $resource Resource */
            foreach ($manifest->getResources() as $resource) {
                $resourceType = $resource->getType();
                //todo temporary logic for processing item only
                if (strpos($resourceType, Resource::TYPE_PREFIX_ITEM) !== false) {
                    $itemXmlFile = FileSystemUtil::readFile($srcPath . DIRECTORY_SEPARATOR . $resource->getHref());
                    list($itemData, $questionData) = self::convertQtiItemToLearnosity($itemXmlFile->getContents());
                    file_put_contents(
                        $workPath . DIRECTORY_SEPARATOR . 'item_' . $itemData['reference'] . '.json',
                        json_encode($itemData, JSON_PRETTY_PRINT)
                    );
                    foreach ($questionData as $q) {
                        file_put_contents(
                            $workPath . DIRECTORY_SEPARATOR . 'question_' . $q['reference'] . '.json',
                            json_encode($q, JSON_PRETTY_PRINT)
                        );
                    }

                    unset($itemData);
                    unset($questionData);
                    unset($itemXmlFile);

                } elseif (strpos($resourceType, Resource::TYPE_PREFIX_TEST) !== false) {
                    $testXmlFile = FileSystemUtil::readFile($srcPath . DIRECTORY_SEPARATOR . $resource->getHref());
                    /* @var $testMapper TestMapper */
                    $testMapper = AppContainer::getApplicationContainer()->get('qtiv2_test_mapper');

                    /* @var $activity activity */
                    list($activity, $activityItemsList) = $testMapper->parse($testXmlFile->getContents());

                    file_put_contents(
                        $workPath . DIRECTORY_SEPARATOR . 'activity_' . $activity->get_reference() . '.json',
                        json_encode($activity->to_array(), JSON_PRETTY_PRINT)
                    );

                    unset($testXmlFile);
                    unset($activity);
                    unset($activityItemsList);
                }
            }
            return $workPath;
        }
    }
}
