<?php /** @noinspection PhpUnusedPrivateMethodInspection */
/** @noinspection HttpUrlsUsage */
/** @noinspection DuplicatedCode */
/** @noinspection PhpUnused */

/** @noinspection SpellCheckingInspection */

namespace LearnosityQti\Services;

use DOMDocument;
use DOMNode;
use DOMXPath;
use Exception;
use LearnosityQti\AppContainer;
use LearnosityQti\Converter;
use LearnosityQti\Domain\JobDataTrait;
use LearnosityQti\Exceptions\InvalidQtiException;
use LearnosityQti\Exceptions\MappingException;
use LearnosityQti\Utils\AssetsFixer;
use LearnosityQti\Utils\AssumptionHandler;
use LearnosityQti\Utils\CheckValidQti;
use LearnosityQti\Utils\General\FileSystemHelper;
use LearnosityQti\Utils\General\StringHelper;
use LearnosityQti\Utils\ResponseProcessingHandler;
use qtism\data\AssessmentItem;
use qtism\data\storage\xml\XmlDocument;
use qtism\data\storage\xml\XmlStorageException;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;
use Symfony\Component\HttpFoundation\File\File;

class ConvertToLearnosityService
{
    use JobDataTrait;

    const RESOURCE_TYPE_ITEM_2P1 = 'imsqti_item_xmlv2p1';
    const RESOURCE_TYPE_ITEM_2P0 = 'imsqti_item_xmlv2p0';
    const RESOURCE_TYPE_PASSAGE = 'webcontent';
    const INFO_OUTPUT_PREFIX = '';
    const CONVERT_LOG_FILENAME = 'convert-to-learnosity.log';

    protected string $inputPath;
    protected string $outputPath;
    protected OutputInterface $output;
    protected string|int $organisationId;
    protected string $isConvertPassageContent;
    protected string $isSingleItemConvert = 'N';

    /* Runtime options */
    protected bool $dryRun                     = false;
    protected bool $shouldAppendLogs           = false;
    protected bool $shouldGuessItemScoringType = true;

    /* Job-specific configurations */
    // Overrides identifiers to be the same as the filename

    protected bool $useFileNameAsIdentifier = false;
    // Uses the identifier found in learning object metadata if available
    protected bool $useMetadataIdentifier = true;
    // Resource identifiers sometimes (but not always) match the assessmentItem identifier, so this can be useful
    protected bool $useResourceIdentifier = false;
    // Look for the `identifier` attribute within each <assessmentItem>

    protected AssetsFixer $assetsFixer;
    // Hold the class instance.
    private static $instance = null;
    private string $finalPath;
    private string $logPath;
    private string $rawPath;
    private mixed $useItemIdentifier;
    private array $filtering;

    protected function __construct(
        $inputPath,
        $outputPath,
        OutputInterface $output,
        $organisationId,
        $isConvertPassageContent,
        $isSingleItemConvert
    ) {
        $this->inputPath               = $inputPath;
        $this->outputPath              = $outputPath;
        $this->output                  = $output;
        $this->organisationId          = $organisationId;
        $this->finalPath               = 'final';
        $this->logPath                 = 'log';
        $this->rawPath                 = 'raw';
        $this->isConvertPassageContent = $isConvertPassageContent;
        $this->isSingleItemConvert     = $isSingleItemConvert;
    }

    // The object is created from within the class itself
    // only if the class has no instance.
    public static function initClass(
        $inputPath,
        $outputPath,
        OutputInterface $output,
        $organisationId,
        $isConvertPassageContent = 'N',
        $isSingleItemConvert = 'N'
    ): ?ConvertToLearnosityService {
        if (!self::$instance) {
            self::$instance = new ConvertToLearnosityService(
                $inputPath,
                $outputPath,
                $output,
                $organisationId,
                $isConvertPassageContent,
                $isSingleItemConvert,
            );
        }

        return self::$instance;
    }

    // Return instance of the class
    public static function getInstance()
    {
        return self::$instance;
    }

    public function getInputPath(): string
    {
        return $this->inputPath;
    }

    public function getOrganisationId(): int|string
    {
        return $this->organisationId;
    }

    public function isUsingMetadataIdentifier(): bool
    {
        return $this->useMetadataIdentifier;
    }

