<?php

namespace LearnosityQti\Services;

use \LearnosityQti\Utils\General\FileSystemHelper;
use \LearnosityQti\Utils\Item\ItemDefinitionBuilder;
use \LearnosityQti\Utils\Item\ItemService;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;

class ItemLayoutService
{
    protected $shouldRemoveItemContent              = true;
    protected $shouldRemoveItemMetadata             = true;
    protected $shouldSplitQuestionFeatureReferences = true;
    protected $shouldRebuildItemDefinition          = true;

    private $output;

    public function execute($inputPath, $outputPath, OutputInterface $output)
    {
        $this->output            = $output;
        $this->doItemMigration   = true;
        $this->recreateOutputDir = true;

        if ($this->recreateOutputDir) {
            FileSystemHelper::createOrReplaceDir($outputPath);
        }
        if ($this->doItemMigration) {
            $this->doItemMigration($inputPath, $outputPath);
        }
    }

    protected function doItemMigration($inputDirectory, $outputDirectory)
    {
        $fileFinder = new Finder();

        // Process an item batch at a time
        foreach ($fileFinder->files()->in($inputDirectory)->name('*.json') as $inputFile) {
            /** @var \SplFileInfo $inputFile */
            $this->output->writeln("<info>Setting item layouts from conversion file: {$inputFile->getRelativePathname()}</info>");

            $json = $inputFile->getContents();
            $encodedResult = $this->migrateBatchItemsJson($json);

            $inputDirPath = $inputFile->getRelativePath() . '/' . $inputFile->getBasename('.json');
            $this->persistResultsFile(json_decode($encodedResult, true), $outputDirectory . '/' . $inputDirPath);
        }

        $this->output->writeln('<info>Writing item JSON: ' . realpath($outputDirectory) . $inputDirPath . ".json</info>");
    }

    /**
     * Migrates a batch of items in JSON.
     *
     * @param  string $itemsJson
     *
     * @return string - migrated items JSON
     */
    protected function migrateBatchItemsJson($itemsJson)
    {
        return json_encode($this->migrateBatchItems(json_decode($itemsJson, true)));
    }

    /**
     * Migrates a batch of items.
     *
     * @param  array  $batchItems
     *
     * @return array - migrated items
     */
    protected function migrateBatchItems(array $batchItems)
    {
        if (empty($batchItems['qtiitems'])) {
            return $batchItems;
        }

        $itemIndex = 0;
        $totalItemsCount = count($batchItems['qtiitems']);
        foreach ($batchItems['qtiitems'] as $fileKey => &$qtiItem) {
            $itemIndex++;
            $this->output->writeln(
                "<comment>Setting layout for ({$itemIndex}/{$totalItemsCount}): {$fileKey}</comment>"
            );

            // Get the widgets JSON related to the item first before performing the migration
            $widgetsJson = [];
            if (!empty($qtiItem['questions'])) {
                $widgetsJson = array_merge($widgetsJson, $qtiItem['questions']);
            }
            if (!empty($qtiItem['features'])) {
                $widgetsJson = array_merge($widgetsJson, $qtiItem['features']);
            }

            // Do the rubric item first, if there is one
            if (!empty($qtiItem['rubric'])) {
                $qtiItem['rubric'] = $this->migrateRubricItem($qtiItem['rubric'], $widgetsJson);
                $this->output->writeln("<comment>Rubric item found; migrating...</comment>");
            }

            if (isset($qtiItem['item'])) {
                // Do the migration on the assessment item
                $qtiItem['item'] = $this->migrateItem($qtiItem['item'], $widgetsJson);
            } else {
                $this->output->writeln("<error>Not a valid item: ({$fileKey}); skipping...</error>");
            }
        }

        return $batchItems;
    }

    /**
     * Migrates a JSON item.
     *
     * @param  string $itemJson
     *
     * @return string - migrated item JSON result
     */
    protected function migrateItemJson($itemJson)
    {
        return json_encode($this->migrateItem(json_decode($itemJson, true)));
    }

    /**
     * Migrates an item.
     *
     * @param  array  $item
     *
     * @return array - migrated item result
     */
    public function migrateItem(array $item, array $widgetsJson = [])
    {
        if (isset($item['definition'])) {
            return $item;
        }

        if ($this->shouldSplitQuestionFeatureReferences) {
            $itemService = new ItemService();
            $item['questions'] = $itemService->getQuestionsListFromItem($item);
            $item['features'] = $itemService->getFeaturesListFromItem($item);
            unset($item['questionReferences']);
        }

        if ($this->shouldRebuildItemDefinition) {
            $definitionBuilder = new ItemDefinitionBuilder($item, $widgetsJson, true);
            $item['definition'] = $definitionBuilder->buildItemDefinition();
        }

        if ($this->shouldRemoveItemContent) {
            unset($item['content']);
        }
        if ($this->shouldRemoveItemMetadata) {
            unset($item['metadata']['authoring']);
            if (empty($item['metadata'])) {
                unset($item['metadata']);
            }
        }

        return $item;
    }

    /**
     * Migrates a rubric item.
     *
     * @param  array  $item
     *
     * @return array - migrated rubric item result
     */
    protected function migrateRubricItem(array $item, array $widgetsJson = [])
    {
        if (isset($item['definition'])) {
            return $item;
        }

        if ($this->shouldSplitQuestionFeatureReferences) {
            $itemService = new ItemService();
            $item['questions'] = $itemService->getQuestionsListFromItem($item);
            $item['features'] = $itemService->getFeaturesListFromItem($item);
            unset($item['questionReferences']);
        }

        if ($this->shouldRebuildItemDefinition) {
            $definitionBuilder = new ItemDefinitionBuilder($item, $widgetsJson);
            $item['definition'] = $definitionBuilder->buildItemDefinition();
        }

        if ($this->shouldRemoveItemContent) {
            unset($item['content']);
        }
        if ($this->shouldRemoveItemMetadata) {
            unset($item['metadata']);
        }

        return $item;
    }

    private function persistResultsFile(array $results, $outputFilePath)
    {
        $innerPath = explode('/', $outputFilePath);
        array_pop($innerPath);
        FileSystemHelper::createDirIfNotExists(implode('/', $innerPath));
        file_put_contents($outputFilePath . '.json', json_encode($results));
    }

    private function tearDown()
    {
        if (!$this->doItemMigration) {
            $this->output->writeln('<comment>No item migration run</comment>');
        }
        if (!$this->doImport) {
            $this->output->writeln('<comment>No import run, are you happy with the JSON? If so set, `$this->doImport = true`</comment>');
        }
    }
}
