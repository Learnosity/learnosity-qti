<?php
namespace LearnosityQti\Services;

use LearnosityQti\AppContainer;
use LearnosityQti\Converter;
use LearnosityQti\Domain\JobDataTrait;
use LearnosityQti\Services\ItemLayoutService;
use LearnosityQti\Exceptions\MappingException;
use LearnosityQti\Utils\AssetsFixer;
use LearnosityQti\Utils\AssumptionHandler;
use LearnosityQti\Utils\CheckValidQti;
use LearnosityQti\Utils\ResponseProcessingHandler;
use LearnosityQti\Utils\General\FileSystemHelper;
use LearnosityQti\Utils\General\StringHelper;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;
use qtism\data\AssessmentItem;
use qtism\data\storage\xml\XmlDocument;

class ConvertToLearnosityService
{
    use JobDataTrait;

    const RESOURCE_TYPE_ITEM = 'imsqti_item_xmlv2p1';
    const INFO_OUTPUT_PREFIX = '';
    const CONVERT_LOG_FILENAME = 'convert-to-learnosity.log';

    protected $inputPath;
    protected $outputPath;
    protected $output;
    protected $organisationId;

    /* Runtime options */
    protected $dryRun                     = false;
    protected $shouldAppendLogs           = false;
    protected $shouldGuessItemScoringType = true;
    protected $shouldUseManifest          = true;

    /* Job-specific configurations */
    // Overrides identifiers to be the same as the filename

    protected $useFileNameAsIdentifier = false;
    // Uses the identifier found in learning object metadata if available
    protected $useMetadataIdentifier = true;
    // Resource identifiers sometimes (but not always) match the assessmentItem identifier, so this can be useful
    protected $useResourceIdentifier = false;

    private $assetsFixer;
    // Hold the class instance.
    private static $instance = null;

    protected function __construct($inputPath, $outputPath, OutputInterface $output, $organisationId)
    {
        $this->inputPath      = $inputPath;
        $this->outputPath     = $outputPath;
        $this->output         = $output;
        $this->organisationId = $organisationId;
        $this->finalPath      = 'final';
        $this->logPath        = 'log';
        $this->rawPath        = 'raw';
    }

    // The object is created from within the class itself
    // only if the class has no instance.
    public static function initClass($inputPath, $outputPath, OutputInterface $output, $organisationId)
    {
        if (!self::$instance) {
            self::$instance = new ConvertToLearnosityService($inputPath, $outputPath, $output, $organisationId);
        }
        return self::$instance;
    }

    // Return instance of the class
    public static function getInstance()
    {
        return self::$instance;
    }

    public function getInputPath()
    {
        return $this->inputPath;
    }

    public function isUsingMetadataIdentifier()
    {
        return $this->useMetadataIdentifier;
    }

    public function useMetadataIdentifier($useMetadataIdentifier)
    {
        $this->useMetadataIdentifier = $useMetadataIdentifier;
    }

    public function isUsingResourceIdentifier()
    {
        return $this->useResourceIdentifier;
    }

    public function useResourceIdentifier($useResourceIdentifier)
    {
        $this->useResourceIdentifier = $useResourceIdentifier;
    }

    public function isUsingFileNameAsIdentifier()
    {
        return $this->useFileNameAsIdentifier;
    }

    public function useFileNameAsIdentifier($useFileNameAsIdentifier)
    {
        $this->useFileNameAsIdentifier = $useFileNameAsIdentifier;
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
        FileSystemHelper::createDirIfNotExists($this->outputPath . DIRECTORY_SEPARATOR . $this->finalPath);
        FileSystemHelper::createDirIfNotExists($this->outputPath . DIRECTORY_SEPARATOR . $this->logPath);
        FileSystemHelper::createDirIfNotExists($this->outputPath . DIRECTORY_SEPARATOR . $this->rawPath);

        $this->assetsFixer = new AssetsFixer($this->organisationId);

        $result = $this->parseContentPackage();

        // Convert the item format (columns etc)
        $ItemLayout = new ItemLayoutService();
        $ItemLayout->execute($this->outputPath . DIRECTORY_SEPARATOR . $this->rawPath . DIRECTORY_SEPARATOR, $this->outputPath . DIRECTORY_SEPARATOR . $this->finalPath, $this->output);

        $this->tearDown();

        return $result;
    }

