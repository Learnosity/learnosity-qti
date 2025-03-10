<?php

namespace LearnosityQti\Services;

use LearnosityQti\Processors\QtiV2\Out\Constants as LearnosityExportConstant;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\Iterator\RecursiveDirectoryIterator;
use Symfony\Component\Finder\SplFileInfo;

class AnalyzeJsonService
{
    const INFO_OUTPUT_PREFIX = '';

    protected $inputPath;
    protected $output;

    private static $instance = null;

    private function __construct($inputPath, OutputInterface $output)
    {
        $this->inputPath = $inputPath;
        $this->output    = $output;

        LearnosityExportConstant::setInputPath($this->getInputPath() . '/');
    }

    // The object is created from within the class itself
    // only if the class has no instance.
    public static function initClass($inputPath, OutputInterface $output)
    {
        if (!self::$instance) {
            self::$instance = new AnalyzeJsonService($inputPath, $output);
        }
        return self::$instance;
    }

    public function getInputPath()
    {
        return $this->inputPath;
    }

    public function process()
    {
        $jsonFiles = $this->parseInputFolders();

        $this->findCompositeItems($jsonFiles);
        $this->findPassages($jsonFiles);
        $this->findScoreNonDefault($jsonFiles);
    }

    private function findCompositeItems($jsonFiles)
    {
        $this->output->writeln("\n<info>" . static::INFO_OUTPUT_PREFIX . "Looking for composite items: {$this->inputPath}</info>");

        $numFound = 0;
        foreach ($jsonFiles as $file) {
            $itemContent = file_get_contents($file);
            $json = json_decode($itemContent, true);
            if (count($json['questions']) >= 2) {
                $numFound++;
                $this->output->writeln("<comment>Composite " . basename($file) . "</comment>");
            }
        }

        if (!$numFound) {
            $this->output->writeln("<info>" . static::INFO_OUTPUT_PREFIX . "No composite items found</info>\n");
        }
    }

    private function findPassages($jsonFiles)
    {
        $this->output->writeln("\n<info>" . static::INFO_OUTPUT_PREFIX . "Looking for passages: {$this->inputPath}</info>");

        $numFound = 0;
        foreach ($jsonFiles as $file) {
            $itemContent = file_get_contents($file);
            $json = json_decode($itemContent, true);
            if (count($json['features'])) {
                $numPassages = count(array_filter($json['features'], function ($obj) {
                    return isset($obj['data']['type']) && $obj['data']['type'] === 'sharedpassage';
                }));
                $numFound += $numPassages;
                $this->output->writeln("<comment>Passage ({$numPassages}) " . basename($file) . "</comment>");
            }
        }

        if (!$numFound) {
            $this->output->writeln("<info>" . static::INFO_OUTPUT_PREFIX . "No passage items found</info>\n");
        }
    }

    private function findScoreNonDefault($jsonFiles)
    {
        $this->output->writeln("\n<info>" . static::INFO_OUTPUT_PREFIX . "Looking for non-default scores: {$this->inputPath}</info>");

        $numFound = 0;
        foreach ($jsonFiles as $file) {
            $itemContent = file_get_contents($file);
            $json = json_decode($itemContent, true);
            foreach ($json['questions'] as $q) {
                if (isset($q['data']['validation']['valid_response']['score']) && $q['data']['validation']['valid_response']['score'] !== 1) {
                    $numFound++;
                    $this->output->writeln("<comment>Non-default score ({$q['data']['validation']['valid_response']['score']}) " . basename($file) . "</comment>");
                }
            }
        }

        if (!$numFound) {
            $this->output->writeln("<info>" . static::INFO_OUTPUT_PREFIX . "No non-default scores found</info>\n");
        }
    }

    /**
     * Traverse the -i option and find all paths with files
     */
    private function parseInputFolders()
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
                    foreach ($this->itemReferences as $itemref) {
                        if (isset($itemref) && is_object($itemref) && isset($itemref->id)) {
                            $itemref = md5($itemref->id);
                        } else {
                            $itemref = md5($itemref);
                        }
                        $folders[] = $this->inputPath . '/items/' . $itemref . '.json';
                    }
                } else {
                    $this->output->writeln("<error>Error converting : No item refrences found in the activity json</error>");
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
}
