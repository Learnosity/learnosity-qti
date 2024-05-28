<?php

/** @noinspection HttpUrlsUsage */

namespace LearnosityQti\Services;

use DOMDocument;
use DOMElement;
use DOMException;
use Exception;
use LearnosityQti\Converter;
use LearnosityQti\Domain\JobDataTrait;
use LearnosityQti\Processors\IMSCP\Entities\File;
use LearnosityQti\Processors\IMSCP\Entities\ImsManifestMetadata;
use LearnosityQti\Processors\IMSCP\Entities\Manifest;
use LearnosityQti\Processors\IMSCP\Entities\Resource;
use LearnosityQti\Processors\QtiV2\Out\Constants as LearnosityExportConstant;
use LearnosityQti\Utils\General\FileSystemHelper;
use LearnosityQti\Utils\UuidUtil;
use LearnosityQti\Utils\MimeUtil;
use RecursiveIteratorIterator;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\Iterator\RecursiveDirectoryIterator;
use Symfony\Component\Finder\SplFileInfo;
use ZipArchive;

class ConvertToQtiService
{
    use JobDataTrait;

    const INFO_OUTPUT_PREFIX = '';
    const MANIFEST_FILE_NAME = 'imsmanifest.xml';
    const IMS_CONTENT_PACKAGE_NAME = 'qti.zip';
    const IMS_XSD_LOCATION = 'http://www.imsglobal.org/xsd/imscp_v1p1 http://www.imsglobal.org/xsd/qti/qtiv2p1/qtiv2p1_imscpv1p2_v1p0.xsd http://www.w3.org/Math/XMLSchema/mathml2/mathml2.xsd';
    const IMS_XMLNS_LOCATION = 'http://www.imsglobal.org/xsd/imscp_v1p1';
    const IMS_IMSMD_LOCATION = 'http://ltsc.ieee.org/xsd/LOM';
    const IMS_IMSQTI_LOCATION = 'http://www.imsglobal.org/xsd/imsqti_metadata_v2p1';
    const IMS_XSI_LOCATION = 'http://www.w3.org/2001/XMLSchema-instance';
    const SHARED_PASSAGE_FOLDER_NAME = '';

    protected string $inputPath;
    protected string $outputPath;
    protected OutputInterface $output;
    protected mixed $format;
    protected string $finalPath;
    protected string $logPath;
    protected string $rawPath;
    protected mixed $organisationId;
    protected array $itemReferences;

    /* Runtime options */
    protected bool $dryRun   = false;
    private static $instance = null;

    private function __construct(
        $inputPath,
        $outputPath,
        OutputInterface $output,
        $format,
        $organisationId = null
    ) {
        $this->inputPath      = $inputPath;
        $this->outputPath     = $outputPath;
        $this->output         = $output;
        $this->format         = $format;
        $this->organisationId = $organisationId;
        $this->finalPath      = 'final';
        $this->logPath        = 'log';
        $this->rawPath        = 'raw';
        $this->itemReferences = array();
    }

    // The object is created from within the class itself
    // only if the class has no instance.
    public static function initClass(
        $inputPath,
        $outputPath,
        OutputInterface $output,
        $organisationId = null
    ): ?ConvertToQtiService {
        if (!self::$instance) {
            self::$instance = new ConvertToQtiService($inputPath, $outputPath, $output, $organisationId);
        }

        return self::$instance;
    }

    // Return instance of the class
    public static function getInstance()
    {
        return self::$instance;
    }

    /** @noinspection PhpUnused */
    public function getInputPath(): string
    {
        return $this->inputPath;
    }

    public function getFormat()
    {
        return $this->format;
    }

    public function process(): array
    {
        $errors = $this->validate();
        $result = [
            'status' => null,
            'message' => []
        ];

        if (!empty($errors)) {
            $result['status'] = false;
            $result['message'] = $errors;
            return $result;
        }

        // Setup output (or -o) subdirectories
        FileSystemHelper::createDirIfNotExists($this->outputPath . '/' . $this->finalPath);
        FileSystemHelper::createDirIfNotExists($this->outputPath . '/' . $this->logPath);
        FileSystemHelper::createDirIfNotExists($this->outputPath . '/' . $this->rawPath);

        $this->createAdditionalFolder($this->outputPath . '/' . $this->rawPath);

        $result = $this->parseContent();

        $this->tearDown();

        return $result;
    }