    /**
     * Performs a conversion on each directory (one level deep)
     * inside the given source directory.
     */
    private function parseContentPackage()
    {
        $manifestFolders = $this->parseInputFolders();

        $finalManifest = $this->getJobManifestTemplate();

        foreach ($manifestFolders as $dir) {
            $tempDirectoryParts = explode(DIRECTORY_SEPARATOR, dirname($dir));
            $dirName = $tempDirectoryParts[count($tempDirectoryParts) - 1];
            $results = $this->convertQtiContentPackagesInDirectory(dirname($dir), $dirName);
            $this->updateJobManifest($finalManifest, $results);
            $this->persistResultsFile($results, realpath($this->outputPath) . DIRECTORY_SEPARATOR . $this->rawPath . DIRECTORY_SEPARATOR . $dirName);
        }

        $this->flushJobManifest($finalManifest);
    }

    // Traverse the -i option and find all paths with an imsmanifest.xml
    private function parseInputFolders()
    {
        $folders = [];

        // Look for the manifest in the current path
        $finder = new Finder();
        $finder->files()->in($this->inputPath)->name('imsmanifest.xml');
        foreach ($finder as $manifest) {
            $folders[] = $manifest->getRealPath();
        }

        return $folders;
    }

    /**
     * Performs a conversion on QTI content packages found in the given root source directory.
     *
     * @param  string $sourceDirectory
     * @param  string $relativeSourceDirectoryPath
     *
     * @return array - the results of the conversion
     */
    private function convertQtiContentPackagesInDirectory($sourceDirectory, $relativeSourceDirectoryPath)
    {
        $results = [
            'qtiitems' => [],
        ];

        $manifestFinder = new Finder();
        $manifestFinderPath = $manifestFinder->files()->in($sourceDirectory)->name('imsmanifest.xml');
        $totalItemCount = 0;
        foreach ($manifestFinderPath as $manifestFile) {
            /** @var SplFileInfo $manifestFile */
            $currentDir = realpath($manifestFile->getPath());
            $fullFilePath = realpath($manifestFile->getPathname());
            $relativeDir = rtrim($relativeSourceDirectoryPath . '/' . $manifestFile->getRelativePath(), '/');
            $relativePath = rtrim($relativeSourceDirectoryPath . '/' . $manifestFile->getRelativePathname(), '/');

            $this->output->writeln("<info>" . static::INFO_OUTPUT_PREFIX . "Processing manifest file: {$relativePath} </info>");

            // build the DOMDocument object
            $manifestDoc = new \DOMDocument();

            $manifestDoc->load($fullFilePath);

            $itemResources = $this->getItemResourcesByHrefFromDocument($manifestDoc);

            $itemCount = 0;
            foreach ($itemResources as $resource) {
                $itemCount++;
                $totalItemCount++;
                $resourceHref = $resource['href'];
                $relatedResource = $resource['resource'];
                $assessmentItemContents = file_get_contents($currentDir . '/' . $resourceHref);
                $itemReference = $this->getItemReferenceFromResource(
                    $relatedResource,
                    $this->useMetadataIdentifier,
                    $this->useResourceIdentifier,
                    $this->useFileNameAsIdentifier
                );

                $itemTagsArray = $this->getTaxonPathEntryForItemTags($relatedResource);
                $metadata = [];
                $itemPointValue = $this->getPointValueFromResource($relatedResource);
                if (isset($itemPointValue)) {
                    $metadata['point_value'] = $itemPointValue;
                }


                $metadata['organisation_id'] = $this->organisationId;

                if (isset($itemReference)) {
                    $this->output->writeln("<comment>Converting assessment item {$itemReference}: $relativeDir/$resourceHref</comment>");
                } else {
                    $this->output->writeln("<comment>Converting assessment item {$itemCount}: $relativeDir/$resourceHref</comment>");
                }

                $convertedContent = $this->convertAssessmentItemInFile($assessmentItemContents, $itemReference, $metadata, $currentDir, $resourceHref, $itemTagsArray);

                if (!empty($convertedContent)) {
                    $results['qtiitems'][basename($relativeDir) . '/' . $resourceHref] = $convertedContent;
                }
            }
        }
        return $results;
    }

