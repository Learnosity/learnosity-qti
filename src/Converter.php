<?php

namespace Learnosity;

use Learnosity\Exceptions\MappingException;
use Learnosity\Mappers\IMSCP\Entities\Manifest;
use Learnosity\Mappers\IMSCP\Entities\Resource;
use Learnosity\Mappers\IMSCP\Import\ManifestMapper;
use Learnosity\Mappers\Learnosity\Export\QuestionWriter;
use Learnosity\Utils\FileSystemUtil;

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

    public static function parseIMSCPPackage($path)
    {
        if (FileSystemUtil::getPathType($path) === FileSystemUtil::PATH_TYPE_DIRECTORY) {

            //parse imsmanifest.xml file
            $manifestFile = FileSystemUtil::readFile($path . DIRECTORY_SEPARATOR . 'imsmanifest.xml');

            /* @var $manifestMapper ManifestMapper */
            $manifestMapper = AppContainer::getApplicationContainer()->get('imscp_manifest_mapper');
            $manifest = $manifestMapper->parse($manifestFile->getContents());

            if (!($manifest instanceof Manifest)) {
                throw new MappingException('The manifest file is not valid');
            }

            $workPath = FileSystemUtil::createWorkingFolder('/tmp', 'IMSCP', $manifest->getIdentifier());


            /* @var $resource Resource */
            foreach ($manifest->getResources() as $resource) {
                $resourceType = $resource->getType();
                //todo temporary logic for processing item only
                if (strpos($resourceType, Resource::TYPE_PREFIX_ITEM) !== false) {
                    $itemXmlFile = FileSystemUtil::readFile($path . DIRECTORY_SEPARATOR . $resource->getHref());
                    list($itemData, $questionData) = self::convertQtiItemToLearnosity($itemXmlFile->getContents());
                    file_put_contents($workPath.DIRECTORY_SEPARATOR.'item_'.$itemData['reference'].'.json', json_encode($itemData,JSON_PRETTY_PRINT));
                    foreach($questionData as $q) {
                        file_put_contents($workPath.DIRECTORY_SEPARATOR.'question_'.$q['reference'].'.json', json_encode($q,JSON_PRETTY_PRINT));
                    }

                    unset($itemData);
                    unset($questionData);

                }
            }

            //parse all resources for type imsqti_item
        }
    }

} 
