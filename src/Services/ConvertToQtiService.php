<?php

namespace LearnosityQti\Services;

use DOMDocument;
use DOMElement;
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
use LearnosityQti\Utils\General\CopyDirectoreyHelper;
use RecursiveIteratorIterator;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\Iterator\RecursiveDirectoryIterator;
use Symfony\Component\Finder\SplFileInfo;
use ZipArchive;

class ConvertToQtiService
{
    use JobDataTrait;

    const RESOURCE_TYPE_ITEM = 'imsqti_item_xmlv2p1';
    const INFO_OUTPUT_PREFIX = '';
    const CONVERT_LOG_FILENAME = 'converttoqti.log';
    const MANIFEST_FILE_NAME = 'imsmanifest.xml';
    const IMS_CONTENT_PACKAGE_NAME = 'qti.zip';

    protected $inputPath;
    protected $outputPath;
    protected $output;
    protected $organisationId;
    protected $itemReference;

    /* Runtime options */
    protected $dryRun                     = false;
    protected $shouldAppendLogs           = false;
    protected $shouldGuessItemScoringType = true;
    protected $shouldUseManifest          = true;

    /* Job-specific configurations */
    // Overrides identifiers to be the same as the filename
    protected $useFileNameAsIdentifier    = false;
    // Uses the identifier found in learning object metadata if available
    protected $useMetadataIdentifier      = true;
    // Resource identifiers sometimes (but not always) match the assessmentItem identifier, so this can be useful
    protected $useResourceIdentifier      = false;

    private $assetsFixer;

    public function __construct($inputPath, $outputPath, OutputInterface $output, $organisationId = null)
    {
        $this->inputPath      = $inputPath;
        $this->outputPath     = $outputPath;
        $this->output         = $output;
        $this->organisationId = $organisationId;
        $this->finalPath      = 'final';
        $this->logPath        = 'log';
        $this->rawPath        = 'raw';
        $this->itemReference = array();
    }

    public function process()
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

        $result = $this->parseContent();

        $this->tearDown();