    /**
     * Retrieves any <assessmentItem> resource elements found in a given
     * XML document.
     *
     * @param  DOMDocument $manifestDoc - the document to search
     *
     * @return array
     */
    private function getItemResourcesByHrefFromDocument(\DOMDocument $manifestDoc)
    {
        $itemResources = [];
        $resources = $manifestDoc->getElementsByTagName('resource');

        while (($resource = $resources->item(0)) != null) {
            $resourceHref = $resource->getAttribute('href');
            $resourceType = $resource->getAttribute('type');

            if ($resourceType === static::RESOURCE_TYPE_ITEM) {
                $itemResources[] = [
                    'href' => $resourceHref,
                    'resource' => $resource
                ];
            }

            // Remove the processed resource from the list for :toast:y performance reasons
            // see http://stackoverflow.com/a/13931470 regarding linear read performance
            $resource->parentNode->removeChild($resource);
        }

        return $itemResources;
    }

    /**
     * Gets the general identifier for this resource from its Learning Object Metadata
     * component, if it exists.
     *
     * @param  DOMNode $resource
     *
     * @return string|null - the identifier
     */
    private function getIdentifierFromResourceMetadata(\DOMNode $resource)
    {
        $identifier = null;

        $xpath = $this->getXPathForQtiDocument($resource->ownerDocument);

        $lomIdentifier = null;
        $searchResult = $xpath->query('.//qti:metadata/lom:lom/lom:general/lom:identifier', $resource);
        if ($searchResult->length > 0) {
            // Assume (as per the LOM/QTI specs) that there is only one case identifier
            $lomIdentifier = $searchResult->item(0);
        }

        // Extract a valid identifier string if exists
        if (isset($lomIdentifier)) {
            $entries = $xpath->query('./lom:entry/text()', $lomIdentifier);
            if ($entries->length > 0) {
                $identifier = $entries->item(0)->nodeValue;
            }
        }

        return $identifier;
    }

    /**
     * Checks whether a general identifier exists in the Learning Object Metadata
     * for this resource.
     *
     * @param  DOMNode $resource
     *
     * @return boolean
     */
    private function metadataIdentifierExists(\DOMNode $resource)
    {
        $xpath = new \DOMXPath($resource->ownerDocument);
        $xpath->registerNamespace('lom', 'http://ltsc.ieee.org/xsd/LOM');
        $xpath->registerNamespace('qti', 'http://www.imsglobal.org/xsd/imscp_v1p1');
        $searchResult = $xpath->query('.//qti:metadata/lom:lom/lom:general/lom:identifier', $resource);
        return $searchResult->length > 0;
    }

    /**
     * Checks whether a taxonPath exists in the Learning Object Metadata
     * for this resource.
     *
     * @param  DOMNode $resource
     *
     * @return array
     */
    private function getTaxonPathEntryForItemTags(\DOMNode $resource)
    {
        $xpath = new \DOMXPath($resource->ownerDocument);
        $xpath->registerNamespace('lom', 'http://ltsc.ieee.org/xsd/LOM');
        $xpath->registerNamespace('qti', 'http://www.imsglobal.org/xsd/imscp_v1p1');

        $searchResult = $xpath->query('.//lom:taxonPath', $resource);
        $itemTagsArray = array();
        foreach ($searchResult as $search) {
            $tagName = $xpath->query('.//lom:source/lom:string', $search)->item(0)->textContent . "\n";
            $tagValues = $xpath->query('.//lom:taxon/lom:entry/lom:string', $search)->item(0)->textContent . "\n";
            if (!empty(trim($tagValues))) {
                $tagValuesArray = explode(',', rtrim($tagValues));
                $itemTagsArray[rtrim($tagName)] = $tagValuesArray;
            }
        }
        return $itemTagsArray;
    }