    public function useMetadataIdentifier($useMetadataIdentifier): void
    {
        $this->useMetadataIdentifier = $useMetadataIdentifier;
    }

    public function isUsingResourceIdentifier(): bool
    {
        return $this->useResourceIdentifier;
    }

    public function useResourceIdentifier($useResourceIdentifier): void
    {
        $this->useResourceIdentifier = $useResourceIdentifier;
    }

    public function isUsingFileNameAsIdentifier(): bool
    {
        return $this->useFileNameAsIdentifier;
    }

    public function useFileNameAsIdentifier($useFileNameAsIdentifier): void
    {
        $this->useFileNameAsIdentifier = $useFileNameAsIdentifier;
    }

    public function isUsingItemIdentifier()
    {
        return $this->useItemIdentifier;
    }

    public function useItemIdentifier($useItemIdentifier): void
    {
        $this->useItemIdentifier = $useItemIdentifier;
    }

    /**
     * @throws MappingException
     */
    public function process(): array
    {
        if ($this->isSingleItemConvert != 'Y' && $this->isSingleItemConvert != 'YES') {
            $errors = $this->validate();
        }
        $result = [
            'status' => null,
            'message' => []
        ];

        if (!empty($errors) && ($this->isSingleItemConvert == 'N' || $this->isSingleItemConvert == 'NO')) {
            $result['status'] = false;
            $result['message'] = $errors;
            return $result;
        }

        // Setup output (or -o) subdirectories
        FileSystemHelper::createDirIfNotExists($this->outputPath . DIRECTORY_SEPARATOR . $this->finalPath);
        FileSystemHelper::createDirIfNotExists($this->outputPath . DIRECTORY_SEPARATOR . $this->logPath);
        FileSystemHelper::createDirIfNotExists($this->outputPath . DIRECTORY_SEPARATOR . $this->rawPath);

        $this->assetsFixer = new AssetsFixer($this->organisationId);
        if ($this->isSingleItemConvert == 'Y' || $this->isSingleItemConvert == 'YES') {
            $resultSingleFile = array();
            $inputXmlFile = new File($this->inputPath);
            $fileName = $inputXmlFile->getFileName();
            $currentDir = realpath($inputXmlFile->getPath());
            $file = $inputXmlFile->getRealPath();
            $tempDirectoryParts = explode(DIRECTORY_SEPARATOR, dirname($file));
            $dirName = $tempDirectoryParts[count($tempDirectoryParts) - 1];
            $assessmentItemContents = file_get_contents($file);
            $metadata['organisation_id'] = $this->organisationId;
            $convertedContent = $this->convertAssessmentItemInFile($assessmentItemContents, $currentDir, $fileName, static::RESOURCE_TYPE_ITEM_2P1, null, $metadata);
            $scoringRubric = '';

            if (isset($convertedContent['rubric'])) {
                // Check if scoring rubric is present in converted string
                $rubricReferenceToBeDelete = $this->checkScoringRubricExistInConvertedContent($convertedContent);
                if (sizeof($rubricReferenceToBeDelete) > 0) {
                    /** @noinspection PhpUnusedLocalVariableInspection */
                    foreach ($rubricReferenceToBeDelete as $id => $reference) {
                        $index = $this->deleteUnusedRubricFromConvertedContent($convertedContent, $reference);
                        $extraRubricContent = $convertedContent['features'][$index];
                        unset($convertedContent['features'][$index]);
                        $scoringRubric = $this->createNewScoringRubricItem($extraRubricContent, $convertedContent['rubric']['reference']);
                    }
                }
            }

            if (isset($convertedContent['rubric'])) {
                unset($convertedContent['rubric']);
            }

            if (!empty($convertedContent)) {
                $convertedContent = $this->removeUnusedDataFromItem($convertedContent);
                $resultSingleFile['qtiitems'][basename($currentDir) . '/' . $fileName] = $convertedContent;
            }

            if (!empty($scoringRubric)) {
                $scoringRubric = $this->removeUnusedDataFromItem($scoringRubric);
                $resultSingleFile['qtiitems'][basename($currentDir) . '/' . $scoringRubric['item']['reference']] = $scoringRubric;
            }
            $this->persistResultsFile($resultSingleFile, realpath($this->outputPath) . DIRECTORY_SEPARATOR . $this->rawPath . DIRECTORY_SEPARATOR . $dirName);
        } else {
            $this->parseContentPackage();
        }

        // Convert the item format (columns etc.)
        $ItemLayout = new ItemLayoutService();
        $ItemLayout->execute($this->outputPath . DIRECTORY_SEPARATOR . $this->rawPath . DIRECTORY_SEPARATOR, $this->outputPath . DIRECTORY_SEPARATOR . $this->finalPath, $this->output);

        $this->tearDown();

        return $result;
    }