    /**
     * Creates various multimedia directory for storing image,audio,video and
     * QTI XML files.
     *
     * @param string $basePath basepath for creating directory
     */
    public function createAdditionalFolder(string $basePath): void
    {

        FileSystemHelper::createDirIfNotExists($basePath . '/' . LearnosityExportConstant::DIRNAME_AUDIO);
        FileSystemHelper::createDirIfNotExists($basePath . '/' . LearnosityExportConstant::DIRNAME_VIDEO);
        FileSystemHelper::createDirIfNotExists($basePath . '/' . LearnosityExportConstant::DIRNAME_IMAGES);
        FileSystemHelper::createDirIfNotExists($basePath . '/' . LearnosityExportConstant::DIRNAME_ITEMS);
        FileSystemHelper::createDirIfNotExists($basePath . '/' . LearnosityExportConstant::SHARED_PASSAGE_FOLDER_NAME);
        $this->copyAllAssetFiles($this->inputPath . '/' . 'assets', $basePath);
    }

    /**
     * Copy each files from assets folder to their respective folder
     *
     * @param string $sourcePath source path of the directory
     * @param string $destinationPath destination directory for copy files
     */
    public function copyAllAssetFiles(
        string $sourcePath,
        string $destinationPath
    ): void {
        $dir = opendir($sourcePath);

        while (($file = readdir($dir)) !== false) {
            if (!in_array($file, ['.', '..'])) {
                $mimeTypeArray = explode('/', MimeUtil::guessMimeType($file));
                if (is_array($mimeTypeArray) && !empty($mimeTypeArray[0])) {
                    $this->copyMediaFilesInFolder($mimeTypeArray[0], $file, $sourcePath, $destinationPath);
                }
            }
        }
    }

    /**
     * This function will copy the files from source folder to destination
     * folder.
     *
     * @param string $mediaType type of the media like jpeg,mp4,mp3 etc
     * @param string $file File to be moved
     * @param string $sourcePath source folder
     * @param string $destinationPath destination folder path for copying
     */
    public function copyMediaFilesInFolder(
        string $mediaType,
        string $file,
        string $sourcePath,
        string $destinationPath
    ): void {
        if ($mediaType == 'audio') {
            FileSystemHelper::copyFiles($sourcePath . '/' . $file, $destinationPath . '/' . LearnosityExportConstant::DIRNAME_AUDIO . '/' . $file);
        } elseif ($mediaType == 'video') {
            FileSystemHelper::copyFiles($sourcePath . '/' . $file, $destinationPath . '/' . LearnosityExportConstant::DIRNAME_VIDEO . '/' . $file);
        } elseif ($mediaType == 'image') {
            FileSystemHelper::copyFiles($sourcePath . '/' . $file, $destinationPath . '/' . LearnosityExportConstant::DIRNAME_IMAGES . '/' . $file);
        } else {
            $this->output->writeln("<error>Media Type not supported only audio, video and image are supported</error>");
        }
    }

    /**
     * Decorate the IMS root element of the Manifest with the appropriate
     * namespaces and schema definition.
     *
     * @param DOMElement $rootElement The root DOMElement object of the document
     *                                to decorate.
     */
    protected function decorateImsManifestRootElement(DOMElement $rootElement): void
    {
        $rootElement->setAttribute('xmlns', static::IMS_XMLNS_LOCATION);
        $rootElement->setAttribute('xmlns:imsmd', static::IMS_IMSMD_LOCATION);
        $rootElement->setAttribute('xmlns:imsqti', static::IMS_IMSQTI_LOCATION);
        $rootElement->setAttribute("xmlns:xsi", static::IMS_XSI_LOCATION);
        $rootElement->setAttribute("xsi:schemaLocation", static::IMS_XSD_LOCATION);
    }

    /**
     * Performs a conversion on each directory (one level deep) inside the given
     * source directory.
     */
    private function parseContent(): array
    {
        $result = [
            'status' => null,
            'message' => []
        ];

        try {
            $results = [];
            $jsonFiles = $this->parseInputFolders();
            $finalManifest = $this->getJobManifestTemplate();
            $this->output->writeln(
                "<info>"
                . static::INFO_OUTPUT_PREFIX
                . "Processing JSON directory: $this->inputPath </info>\n"
            );

            foreach ($jsonFiles as $file) {
                if (file_exists($file)) {
                    $results[] = $this->convertLearnosityInDirectory($file);
                } else {
                    $this->output->writeln(
                        "<info>"
                        . static::INFO_OUTPUT_PREFIX
                        . "Learnosity JSON file "
                        . basename($file)
                        . " Not found in: $this->inputPath/items </info>"
                    );
                }
            }

            $resourceInfo = $this->updateJobManifest($finalManifest, $results);
            $finalManifest->setResources($resourceInfo);
            $this->persistResultsFile($results, realpath($this->outputPath) . '/' . $this->rawPath . '/');
            $this->flushJobManifest($finalManifest, $results);
            $this->createIMSContentPackage(realpath($this->outputPath) . '/' . $this->rawPath . '/');
        } catch (Exception $e) {
            $result['status'] = false;
            $result['message'] = $e->getMessage();
        }

        return $result;
    }