        return $result;
    }
    
    /**
    * Decorate the IMS root element of the Manifest with the appropriate
	 * namespaces and schema definition.
	 *
	 * @param DOMElement $rootElement The root DOMElement object of the document to decorate.
	 */
    protected function decorateImsManifestRootElement(DOMElement $rootElement)
    {
        
        $xsdLocation = 'http://www.imsglobal.org/xsd/imscp_v1p1 http://www.imsglobal.org/xsd/qti/qtiv2p1/qtiv2p1_imscpv1p2_v1p0.xsd';
        $xmlns = "http://www.imsglobal.org/xsd/imscp_v1p1";
        $rootElement->setAttribute('xmlns', $xmlns);
        $rootElement->setAttribute('xmlns:imsmd', 'http://ltsc.ieee.org/xsd/LOM');
        $rootElement->setAttribute('xmlns:imsqti', 'http://www.imsglobal.org/xsd/imsqti_metadata_v2p1');
        $rootElement->setAttribute("xmlns:xsi", "http://www.w3.org/2001/XMLSchema-instance");
        $rootElement->setAttribute("xsi:schemaLocation", $xsdLocation);
    }

    /**
     * Performs a conversion on each directory (one level deep)
     * inside the given source directory.
     */
    private function parseContent()
    {
        
        $results = [];
        $jsonFiles = $this->parseInputFolders();
        
        $finalManifest = $this->getJobManifestTemplate();

        $this->output->writeln("<info>" . static::INFO_OUTPUT_PREFIX . "Processing JSON directory: {$this->inputPath} </info>");

        foreach ($jsonFiles as $file) {
            if(file_exists($file)) {
                $results[] = $this->convertLearnosityInDirectory($file);
            } else {
                $this->output->writeln("<info>" . static::INFO_OUTPUT_PREFIX . "Learnosity JSON file ".basename($file). " Not fount in: {$this->inputPath}/items </info>");
            }
        }
        
        $resourceInfo = $this->updateJobManifest($finalManifest, $results);
        $finalManifest->setResources($resourceInfo);
        $this->persistResultsFile($results, realpath($this->outputPath) . '/' . $this->rawPath . '/');
        $this->flushJobManifest($finalManifest, $results);
        CopyDirectoreyHelper::copyFiles(realpath($this->inputPath) . '/assets', realpath($this->outputPath) . '/' . $this->rawPath . '/assets');
        $this->createIMSContntPackage(realpath($this->outputPath) . '/' . $this->rawPath . '/');
    }


    /**
     * Performs a conversion on QTI content packages found in the given root source directory.
     *
     * @param  string $sourceDirectory
     * @param  string $relativeSourceDirectoryPath
     *
     * @return array - the results of the conversion
     */
    private function convertLearnosityInDirectory($file)
    {
        $this->output->writeln("<comment>Converting Learnosity JSON {$file}</comment>");
        return $this->convertAssessmentItem(json_decode(file_get_contents($file), true));
    }

    // Traverse the -i option and find all paths with files
    private function parseInputFolders() {
        $folders = [];

        // Look for json files in the current path
        $finder = new Finder();
        $finder->files()->in($this->inputPath . '/activities');
        foreach ($finder as $json) {
            $activityJson = json_decode(file_get_contents($json));
            $itemReferences = $activityJson->data->items;
            $this->itemReference = $itemReferences;
            if (!empty($itemReferences)) {
                foreach ($itemReferences as $itemref) {
                    $itemref = md5($itemref);
                    $folders[] = $this->inputPath . '/items/' . $itemref . '.json';
                }
            } else {
                $this->output->writeln("<error>Error converting : No item refrences found in the activity json</error>");
            }
        }

        return $folders;
    }

    /**
     * Converts Learnosity JSON to QTI
     *
     * @param  string $jsonString
     *
     * @return array - the results of the conversion
     *
     * @throws Exception - if the conversion fails
     */
    private function convertAssessmentItem($json)
    {
        $result = [];

        
        $finalXml = [];
        $content = $json['content'];
        foreach($json['questions'] as $question):
            $question['content'] = $content;
            if (in_array($question['data']['type'], LearnosityExportConstant::$supportedQuestionTypes)) {
                $result = Converter::convertLearnosityToQtiItem($question);
                $result[0] = str_replace('/vendor/learnosity/itembank/','',$result[0]);
                $finalXml[] = $result;
            } else {
                $result = [
                    '',
                    [
                        'Ignoring' . $question['data']['type'] . ' , currently unsupported'
                    ]
                ];
                $this->output->writeln("<error>Question type `{$question['data']['type']}` not yet supported, ignoring</error>");
            }
        endforeach;
        return [
            'qti' => $finalXml,
            'json' => $json
        ];
    }

     /**
     * Flush and write the given job manifest.
     *
     * @param array $manifest
     */
    private function flushJobManifest(Manifest $manifestContent)
    {
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
        
        $resourceInfo = $this->addResourceInfoInManifest($manifestContent, $imsManifestXml);
        $element->appendChild($resourceInfo);
        
        $this->decorateImsManifestRootElement($element);
        $xml = $imsManifestXml->saveXML();
        $outputFilePath = realpath($this->outputPath) . '/' . $this->rawPath . '/';
        file_put_contents($outputFilePath . '/' . $manifestFileBasename, $xml);
    }
    
    private function createIMSContntPackage($contentDirPath) {
        // Get real path for our folder
        $rootPath = $contentDirPath;
        
        // Initialize archive object
        $zip = new ZipArchive();
        $zip->open($contentDirPath.'/'. self::IMS_CONTENT_PACKAGE_NAME, ZipArchive::CREATE | ZipArchive::OVERWRITE);

        // Create recursive directory iterator
        /** @var SplFileInfo[] $files */
        $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($rootPath, true), RecursiveIteratorIterator::LEAVES_ONLY);
            
        foreach ($files as $name => $file) {
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

    private function addManifestMetadata(Manifest $manifestContent, DOMDocument $imsManifestXml){
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
        foreach($imsMetaMetaData as $metaDataSchema){
            $imsMetaMetaDataSchema->appendChild($imsManifestXml->createElement('imsmd:metadataschema', $metaDataSchema));
        }
        $imsMetaMetaDataSchema->appendChild($imsManifestXml->createElement('imsmd:language', LearnosityExportConstant::IMSQTI_LANG));
        
        $imsRights = $imsManifestXml->createElement('imsmd:rights');
        $imsDescription = $imsManifestXml->createElement('imsmd:description');
        $imsDescriptionValue = $imsManifestXml->createElement('imsmd:string', LearnosityExportConstant::IMSQTI_RIGHTS);
        $imsDescriptionValue->setAttribute('xml:lang', LearnosityExportConstant::IMSQTI_LANG);
        $imsDescription->appendChild($imsDescriptionValue);
        $imsRights->appendChild($imsDescription);
        
        
        $schemaVersion = $imsManifestXml->createElement("schemaversion", $manifestMetadataContent->getSchemaversion());
        $manifestMetadata->appendChild($schemaVersion);
        $manifestMetadata->appendChild($qtiMetaData);
        $manifestMetadata->appendChild($qtiLOMData);
        $manifestMetadata->appendChild($imsMetaMetaDataSchema);
        $manifestMetadata->appendChild($imsRights);
        return $manifestMetadata;
    }
    
    private function addOrganizationInfoInManifest(Manifest $manifestContent, DOMDocument $imsManifestXml){
        $organization = $imsManifestXml->createElement("organizations");
        return $organization;
    }
    
    private function addResourceInfoInManifest(Manifest $manifestContent, DOMDocument $imsManifestXml){
        $resources = $imsManifestXml->createElement("resources");
        $resourcesContent = $manifestContent->getResources();
        foreach($resourcesContent as $resourceContent){
            $resource = $imsManifestXml->createElement("resource");
            $resource->setAttribute("identifier", $resourceContent->getIdentifier());
            $resource->setAttribute("type", $resourceContent->getType());
            $resource->setAttribute("href", $resourceContent->getHref());
            $metadara = $imsManifestXml->createElement("metadata");
            $resource->appendChild($metadara);
            $filesData = $resourceContent->getFiles();
            foreach($filesData as $fileContent){
                $file = $imsManifestXml->createElement("file");
                $file->setAttribute("href", $fileContent->getHref());
                $resource->appendChild($file);
            }
            $resources->appendChild($resource);
         }
         return $resources;
    }
    
    /**
     * Returns the base template for job manifests consumed by this job.
     *
     * @return array
     */
    private function getJobManifestTemplate()
    {
        $manifest = new Manifest();
        $manifest->setIdentifier('i'.UuidUtil::generate());
        $manifest->setImsManifestMetaData($this->createImsManifestMetaData());
        return $manifest;
    }
    
    private function createImsManifestMetaData(){
        $manifestMetaData = new ImsManifestMetadata();
        $manifestMetaData->setSchema("QTIv2.1 Item Bank Package");
        $manifestMetaData->setSchemaversion("1.0.0");
        $manifestMetaData->setTitle("QTI 2.1 Conversion Data");
        $manifestMetaData->setQtiMetadata('ABCVD');
        return $manifestMetaData;
    }

    /**
     * Writes a given results file to the specified output path.
     *
     * @param array  $results
     * @param string $outputFilePath
     */
    private function persistResultsFile(array $results, $outputFilePath)
    {
        if ($this->dryRun) {
            return;
        }

        $this->output->writeln("\n<info>" . static::INFO_OUTPUT_PREFIX . "Writing conversion results: " . $outputFilePath . '.json' . "</info>\n");
        
        foreach ($results as $result) {
            $i=0;
            foreach($result['qti'] as $qti){
                 
                if (!empty($result['qti'])) {
                    file_put_contents($outputFilePath . '/' . $result['json']['questions'][$i]['reference'] . '.xml', $qti[0]);
                }
                $i++;
            }
        }
    }

    /**
     * Updates a given job manifest in place with the contents of a specified
     * job partial result object.
     *
     * @param array $manifest - the job manifest to update
     * @param array $results  - the partial job result object to read
     */
    private function updateJobManifest(Manifest $manifest, array $results)
    {
        $resources = array();
        
        $additionalFileReferenceInfo = $this->getAdditionalFileInfoForManifestResource();
        foreach ($results as $result) {
            foreach($result['json']['questions'] as $question){
                if (!empty($result['qti'])) {
                    $files = array();
                    $resource = new Resource();
                    $resource->setIdentifier('i'.$question['reference']);
                    $resource->setType(Resource::TYPE_PREFIX_ITEM."xmlv2p1");
                    $resource->setHref($question['reference'].".xml");
                    if(array_key_exists($question['reference'], $additionalFileReferenceInfo)){
                        $files = $this->addAdditionalFileInfo($additionalFileReferenceInfo[$question['reference']], $files);
                    }
                    $file = new File();
                    $file->setHref($question['reference'].".xml");
                    $files[] = $file;
                    $resource->setFiles($files);
                    $resources[] = $resource;
                }
            }
        }
        return $resources;
    }
    
     private function addAdditionalFileInfo($filesInfo, $files){
        $files = array();
        foreach($filesInfo as $id=>$info){
            $file = new File();
            $href = substr($info, strlen('/vendor/learnosity/itembank/'));
            $file->setHref($href);
            $files[] = $file;
        }
        return $files;
    }
    
    private function getAdditionalFileInfoForManifestResource(){
        $itemsReferenseArray = $this->itemReference;
        $learnosityManifestJson = json_decode(file_get_contents($this->inputPath. '/manifest.json'));
        $activityArray = array();
        if(!empty($learnosityManifestJson->assets->items)){
            $activityArray = $learnosityManifestJson->assets->items;
        }
        $additionalFileInfoArray = array();
        $valueArray = array();
        foreach($activityArray as $itemReference=>$itemValue){
            $questionArray = $itemValue;
            if(is_object($questionArray->questions)){
                foreach($questionArray->questions as $questionKey=>$questionValue){
                    $valueArray = array();
                    foreach($questionValue as $questions=>$value){
                       $valueArray[] = $value->replacement;
                    }
                    $additionalFileInfoArray[$questionKey] = $valueArray;
                }
            }
        }
        return $additionalFileInfoArray;
    }

    private function tearDown()
    {
    }

    private function validate()
    {
        $errors = [];
        $jsonFolders = $this->parseInputFolders();

        if (empty($jsonFolders)) {
            array_push($errors, 'No files found in ' . $this->inputPath);
        }

        return $errors;
    }
}