    /**
     * Performs a conversion on each directory (one level deep)
     * inside the given source directory.
     *
     * @throws MappingException
     * @throws Exception
     */
    private function parseContentPackage(): void
    {
        $manifestFolders = $this->parseInputFolders();

        $finalManifest = $this->getJobManifestTemplate();

        foreach ($manifestFolders as $dir) {
            $tempDirectoryParts = explode(DIRECTORY_SEPARATOR, dirname($dir));
            $dirName = $tempDirectoryParts[count($tempDirectoryParts) - 1];
            $results = $this->convertQtiContentPackagesInDirectory(dirname($dir), $dirName);
            $this->updateJobManifest($finalManifest, $results);
            $this->persistResultsFile(
                $results,
                realpath($this->outputPath)
                . DIRECTORY_SEPARATOR
                . $this->rawPath
                . DIRECTORY_SEPARATOR
                . $dirName
            );
        }

        $this->flushJobManifest($finalManifest);
    }

    // Traverse the -i option and find all paths with an imsmanifest.xml
    private function parseInputFolders(): array
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
     * @param string $sourceDirectory
     * @param string $relativeSourceDirectoryPath
     *
     * @return array - the results of the conversion
     * @throws MappingException
     */
    private function convertQtiContentPackagesInDirectory(
        string $sourceDirectory,
        string $relativeSourceDirectoryPath
    ): array {
        $results = [
            'qtiitems' => [],
        ];

        $manifestFinder = new Finder();
        $manifestFinderPath = $manifestFinder->files()->in($sourceDirectory)->name('imsmanifest.xml');

        foreach ($manifestFinderPath as $manifestFile) {
            /** @var SplFileInfo $manifestFile */
            $currentDir = realpath($manifestFile->getPath());
            $fullFilePath = realpath($manifestFile->getPathname());
            $relativeDir = rtrim($relativeSourceDirectoryPath . '/' . $manifestFile->getRelativePath(), '/');
            $relativePath = rtrim($relativeSourceDirectoryPath . '/' . $manifestFile->getRelativePathname(), '/');

            $this->output->writeln(
                "<info>"
                . static::INFO_OUTPUT_PREFIX
                . "Processing manifest file: $relativePath </info>"
            );

            // build the DOMDocument object
            $manifestDoc = new DOMDocument();

            $manifestDoc->load($fullFilePath);

            $itemResources = $this->getItemResourcesByHrefFromDocument($manifestDoc);

            foreach ($itemResources as $resource) {
                $resourceHref = $resource['href'];
                $relatedResource = $resource['resource'];

                if (
                    $resource['type'] === static::RESOURCE_TYPE_PASSAGE
                    && $this->isConvertPassageContent != 'Y'
                    && $this->isConvertPassageContent != 'YES') {
                    continue;
                }

                $assessmentItemContents = file_get_contents($currentDir . '/' . $resourceHref);
                $itemReference = $this->getItemReferenceFromResource(
                    $relatedResource,
                    $assessmentItemContents,
                    $this->useMetadataIdentifier,
                    $this->useResourceIdentifier,
                    $this->useFileNameAsIdentifier,
                    $this->useItemIdentifier
                );

                // The QTI package requires that `identifier` be on the <assessmentItem> node
                // Check that it's there, or add it from the location we retrieved it from
                if (!empty($itemReference)) {
                    $assessmentItemContents = $this->checkAssessmentItemIdentifier(
                        $assessmentItemContents,
                        $itemReference,
                    );
                } else {
                    throw new MappingException(
                        'Fatal: Cannot find a valid identifier for '
                        . $resourceHref
                        . '. Perhaps try a different item-reference-source'
                    );
                }

                $itemTagsArray = $this->getTaxonPathEntryForItemTags($relatedResource);
                $metadata = [];
                $itemPointValue = $this->getPointValueFromResource($relatedResource);
                if (isset($itemPointValue)) {
                    $metadata['point_value'] = $itemPointValue;
                }


                $metadata['organisation_id'] = $this->organisationId;

                $this->output->writeln("<comment>Converting assessment item $itemReference: $relativeDir/$resourceHref</comment>");

                $convertedContent = $this->convertAssessmentItemInFile(
                    $assessmentItemContents,
                    $currentDir,
                    $resourceHref,
                    $resource['type'],
                    $itemReference,
                    $metadata,
                    $itemTagsArray,
                );

                $scoringRubric = '';

                if (isset($convertedContent['rubric'])) {
                    // Check if scoring rubric is present in converted string
                    $rubricReferenceToBeDelete = $this->checkScoringRubricExistInConvertedContent($convertedContent);
                    if (sizeof($rubricReferenceToBeDelete) > 0) {
                        /** @noinspection PhpUnusedLocalVariableInspection */
                        foreach ($rubricReferenceToBeDelete as $id => $reference) {
                            $index = $this->deleteUnusedRubricFromConvertedContent($convertedContent, $reference);
                            $extraRubricContent = $convertedContent['features'][$index];
                            unset($convertedContent['features'][$index]);
                            $scoringRubric = $this->createNewScoringRubricItem($extraRubricContent, $convertedContent['rubric']['reference']);
                        }
                    }
                }


                if (isset($convertedContent['rubric'])) {
                    unset($convertedContent['rubric']);
                }
                $convertedContent = $this->removeUnusedDataFromItem($convertedContent);
                if (!empty($convertedContent)) {
                    $results['qtiitems'][basename($relativeDir) . '/' . $resourceHref] = $convertedContent;
                }
                $scoringRubric = $this->removeUnusedDataFromItem($scoringRubric);
                if (!empty($scoringRubric)) {
                    $results['qtiitems'][basename($relativeDir) . '/' . $scoringRubric['item']['reference']] = $scoringRubric;
                }
            }
        }
        return $results;
    }