    /**
     * Performs a conversion on QTI content packages found in the given root source directory.
     *
     * @param $file
     *
     * @return array - the results of the conversion
     * @throws Exception
     */
    private function convertLearnosityInDirectory($file): array
    {
        $this->output->writeln("<comment>Converting Learnosity JSON $file</comment>");
        $itemContent = file_get_contents($file);
        return $this->convertAssessmentItem(json_decode($itemContent, true));
    }

    // Traverse the -i option and find all paths with files
    private function parseInputFolders(): array
    {
        $folders = [];
        // Look for json files in the current path
        $finder = new Finder();
        $finder->files()->in($this->inputPath . '/activities');
        if ($finder->count() > 0) {
            foreach ($finder as $json) {
                $activityJson = json_decode(file_get_contents($json));
                $this->itemReferences = $activityJson->data->items;
                if (!empty($this->itemReferences)) {
                    foreach ($this->itemReferences as $itemReference) {
                        if (isset($itemReference->id) && is_object($itemReference)) {
                            $itemReference = md5($itemReference->id);
                        } else {
                            $itemReference = md5($itemReference);
                        }
                        $folders[] = $this->inputPath . '/items/' . $itemReference . '.json';
                    }
                } else {
                    $this->output->writeln(
                        "<error>Error converting : No item references found in the activity json</error>"
                    );
                }
            }
        } else {
            $finder->files()->in($this->inputPath . '/items');
            foreach ($finder as $json) {
                $folders[] = $this->inputPath . '/items/' . $json->getRelativePathname();
            }
        }

        return $folders;
    }

    /**
     * Converts Learnosity JSON to QTI
     *
     * @param array $json
     *
     * @return array - the results of the conversion
     *
     * @throws Exception - if the conversion fails
     * @noinspection DuplicatedCode
     */
    private function convertAssessmentItem(array $json): array
    {
        $finalXml = [];
        $tagsArray = [];
        $content = $json['content'];
        $features = $json['features'];
        $tags = $json['tags'];
        $itemReference = $json['reference'];

        if (!empty($json['questions']) && (sizeof($features)>=1)) {
            $referenceArray = $this->getReferenceArray($json);
            foreach ($json['questions'] as $question) :
                $question['content'] = $content;
                $question['itemreference'] = $itemReference;
                $featureReference = $this->getFeatureReference($question['reference'], $referenceArray);
                if ($featureReference != "") {
                    $question['feature'] = $this->getFeature($featureReference, $features);
                } else {
                    $question['feature'] = [];
                }

                if (in_array($question['data']['type'], LearnosityExportConstant::$supportedQuestionTypes)) {
                    $result = Converter::convertLearnosityToQtiItem($question);

                    if (!$result) {
                        $this->output->writeln("<error>Unkown error with `{$question['data']['type']}`, ignoring</error>");
                        continue;
                    }

                    $result[0] = str_replace('/vendor/learnosity/itembank/', '', $result[0]);
                    $result[0] = str_replace('xmlns:default="http://www.w3.org/1998/Math/MathML"', '', $result[0]);
                    //TODO: Change this to only select MathML elements?
                    $result[0] = str_replace('<default:', '<', $result[0]);
                    $result[0] = str_replace('</default:', '</', $result[0]);
                    $finalXml['questions'][] = $result;
                    $tagsArray[$question['reference']] = $tags;
                } else {
                    $this->output->writeln(
                        "<error>Question type `{$question['data']['type']}` not yet supported, ignoring</error>"
                    );
                }
            endforeach;
        }
        if (!empty($json['questions']) && empty($json['features'])) {
            foreach ($json['questions'] as $question) {
                $question['content'] = $content;
                $question['itemreference'] = $itemReference;
                $question['feature'] = [];
                if (in_array($question['data']['type'], LearnosityExportConstant::$supportedQuestionTypes)) {
                    $result = Converter::convertLearnosityToQtiItem($question);
                    if (!$result) {
                        $this->output->writeln("<error>Unkown error with `{$question['data']['type']}`, ignoring</error>");
                        continue;
                    }
                    $result[0] = str_replace('/vendor/learnosity/itembank/', '', $result[0]);
                    $result[0] = str_replace('xmlns:default="http://www.w3.org/1998/Math/MathML"', '', $result[0]);
                    //TODO: Change this to only select MathML elements?
                    $result[0] = str_replace('<default:', '<', $result[0]);
                    $result[0] = str_replace('</default:', '</', $result[0]);
                    $finalXml['questions'][] = $result;
                    $tagsArray[$question['reference']] = $tags;
                } else {
                    $this->output->writeln(
                        "<error>Question type `{$question['data']['type']}` not yet supported, ignoring</error>"
                    );
                }
            }
        }

        if (!empty($json['features']) && empty($json['questions'])) {
            foreach ($json['features'] as $feature) {
                $feature['content'] = $content;
                $feature['itemreference'] = $itemReference;
                if (in_array($feature['data']['type'], LearnosityExportConstant::$supportedFeatureTypes)) {
                    $result = Converter::convertLearnosityToQtiItem($feature);
                    $result[0] = str_replace('/vendor/learnosity/itembank/', '', $result[0]);
                    $finalXml['features'][] = $result;
                    $tagsArray[$feature['reference']] = $tags;
                } else {
                    $this->output->writeln("<error>Feature type `{$feature['data']['type']}` not yet supported, ignoring</error>");
                }
            }
        }

        return [
            'qti'  => $finalXml,
            'json' => $json,
            'tags' => $tagsArray
        ];
    }