    /**
     * Converts a single <assessmentItem> file.
     *
     * @param  SplFileInfo $file
     * @param  string $itemReference - Optional
     *
     * @return array - the results of the conversion
    */
    private function convertAssessmentItemInFile($contents, $itemReference = null, array $metadata = [], $currentDir, $resourceHref, $itemTagsArray = [])
    {
        $results = null;

        try {
            $xmlString = $contents;
            // Check that we're on an <assessmentItem>
            if (!CheckValidQti::isAssessmentItem($xmlString)) {
                $this->output->writeln("<info>" . static::INFO_OUTPUT_PREFIX . "Not an <assessmentItem>, moving on...</info>");
                return $results;
            }

            $resourcePath = $currentDir . '/' . $resourceHref;
            $results = $this->convertAssessmentItem($xmlString, $itemReference, $resourcePath, $metadata, $itemTagsArray);

        } catch (\Exception $e) {
            $targetFilename = $resourceHref;
            $message = $e->getMessage();
            $results = ['exception' => $targetFilename . '-' . $message];
            if (!StringHelper::contains($message, 'This is intro or outro')) {
                $this->output->writeln('  <error>EXCEPTION with item ' . str_replace($currentDir, '', $resourceHref) . ' : ' . $message . '</error>');
            }
        }

        return $results;
    }

    /**
     * Converts a single <assessmentItem> XML string.
     *
     * @param  string $xmlString
     * @param  string $itemReference - Optional
     * @param  string $resourcePath  - Optional
     *
     * @return array - the results of the conversion
     *
     * @throws \Exception - if the conversion fails
     */
    private function convertAssessmentItem($xmlString, $itemReference = null, $resourcePath = null, array $metadata = [], array $itemTagsArray = [])
    {
        AssumptionHandler::flush();

        $xmlString = CheckValidQti::preProcessing($xmlString);

        $result     = Converter::convertQtiItemToLearnosity($xmlString, null, null, $resourcePath, $itemReference, $metadata);
        $item       = $result['item'];
        $questions  = $result['questions'];
        $features   = $result['features'];
        $manifest   = $result['messages'];
        $rubricItem = !empty($result['rubric']) ? $result['rubric'] : null;

        $questions = !empty($questions) ? $this->assetsFixer->fix($questions) : $questions;
        $features = !empty($features) ? $this->assetsFixer->fix($features) : $features;

        // Return those results!
        list($item, $questions) = CheckValidQti::postProcessing($item, $questions, []);

        if ($this->shouldGuessItemScoringType) {
            list($assumedItemScoringType, $scoringTypeManifest) = $this->getItemScoringTypeFromResponseProcessing($xmlString);
            if (isset($assumedItemScoringType)) {
                $item['metadata']['scoring_type'] = $assumedItemScoringType;
            }
            $manifest = array_merge($manifest, $scoringTypeManifest);
        }
        $item['tags'] = $itemTagsArray;
        return [
            'item'        => $item,
            'questions'   => $questions,
            'features'    => $features,
            'manifest'    => $manifest,
            'rubric'      => $rubricItem,
            'assumptions' => AssumptionHandler::flush()
        ];
    }

    /**
     * Gets an item reference (if available) from a given resource.
     *
     * What to use as the item reference depends on the flags passed.
     * The order used for selecting an item reference, in ascending order
     * of priority, is as follows:
     *
     * - no item reference selected (if none found, or all options disabled)
     * - metadata identifier
     * - resource identifier attribute (if enabled)
     * - file name (if enabled)
     *
     * As such, if {$useFileNameAsIdentifier} is enabled, it takes precedence
     * over any other flags set before it.
     *
     * @param  DOMNode $resource
     * @param  boolean $useMetadataIdentifier   - Optional. true by default; set false to disable
     * @param  boolean $useResourceIdentifier   - Optional
     * @param  boolean $useFileNameAsIdentifier - Optional
     *
     * @return string|null
     */
    private function getItemReferenceFromResource(
        \DOMNode $resource,
        $useMetadataIdentifier = true,
        $useResourceIdentifier = false,
        $useFileNameAsIdentifier = false
    ) {
        $itemReference = null;

        if ($useMetadataIdentifier && $this->metadataIdentifierExists($resource)) {
            $itemReference = $this->getIdentifierFromResourceMetadata($resource);
        }

        if ($useResourceIdentifier) {
            $itemReference = $resource->getAttribute('identifier');
        }

        if ($useFileNameAsIdentifier) {
            // This flag should override anything else that is set above
            $resourceHref = $resource->getAttribute('href');
            $itemReference = $this->getIdentifierFromResourceHref($resourceHref);
        }
        return $itemReference;
    }