    /**
     * Remove the widget_type , item_reference, content from the converted content.
     * Return converted content after deleting the data.
     *
     * @param string|array $convertedContent converted content
     */
    private function removeUnusedDataFromItem(string|array $convertedContent): array|string
    {
        if (is_array($convertedContent) && array_key_exists('questions', $convertedContent)) {
            foreach ($convertedContent['questions'] as $id => $data) {
                if (isset($data['widget_type'])) {
                    unset($convertedContent['questions'][$id]['widget_type']);
                }
                if (isset($data['item_reference'])) {
                    unset($convertedContent['questions'][$id]['item_reference']);
                }
                if (isset($data['content'])) {
                    unset($convertedContent['questions'][$id]['content']);
                }
                if (isset($convertedContent['item']['metadata']['rubric_reference'])) {
                    unset($convertedContent['item']['metadata']['rubric_reference']);
                }
            }
        }

        return $this->removeUnusedFeatureData($convertedContent);
    }

    /**
     * Removes the widget_type, item_reference and content
     * from the feature array as these are no longer needed
     * return converted content after deleting
     *
     * @param array|string $convertedContent converted content
     */
    private function removeUnusedFeatureData(array|string $convertedContent): array|string
    {
        if (is_array($convertedContent) && array_key_exists('features', $convertedContent)) {
            foreach ($convertedContent['features'] as $id => $data) {
                if (isset($data['widget_type'])) {
                    unset($convertedContent['features'][$id]['widget_type']);
                }
                if (isset($data['item_reference'])) {
                    unset($convertedContent['features'][$id]['item_reference']);
                }
                if (isset($data['content'])) {
                    unset($convertedContent['features'][$id]['content']);
                }
            }
        }

        return $convertedContent;
    }

    /**
     * Create a new item with only a shared passage
     *
     * @param array $scoringRubric rubric needs to be attached with the item
     * @param int|string $reference reference of the rubric
     *
     * @return array array
     */
    private function createNewScoringRubricItem(
        array $scoringRubric,
        int|string $reference
    ): array {
        $itemData['reference'] = $reference;
        $itemData['status'] = 'published';
        $itemData['questions'] = array();
        $itemData['definition']['template'] = 'dynamic';
        $itemData['definition']['widgets'][]['reference'] = $scoringRubric['reference'];
        $itemData['features'][]['reference'] = $scoringRubric['reference'];
        $featuresData = array($scoringRubric);

        return [
            'item' => $itemData,
            'features' => $featuresData,
            'questions' => array(),
        ];
    }