    /**
     * Flush and write the given job manifest.
     *
     * @param Manifest $manifestContent
     * @param array $results
     *
     * @throws DOMException
     */
    private function flushJobManifest(
        Manifest $manifestContent,
        array $results
    ): void {
        $manifestFileBasename = static::MANIFEST_FILE_NAME;
        $imsManifestXml = new DOMDocument("1.0", "UTF-8");
        $imsManifestXml->formatOutput = true;
        $element = $imsManifestXml->createElement("manifest");
        $element->setAttribute("identifier", $manifestContent->getIdentifier());
        $imsManifestXml->appendChild($element);

        $manifestMetaData = $this->addManifestMetadata($manifestContent, $imsManifestXml);
        $element->appendChild($manifestMetaData);

        $organization = $this->addOrganizationInfoInManifest($manifestContent, $imsManifestXml);
        $element->appendChild($organization);

        $resourceInfo = $this->addResourceInfoInManifest($manifestContent, $imsManifestXml, $results);
        $element->appendChild($resourceInfo);

        $this->decorateImsManifestRootElement($element);
        $xml = $imsManifestXml->saveXML();
        $outputFilePath = realpath($this->outputPath) . '/' . $this->rawPath . '/';
        file_put_contents($outputFilePath . '/' . $manifestFileBasename, $xml);
    }

    /**
     * Create and zip all the files in specified folder .
     *
     * @param string $contentDirPath Directory path where all the files are
     */
    private function createIMSContentPackage(string $contentDirPath): void
    {
        if (!class_exists('ZipArchive')) {
            return;
        }

        // Get real path for our folder
        $rootPath = $contentDirPath;

        // Initialize archive object
        $zip = new ZipArchive();
        $zip->open($contentDirPath . '/' . self::IMS_CONTENT_PACKAGE_NAME, ZipArchive::CREATE | ZipArchive::OVERWRITE);

        // Create recursive directory iterator
        /** @var SplFileInfo[] $files */
        $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($rootPath, true), RecursiveIteratorIterator::LEAVES_ONLY);

        foreach ($files as $file) {
            // Skip directories (they would be added automatically)
            if (!$file->isDir()) {
                // Get real and relative path for currentI file
                $filePath = $file->getRealPath();
                $relativePath = substr($filePath, strlen($rootPath));

                // Add current file to archive
                $zip->addFile($filePath, $relativePath);
            }
        }