    /**
     * Takes the resource href and extracts the file name out of it.
     * @example items/My-File.xml will return My-File
     *
     * @param string $resourceHref
     * @return string
     */
    private function getIdentifierFromResourceHref($resourceHref, $suffix = '.xml')
    {
        return basename($resourceHref, $suffix);
    }

    /**
     * Tries to determine item scoring information based on response
     * processing rules in the given XML string.
     *
     * @param  string $xmlString
     *
     * @return <string, array> - the item scoring type if found as the first arg;
     *                         the list of log messages as the second arg
     */
    private function getItemScoringTypeFromResponseProcessing($xmlString)
    {
        $xmlString = AppContainer::getApplicationContainer()->get('xml_assessment_items_processing')->processXml($xmlString);
        $xmlDocument = new XmlDocument();
        $xmlDocument->loadFromString($xmlString);
        // $xmlDocument->getDomDocument()->documentURI = $file->getPathname();
        // $xmlDocument->xInclude();

        /** @var AssessmentItem $assessmentItem */
        $assessmentItem = $xmlDocument->getDocumentComponent();
        if (!($assessmentItem instanceof AssessmentItem)) {
            throw new MappingException('XML is not a valid <assessmentItem> document');
        }

        // Handle response processing
        list($responseProcessing, $assumedItemScoringType, $messages) = ResponseProcessingHandler::handle($assessmentItem, $xmlString);
        if (empty($messages)) {
            $messages = [];
        }

        return [$assumedItemScoringType, $messages];
    }

    private function getPointValueFromResource(\DOMNode $resource)
    {
        $pointValue = null;

        $xpath = $this->getXPathForQtiDocument($resource->ownerDocument);
        $pointValueEntries = ($xpath->query('./qti:metadata/lom:lom/lom:classification/lom:taxonPath/lom:source/lom:string[text() = \'cf$Point Value\']/../../lom:taxon/lom:entry', $resource));
        if ($pointValueEntries->length > 0) {
            $pointValue = (int) $pointValueEntries->item(0)->nodeValue;
        }

        return $pointValue;
    }

    private function getXPathForQtiDocument(\DOMDocument $document)
    {
        $xpath = new \DOMXPath($document);
        $xpath->registerNamespace('lom', 'http://ltsc.ieee.org/xsd/LOM');
        $xpath->registerNamespace('qti', 'http://www.imsglobal.org/xsd/imscp_v1p1');

        return $xpath;
    }

    /**
     * Returns a finder that is based on the finder provided, with any
     * specified filtering rules applied.
     *
     * @param  Finder $finder
     * @param  array  $filtering
     *
     * @return Finder
     */
    private function applyFilteringToFinder(Finder $finder, $filtering = null)
    {
        if (!isset($filtering)) {
            $filtering = $this->filtering;
        }

        // If filtering set, then only process those. This is useful for debugging :)
        if (!empty($filtering)) {
            $finder = $finder->filter(function (SplFileInfo $file) use ($filtering) {
                return $this->containsPath($file, $filtering);
            });
        }

        return $finder;
    }