    /**
     * Check if a scoring rubric exist based on the rubric view
     *
     * @param array $rubricArray rubric array
     * @return array reference of the deleted rubric
     */
    private function checkScoringRubricExistInConvertedContent(array $rubricArray): array
    {
        $rubricReferenceToBeDelete = array();
        if (isset($rubricArray['rubric']['features'])) {
            /** @noinspection PhpUnusedLocalVariableInspection */
            foreach ($rubricArray['rubric']['features'] as $id => $value) {
                if ($value['view'] == 3) {
                    $rubricReferenceToBeDelete[] = $value['reference'];
                }
            }
        }
        return $rubricReferenceToBeDelete;
    }

    /**
     * Delete the scoring rubric from converted content
     *
     * @param array $convertedContent converted content
     * @param int|string $reference reference of the rubric
     *
     * @return int|string index of the rubric to be deleted
     */
    private function deleteUnusedRubricFromConvertedContent(
        array $convertedContent,
        int|string $reference
    ): int|string {
        $index = '';
        foreach ($convertedContent['features'] as $id => $featureContent) {
            if ($featureContent['reference'] == $reference) {
                $index = $id;
            }
        }

        return $index;
    }

    /**
     * Retrieves any <assessmentItem> or shared passage resource elements
     * found in a given manifest XML document.
     *
     * @param  DOMDocument $manifestDoc - the document to search
     *
     * @return array
     * @noinspection PhpPossiblePolymorphicInvocationInspection
     */
    private function getItemResourcesByHrefFromDocument(DOMDocument $manifestDoc): array
    {
        $itemResources = [];
        $resources = $manifestDoc->getElementsByTagName('resource');

        while (($resource = $resources->item(0)) != null) {
            $resourceHref = $resource->getAttribute('href');
            $resourceType = $resource->getAttribute('type');

            if ($resourceType === static::RESOURCE_TYPE_ITEM_2P1|| $resourceType === static::RESOURCE_TYPE_ITEM_2P0) {
                $itemResources[] = [
                    'href' => $resourceHref,
                    'resource' => $resource,
                    'type' => $resourceType
                ];
            } else if ($resourceType === static::RESOURCE_TYPE_PASSAGE) {
                $itemResources[] = [
                    'href' => $resourceHref,
                    'resource' => $resource,
                    'type' => $resourceType
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
     * @param DOMNode $resource
     *
     * @return string|null - the identifier
     */
    private function getIdentifierFromResourceMetadata(DOMNode $resource): ?string
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
    private function metadataIdentifierExists(DOMNode $resource): bool
    {
        $xpath = new DOMXPath($resource->ownerDocument);
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
    private function getTaxonPathEntryForItemTags(DOMNode $resource): array
    {
        $xpath = new DOMXPath($resource->ownerDocument);
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
     * @param $contents
     * @param $currentDir
     * @param $resourceHref
     * @param $resourceType
     * @param null $itemReference - Optional
     * @param array $metadata
     * @param array $itemTagsArray
     *
     * @return array|null - the results of the conversion
     */
    protected function convertAssessmentItemInFile(
        $contents,
        $currentDir,
        $resourceHref,
        $resourceType,
        $itemReference = null,
        array $metadata = [],
        array $itemTagsArray = [],
    ): ?array {
        try {
            $xmlString = $contents;

            // Check that we're on an <assessmentItem>
//            if (
//                (
//                    $resourceType === static::RESOURCE_TYPE_ITEM_2P1 ||
//                    $resourceType === static::RESOURCE_TYPE_ITEM_2P0
//                )
//                && !CheckValidQti::isAssessmentItem($xmlString)
//            ) {
//                $this->output->writeln("<info>" . static::INFO_OUTPUT_PREFIX . "Not an <assessmentItem>, moving on...</info>");
//                return null;
//            }

            $resourcePath = $currentDir . '/' . $resourceHref;

            $results = $this->convertAssessmentItem($xmlString, $resourceType, $itemReference, $resourcePath, $metadata, $itemTagsArray);
        } catch (Exception $e) {
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
     * @param string $xmlString
     * @param $resourceType
     * @param string|null $itemReference - Optional
     * @param string|null $resourcePath - Optional
     * @param array $metadata
     * @param array $itemTagsArray
     *
     * @return array - the results of the conversion
     *
     * @throws MappingException
     * @throws InvalidQtiException
     * @throws XmlStorageException
     * @throws Exception
     */
    private function convertAssessmentItem(
        string $xmlString,
        $resourceType,
        string $itemReference = null,
        string $resourcePath = null,
        array $metadata = [],
        array $itemTagsArray = []
    ): array {
        AssumptionHandler::flush();
        $xmlString = CheckValidQti::preProcessing($xmlString);

        if ($resourceType === static::RESOURCE_TYPE_ITEM_2P1 || $resourceType === static::RESOURCE_TYPE_ITEM_2P0) {
            $result = Converter::convertQtiItemToLearnosity($xmlString, null, false, $resourcePath, $itemReference, $metadata);
        } elseif ($resourceType == static::RESOURCE_TYPE_PASSAGE && ($this->isConvertPassageContent == 'Y' || $this->isConvertPassageContent == 'YES')) {
            $result = Converter::convertPassageItemToLearnosity($xmlString, null, true, $resourcePath, $itemReference, $metadata);
        }

        $item       = !empty($result['item']) ? $result['item'] : array();
        $questions  = !empty($result['questions']) ? $result['questions'] : array();
        $features   = !empty($result['features']) ? $result['features'] : array();
        $manifest   = !empty($result['messages']) ? $result['messages'] : array();
        $rubricItem = !empty($result['rubric']) ? $result['rubric'] : null;
        $questions  = !empty($questions) ? $this->assetsFixer->fix($questions) : array();
        $features   = !empty($features) ? $this->assetsFixer->fix($features) : array();

        // Return those results!
        list($item, $questions) = CheckValidQti::postProcessing($item, $questions, []);

        if ($this->shouldGuessItemScoringType && ($resourceType === static::RESOURCE_TYPE_ITEM_2P1 || $resourceType === static::RESOURCE_TYPE_ITEM_2P0)) {
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
     * @param DOMNode $resource
     * @param $assessmentItemContents
     * @param boolean $useMetadataIdentifier - Optional. true by default; set false to disable
     * @param boolean $useResourceIdentifier - Optional
     * @param boolean $useFileNameAsIdentifier - Optional
     * @param bool $useItemIdentifier
     *
     * @return string|null
     * @noinspection PhpPossiblePolymorphicInvocationInspection
     */
    private function getItemReferenceFromResource(
        DOMNode $resource,
        $assessmentItemContents,
        bool $useMetadataIdentifier = true,
        bool $useResourceIdentifier = false,
        bool $useFileNameAsIdentifier = false,
        bool $useItemIdentifier = false
    ): ?string {
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

        // If we haven't already found an item reference (and it was enabled via the command), look for it in assessmentItem
        if ($useItemIdentifier && empty($itemReference)) {
            $itemReference = $this->getIdentifierFromAssessmentItem($assessmentItemContents);
        }

        return $itemReference;
    }

    /**
     * Takes the resource href and extracts the file name out of it.
     *
     * @param string $resourceHref
     * @param string $suffix
     *
     * @return string
     * @example items/My-File.xml will return My-File
     */
    private function getIdentifierFromResourceHref(
        string $resourceHref,
        string $suffix = '.xml',
    ): string {
        return basename($resourceHref, $suffix);
    }

    /**
     * Look at an <assessmentItem> XML string to see if there's an `identifier` attribute.
     * If there is, return that as the item reference (identifier)
     *
     * @param string $xmlString
     *
     * @return string|null
     */
    private function getIdentifierFromAssessmentItem(string $xmlString): ?string
    {
        $document = new DOMDocument();
        $document->loadXML($xmlString);
        $elAssessmentItem = $document->getElementsByTagName('assessmentItem');
        $identifier = null;

        // Find the <assessmentItem> element
        foreach ($elAssessmentItem as $node) {
            if ($node->nodeName === 'assessmentItem') {
                // Iterate over each attribute and check for the `identifier` attribute
                foreach ($node->attributes as $attribute) {
                    if ($attribute->name === 'identifier') {
                        if (!empty($attribute->value)) {
                            $identifier = $attribute->value;
                        }
                    }
                }
            }
        }

        return $identifier;
    }

    /**
     * Checks an <assessmentItem> string to make sure the `identifier` attribute
     * is present, if not, add it from where the user specified in the command.
     *
     * @param string $xmlString
     * @param string $itemReference
     *
     * @return string
     */
    private function checkAssessmentItemIdentifier(
        string $xmlString,
        string $itemReference,
    ): string {
        $document = new DOMDocument();
        $document->loadXML($xmlString);
        $elAssessmentItem = $document->getElementsByTagName('assessmentItem');
        $foundIdentifer = false;

        // Find the <assessmentItem> element
        foreach ($elAssessmentItem as $node) {
            // Iterate over each attribute and check for the `identifier` attribute
            foreach ($node->attributes as $attribute) {
                if ($attribute->name === 'identifier') {
                    if (empty($attribute->value)) {
                        $attribute->value = $itemReference;
                    }
                }
            }
            // We didn't find an `identifier` attribute, add one manually
            if ($foundIdentifer === false) {
                $node->setAttribute('identifier', $itemReference);
            }
        }

        return $document->saveXML();
    }

    /**
     * Tries to determine item scoring information based on response
     * processing rules in the given XML string.
     *
     * @param string $xmlString
     *
     * @return string|array The item scoring type if found as the first arg;
     *                         The list of log messages as the second arg.
     * @throws XmlStorageException
     * @throws MappingException
     * @throws Exception
     */
    private function getItemScoringTypeFromResponseProcessing(
        string $xmlString
    ): string|array {
        $xmlString = AppContainer::getApplicationContainer()
            ->get('xml_assessment_items_processing')
            ->processXml($xmlString);

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
        /** @noinspection PhpUnusedLocalVariableInspection */
        list($responseProcessing, $assumedItemScoringType, $messages) = ResponseProcessingHandler::handle($assessmentItem, $xmlString);
        if (empty($messages)) {
            $messages = [];
        }

        return [$assumedItemScoringType, $messages];
    }

    private function getPointValueFromResource(DOMNode $resource): ?int
    {
        $pointValue = null;

        $xpath = $this->getXPathForQtiDocument($resource->ownerDocument);
        $pointValueEntries = ($xpath->query('./qti:metadata/lom:lom/lom:classification/lom:taxonPath/lom:source/lom:string[text() = \'cf$Point Value\']/../../lom:taxon/lom:entry', $resource));
        if ($pointValueEntries->length > 0) {
            $pointValue = (int) $pointValueEntries->item(0)->nodeValue;
        }

        return $pointValue;
    }

    private function getXPathForQtiDocument(DOMDocument $document): DOMXPath
    {
        $xpath = new DOMXPath($document);
        $xpath->registerNamespace('lom', 'http://ltsc.ieee.org/xsd/LOM');
        $xpath->registerNamespace('qti', 'http://www.imsglobal.org/xsd/imscp_v1p1');

        return $xpath;
    }

    /**
     * Returns a finder that is based on the finder provided, with any
     * specified filtering rules applied.
     *
     * @param  Finder $finder
     * @param array|null $filtering
     *
     * @return Finder
     */
    private function applyFilteringToFinder(
        Finder $finder,
        array $filtering = null
    ): Finder {
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
     *
     * @throws Exception
     */
    private function flushJobManifest(array $manifest): void
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
    private function getJobManifestTemplate(): array
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
    private function persistResultsFile(
        array $results,
        string $outputFilePath
    ): void {
        if ($this->dryRun) {
            return;
        }
        $this->output->writeln(
            '<info>'
            . static::INFO_OUTPUT_PREFIX
            . "Writing conversion results: "
            . $outputFilePath
            . '.json'
            . "</info>"
        );

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
    private function updateJobManifest(array &$manifest, array $results): void
    {
        if (empty($results['qtiitems'])) {
            return;
        }

        foreach ($results['qtiitems'] as $itemResult) {
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
            foreach ($itemResult['questions'] as $question) {
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

    public function showWarnings($message): void
    {
        $this->output->writeln("<info>" . static::INFO_OUTPUT_PREFIX .$message." </info>");
    }

    private function validate(): array
    {
        $errors = [];
        $manifestFolders = $this->parseInputFolders();

        if (empty($manifestFolders)) {
            $errors[] = 'No manifest(s) found in ' . $this->inputPath;
        }

        return $errors;
    }
}