        // Zip archive will be created only after closing object
        $zip->close();
    }

    /**
     * This function is used to add the imsmanifest metadata.
     *
     * @param Manifest $manifestContent content of the manifest
     * @param DOMDocument $imsManifestXml manifest xml document object
     *
     * @return DOMElement|false manifest meta data
     * @throws DOMException
     */
    private function addManifestMetadata(
        Manifest $manifestContent,
        DOMDocument $imsManifestXml
    ): bool|DOMElement {
        $manifestMetadata = $imsManifestXml->createElement("metadata");
        $manifestMetadataContent = $manifestContent->getImsManifestMetaData();
        $schema = $imsManifestXml->createElement("schema", $manifestMetadataContent->getSchema());
        $manifestMetadata->appendChild($schema);

        $qtiMetaData = $imsManifestXml->createElement('imsqti:qtiMetadata');

        $qtiMetaData->appendChild($imsManifestXml->createElement('imsqti:toolName', LearnosityExportConstant::IMSQTI_TOOLNAME));
        $qtiMetaData->appendChild($imsManifestXml->createElement('imsqti:toolVersion', LearnosityExportConstant::IMSQTI_TOOL_VERSION));
        $qtiMetaData->appendChild($imsManifestXml->createElement('imsqti:toolVendor', LearnosityExportConstant::IMSQTI_TOOL_VENDOR));

        $qtiLOMData = $imsManifestXml->createElement('imsmd:lom');
        $qtiLOMGenral = $imsManifestXml->createElement('imsmd:general');
        $qtiLOMTitle = $imsManifestXml->createElement('imsmd:title');

        $qtiLOMTitleValue = $imsManifestXml->createElement('imsmd:string', LearnosityExportConstant::IMSQTI_TITLE);
        $qtiLOMTitleValue->setAttribute('xml:lang', LearnosityExportConstant::IMSQTI_LANG);
        $qtiLOMTitle->appendChild($qtiLOMTitleValue);

        $qtiLOMGenral->appendChild($qtiLOMTitle);
        $qtiLOMData->appendChild($qtiLOMGenral);

        $imsMetaMetaData = LearnosityExportConstant::IMSQTI_METADATA_SCHEMA;
        $imsMetaMetaDataSchema = $imsManifestXml->createElement('imsmd:metaMetadata');
        foreach ($imsMetaMetaData as $metaDataSchema) {
            $imsMetaMetaDataSchema->appendChild($imsManifestXml->createElement('imsmd:metadataschema', $metaDataSchema));
        }
        $imsMetaMetaDataSchema->appendChild($imsManifestXml->createElement('imsmd:language', LearnosityExportConstant::IMSQTI_LANG));

        $schemaVersion = $imsManifestXml->createElement("schemaversion", $manifestMetadataContent->getSchemaVersion());
        $manifestMetadata->appendChild($schemaVersion);
        $manifestMetadata->appendChild($qtiMetaData);
        $manifestMetadata->appendChild($qtiLOMData);
        $manifestMetadata->appendChild($imsMetaMetaDataSchema);
        return $manifestMetadata;
    }

    /**
     * This function is used for add organization info in manifest file.
     *
     * @param Manifest $manifestContent
     * @param DOMDocument $imsManifestXml
     *
     * @return DOMElement|false Organization info
     * @throws DOMException
     */
    private function addOrganizationInfoInManifest(
        Manifest $manifestContent,
        DOMDocument $imsManifestXml
    ): DOMElement|bool {
        return $imsManifestXml->createElement("organizations");
    }

    /**
     * This function is used to add resource information in manifest xml file.
     *
     * @param Manifest $manifestContent
     * @param DOMDocument $imsManifestXml
     * @param array $results
     *
     * @return DOMElement|false resource
     * @throws DOMException
     */
    private function addResourceInfoInManifest(
        Manifest $manifestContent,
        DOMDocument $imsManifestXml,
        array $results
    ): bool|DOMElement {
        $resources = $imsManifestXml->createElement("resources");
        $resourcesContents = $manifestContent->getResources();

        foreach ($resourcesContents as $index => $resourcesContent) {
            foreach ($resourcesContent as $indexResource => $resourceContent) {
                $resource = $imsManifestXml->createElement("resource");
                $resource->setAttribute("identifier", $resourceContent->getIdentifier());
                $resource->setAttribute("type", $resourceContent->getType());
                $resource->setAttribute("href", $resourceContent->getHref());
                if (
                    !empty($results[$index])
                    &&
                    (
                        in_array('tags', $results[$index])
                        && !empty($results[$index]['tags'][$results[$index]['json']['questions'][$indexResource]['reference']]))
                ) {
                    $metadata = $imsManifestXml->createElement("metadata");
                    $tagsArray = $results[$index]['tags'][$results[$index]['json']['questions'][$indexResource]['reference']];
                    if (is_array($tagsArray) && sizeof($tagsArray) > 0) {
                        $resourceMetadata = $this->addResourceMetaDataInfo($imsManifestXml, $tagsArray);
                        $metadata->appendChild($resourceMetadata);
                    }
                    $resource->appendChild($metadata);
                }

                $filesData = $resourceContent->getFiles();

                foreach ($filesData as $fileContent) {
                    $file = $imsManifestXml->createElement("file");
                    $file->setAttribute("href", $fileContent->getHref());
                    $resource->appendChild($file);
                }

                $resources->appendChild($resource);
            }
        }
        return $resources;
    }

    /**
     * Returns the base template for job manifests consumed by this job.
     *
     * @return array|Manifest
     */
    private function getJobManifestTemplate(): array|Manifest
    {
        $manifest = new Manifest();
        $manifest->setIdentifier('i' . UuidUtil::generate());
        $manifest->setImsManifestMetaData($this->createImsManifestMetaData());
        return $manifest;
    }

    /**
     * This is used for adding manifest metadata in ims manifest xml file.
     *
     * @return ImsManifestMetadata ImsManifestMetadata
     */
    private function createImsManifestMetaData(): ImsManifestMetadata
    {
        $manifestMetaData = new ImsManifestMetadata();
        $manifestMetaData->setSchema(LearnosityExportConstant::SCHEMA_NAME);
        $manifestMetaData->setSchemaVersion(LearnosityExportConstant::SCHEMA_VERSION);
        $manifestMetaData->setTitle("QTI 2.1 Conversion Data");
        return $manifestMetaData;
    }

    /**
     * Writes a given results file to the specified output path.
     *
     * @param array  $results
     * @param string $outputFilePath
     */
    private function persistResultsFile(
        array $results,
        string $outputFilePath
    ): void {
        if ($this->dryRun) {
            return;
        }
        $this->output->writeln(
            "\n<info>"
            . static::INFO_OUTPUT_PREFIX
            . "Writing conversion results: "
            . $outputFilePath
            . "</info>\n"
        );

        foreach ($results as $result) {
            if (!empty($result['qti'])) {
                if (!empty($result['json']['questions'])) {
                    foreach ($result['qti']['questions'] as $key => $value) {
                        file_put_contents($outputFilePath . '/' . LearnosityExportConstant::DIRNAME_ITEMS . '/' . $result['json']['questions'][$key]['reference'] . '.xml', $value[0]);
                    }
                }

                if (!empty($result['json']['features']) && empty($result['json']['questions'])) {
                    foreach ($result['qti']['features'] as $key => $value) {
                        file_put_contents($outputFilePath . '/' . LearnosityExportConstant::DIRNAME_ITEMS . '/' . $result['json']['features'][$key]['reference'] . '.xml', $value[0]);
                    }
                }
            }
        }
    }

    /**
     * Updates a given job manifest in place with the contents of a specified
     * job partial result object.
     *
     * @param Manifest $manifest - the job manifest to update
     * @param array $results - the partial job result object to read
     *
     * @return array
     */
    private function updateJobManifest(Manifest $manifest, array $results): array
    {
        $resourcesArray = [];

        $additionalFileReferenceInfo = $this->getAdditionalFileInfoForManifestResource($results);

        foreach ($results as $result) {
            if (!empty($result['json']['questions'])) {
                $resourcesArray[] = $this->addQuestionReference($result['json']['questions'], $result, $additionalFileReferenceInfo);
            }
            if (!empty($result['json']['features']) && empty($result['json']['questions'])) {
                $resourcesArray[] = $this->addFeatureReference($result['json']['features'], $result, $additionalFileReferenceInfo);
            }
        }

        return $resourcesArray;
    }

    private function addQuestionReference(
        $questions,
        $result,
        $additionalFileReferenceInfo
    ): array {
        $resources = array();
        if (!empty($result['qti']['questions'])) {
            foreach ($result['qti']['questions'] as $question) {
                $files = array();
                $resource = new Resource();
                $resource->setIdentifier('i'.$question['2']);
                $resource->setType(Resource::TYPE_PREFIX_ITEM."xmlv2p1");
                $resource->setHref(LearnosityExportConstant::DIRNAME_ITEMS . '/' . $question['2'].".xml");
                if (array_key_exists($question['2'], $additionalFileReferenceInfo)) {
                    $files = $this->addAdditionalFileInfo($additionalFileReferenceInfo[$question['2']], $files);
                }
                if (!empty($question['3']) && array_key_exists($question['2'], $question['3'])) {
                    $files = $this->addFeatureHtmlFilesInfo($question['3'][$question['2']], $files);
                }
                if (!empty($question['3']) && array_key_exists('features', $question['3'])) {
                    $files = $this->addAdditionalFileInfo($additionalFileReferenceInfo[$question['3']['features']], $files);
                }
                $file = new File();
                $file->setHref(LearnosityExportConstant::DIRNAME_ITEMS . '/' . $question['2'].".xml");
                $files[] = $file;
                $resource->setFiles($files);
                $resources[] = $resource;
            }
        }

        return $resources;
    }

    private function addFeatureReference(
        $features,
        $result,
        $additionalFileReferenceInfo
    ): array {
        $resources = array();
        foreach ($features as $feature) {
            if (!empty($result['qti'])) {
                $files = array();
                $resource = new Resource();
                $resource->setIdentifier('i'.$feature['reference']);
                $resource->setType(Resource::TYPE_PREFIX_ITEM."xmlv2p1");
                $resource->setHref(LearnosityExportConstant::DIRNAME_ITEMS . '/' . $feature['reference'].".xml");
                if (array_key_exists($feature['reference'], $additionalFileReferenceInfo)) {
                    $files = $this->addAdditionalFileInfo($additionalFileReferenceInfo[$feature['reference']], $files);
                }

                $file = new File();
                $file->setHref(LearnosityExportConstant::DIRNAME_ITEMS . '/' . $feature['reference'].".xml");
                $files[] = $file;
                $resource->setFiles($files);
                $resources[] = $resource;
            }
        }
        return $resources;
    }

    private function addFeatureFilesInfo($featureArray, array $files): array
    {
        foreach ($featureArray as $featureId => $featureHtml) {
            if (file_put_contents(
                $this->outputPath
                . '/'
                . $this->rawPath
                . '/'
                . self::SHARED_PASSAGE_FOLDER_NAME
                . '/'
                . $featureId
                . '.html', $featureHtml)
            ) {
                $file = new File();
                $file->setHref(self::SHARED_PASSAGE_FOLDER_NAME . '/' . $featureId . '.html');
                $files[] = $file;
            }
        }

        return $files;
    }

    /**
     * This is used to add shared passage html file in manifest json file
     *
     * @param array $featureHtmlArray HTML files of shared passages
     * @param array $files files to be added
     *
     * @return array|File array of files
     */
    private function addFeatureHtmlFilesInfo(
        array $featureHtmlArray,
        array $files
    ): array|File {
        foreach ($featureHtmlArray as $featureId => $featureHtml) {
            if (file_put_contents($this->outputPath . '/' . $this->rawPath . '/' . LearnosityExportConstant::SHARED_PASSAGE_FOLDER_NAME . '/' . $featureId . '.html', $featureHtml)) {
                $file = new File();
                $file->setHref(LearnosityExportConstant::SHARED_PASSAGE_FOLDER_NAME . '/' . $featureId . '.html');
                $files[] = $file;
            }
        }

        return $files;
    }

    /**
     * This is used for adding metadata for each resource
     *
     * @param DOMDocument $imsManifestXml manifest xml
     * @param array $tagsArray From learnosity manifest json
     *
     * @return DOMElement LOM object of resource metadata
     * @throws DOMException
     */
    private function addResourceMetaDataInfo(
        DOMDocument $imsManifestXml,
        array $tagsArray
    ): DOMElement {
        $imsmdLom = $imsManifestXml->createElement('imsmd:lom');
        $imsmdClassification = $imsManifestXml->createElement('imsmd:classification');
        $imsmdLom->appendChild($imsmdClassification);
        $imsmdPurpose = $imsManifestXml->createElement('imsmd:purpose');
        $imsmdPurpose->appendChild($imsManifestXml->createElement('imsmd:source', 'LOMv1.0'));
        $imsmdPurpose->appendChild($imsManifestXml->createElement('imsmd:value', 'discipline'));
        $imsmdClassification->appendChild($imsmdPurpose);

        foreach ($tagsArray as $tagKey => $tagValues) {
            $taxonPath = $imsManifestXml->createElement('imsmd:taxonPath');
            $imsmdSource = $imsManifestXml->createElement('imsmd:source');
            $imsmdSource->appendChild($imsManifestXml->createElement('imsmd:string', $tagKey));
            $taxonPath->appendChild($imsmdSource);
            $taxOn = $imsManifestXml->createElement('imsmd:taxon');
            $imsmdEntry = $imsManifestXml->createElement('imsmd:entry');
            $tagsValues = implode(',', $tagValues);
            $imsmdEntry->appendChild($imsManifestXml->createElement('msmd:string', $tagsValues));
            $taxOn->appendChild($imsmdEntry);
            $taxonPath->appendChild($taxOn);

            $imsmdClassification->appendChild($taxonPath);
        }

        return $imsmdLom;
    }

    /**
     * Add any additional file info is associated with resource
     *
     * @param array $filesInfo
     * @param array $files
     *
     * @return array|File
     */
    private function addAdditionalFileInfo(array $filesInfo, array $files): array|File
    {
        $files = [];

        foreach ($filesInfo as $info) {
            $file = new File();
            $fileName = substr($info, strlen(LearnosityExportConstant::DIRPATH_ASSETS));
            $mimeType = MimeUtil::guessMimeType($fileName);
            $href = $this->getAssetHref($fileName, $mimeType);
            $file->setHref($href);
            $files[] = $file;
        }

        return $files;
    }

    private function getAssetHref($fileName, $mimeType): string
    {
        $mediaFormatArray = explode('/', $mimeType);
        $href = '';

        if (is_array($mediaFormatArray) && !empty($mediaFormatArray[0])) {
            $mediaFormat = $mediaFormatArray[0];
            if ($mediaFormat == 'video') {
                $href = LearnosityExportConstant::DIRNAME_VIDEO . '/' . $fileName;
            } elseif ($mediaFormat == 'audio') {
                $href = LearnosityExportConstant::DIRNAME_AUDIO . '/' . $fileName;
            } elseif ($mediaFormat == 'image') {
                $href = LearnosityExportConstant::DIRNAME_IMAGES . '/' . $fileName;
            }
        }

        return $href;
    }

    /**
     * This function will add additional file info in resource part of manifest xml
     *
     * @return array additional file info
     */
    private function getAdditionalFileInfoForManifestResource(array $results): array
    {
        $learnosityManifestJson = json_decode(file_get_contents($this->inputPath . '/manifest.json'));
        $additionalFileInfoArray = array();

        if (isset($learnosityManifestJson->assets->items)) {
            $activityArray = $learnosityManifestJson->assets->items;

            foreach ($activityArray as $itemValue) {
                $questionArray = $itemValue;

                if (isset($questionArray->questions) && is_object($questionArray->questions)) {
                    foreach ($questionArray->questions as $questionKey => $questionValue) {
                        $valueArray = array();
                        foreach ($questionValue as $value) {
                            $valueArray[] = $value->replacement;
                        }
                        $additionalFileInfoArray[$questionKey] = $valueArray;
                    }
                }

                if (isset($questionArray->features) && is_object($questionArray->features)) {
                    foreach ($questionArray->features as $featureKey => $featureValue) {
                        $valueArray = array();

                        foreach ($featureValue as $value) {
                            if (isset($value->replacement)) {
                                $valueArray[] = $value->replacement;
                            }
                        }
                        $additionalFileInfoArray[$featureKey] = $valueArray;
                    }
                }
            }
        }

        return $additionalFileInfoArray;
    }

    private function tearDown()
    {
    }

    private function validate(): array
    {
        $errors = [];
        $jsonFolders = $this->parseInputFolders();

        if (empty($jsonFolders)) {
            $errors[] = 'No files found in ' . $this->inputPath;
        }

        return $errors;
    }

    private function getReferenceArray($json): array
    {
        $content = strip_tags($json['content'], "<span>");
        $contentArr = explode('</span>', $content);
        $referenceArr = [];
        for ($i=0; $i< sizeof($contentArr); $i++) {
            if (strpos($contentArr[$i], 'feature')) {
                $featureReference = trim(str_replace('<span class="learnosity-feature feature-', '', $contentArr[$i]));
                $featureReference = trim(str_replace('">', "", $featureReference));
            }

            if (strpos($contentArr[$i], 'question')) {
                $questionReference = trim(str_replace('<span class="learnosity-response question-', '', $contentArr[$i]));
                $questionReference = trim(str_replace('">', "", $questionReference));
                $referenceArr[$i]['questionReference'] = $questionReference;
                if (isset($featureReference)) {
                    $referenceArr[$i]['featureReference'] = $featureReference;
                }
            }
        }
        return $referenceArr;
    }
    private function getFeatureReference($questionReference, $referenceArray)
    {
        $featureReference = '';
        foreach ($referenceArray as $reference) {
            if ($questionReference == $reference['questionReference']) {
                if (isset($reference['featureReference'])) {
                    $featureReference = $reference['featureReference'];
                }
            }
        }

        return $featureReference;
    }

    private function getFeature($featureReference, $features): array
    {
        $featureArray = [];

        foreach ($features as $feature) {
            if ($feature['reference'] == $featureReference) {
                $featureArray[] = $feature;
            }
        }

        return $featureArray;
    }
}