    /**
     * Flush and write the given job manifest.
     *
     * @param array $manifest
     */
    private function flushJobManifest(array $manifest)
    {
        if ($this->dryRun) {
            return;
        }
        $manifest['info']['question_types'] = array_values(array_unique($manifest['info']['question_types']));
        $manifest['imported_rubrics'] = array_values(array_unique($manifest['imported_rubrics']));
        $manifest['imported_items'] = array_values(array_unique($manifest['imported_items']));
        $manifest['ignored_items'] = array_values(array_unique($manifest['ignored_items']));

        $manifest['info']['rubric_count'] = count($manifest['imported_rubrics']);
        $manifest['info']['item_count'] = count($manifest['imported_items']);
        $manifest['info']['item_scoring_types_counts']['none'] = $manifest['info']['item_count'] - array_sum($manifest['info']['item_scoring_types_counts']);

        if ($this->shouldAppendLogs) {
            $manifestFileBasename = static::CONVERT_LOG_FILENAME . '_' . date('m-d-y-His');
        } else {
            $manifestFileBasename = static::CONVERT_LOG_FILENAME;
        }
        $this->output->writeln('<info>' . static::INFO_OUTPUT_PREFIX . 'Writing manifest: ' . $this->outputPath . DIRECTORY_SEPARATOR . $this->logPath . DIRECTORY_SEPARATOR . $manifestFileBasename . ".json</info>\n");
        $this->writeJsonToFile($manifest, $this->outputPath . DIRECTORY_SEPARATOR . $this->logPath . DIRECTORY_SEPARATOR . $manifestFileBasename . '.json');
    }

    /**
     * Returns the base template for job manifests consumed by this job.
     *
     * @return array
     */
    private function getJobManifestTemplate()
    {
        return [
            'info' => [
                'question_types' => [],
                'item_scoring_types_counts' => [],
            ],
            'imported_rubrics' => [],
            'imported_items' => [],
            'ignored_items' => [],
        ];
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
        $this->output->writeln('<info>' . static::INFO_OUTPUT_PREFIX . "Writing conversion results: " . $outputFilePath . '.json' . "</info>");
        $innerPath = explode('/', $outputFilePath);
        array_pop($innerPath);
        file_put_contents($outputFilePath . '.json', json_encode($results));
    }

    /**
     * Updates a given job manifest in place with the contents of a specified
     * job partial result object.
     *
     * @param array $manifest - the job manifest to update
     * @param array $results  - the partial job result object to read
     */
    private function updateJobManifest(array &$manifest, array $results)
    {
        if (empty($results['qtiitems'])) {
            return;
        }

        foreach ($results['qtiitems'] as &$itemResult) {
            // Log ignored items
            if (!isset($itemResult['item'])) {
                $manifest['ignored_items'][] = $itemResult['exception'];
                continue;
            }
            // Log processed items
            $itemReference = $itemResult['item']['reference'];
            $manifest['imported_items'][] = $itemReference;

            if (!empty($itemResult['rubric'])) {
                $rubricReference = $itemResult['rubric']['reference'];
                $manifest['imported_rubrics'][] = $rubricReference;
            }

            // Log item scoring type
            // if (isset($itemResult['item']['metadata']['scoring_type'])) {
            //     ++$manifest['info']['item_scoring_types_counts'][$itemResult['item']['metadata']['scoring_type']];
            // }
            if (isset($itemResult['item']['metadata']['scoring_type'])) {
                if (!isset($manifest['info']['item_scoring_types_counts'][$itemResult['item']['metadata']['scoring_type']])) {
                    $manifest['info']['item_scoring_types_counts'][$itemResult['item']['metadata']['scoring_type']] = 0;
                }
                ++$manifest['info']['item_scoring_types_counts'][$itemResult['item']['metadata']['scoring_type']];
            }
            foreach ($itemResult['questions'] as &$question) {
                // Log question types
                $manifest['info']['question_types'][] = $question['type'];
            }
            // Store assumptions
            if (!empty($itemResult['assumptions'])) {
                $manifest['warnings'][$itemReference] = array_unique($itemResult['assumptions']);
            }
        }
    }

    private function tearDown()
    {

    }

    public function showWarnings($message)
    {
        $this->output->writeln("<info>" . static::INFO_OUTPUT_PREFIX .$message." </info>");
    }

    private function validate()
    {
        $errors = [];
        $manifestFolders = $this->parseInputFolders();

        if (empty($manifestFolders)) {
            array_push($errors, 'No manifest(s) found in ' . $this->inputPath);
        }

        return $errors;
    }
}
