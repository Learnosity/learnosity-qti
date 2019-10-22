<?php

namespace LearnosityQti\Commands;

use LearnosityQti\Services\ConvertToLearnosityService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ConvertToLearnosityCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('convert:to:learnosity')
            ->setDescription('Converts Learnosity JSON to QTI v2.1')
            ->setHelp('Converts QTI v2.1 to Learnosity JSON, expects to run on folder(s) with a imsmanifest.xml file')
            ->addOption(
                'input',
                'i',
                InputOption::VALUE_REQUIRED,
                'The input path to your QTI content',
                './data/input'
            )
            ->addOption(
                'output',
                'o',
                InputOption::VALUE_REQUIRED,
                'An output path where the Learnosity JSON will be saved',
                './data/output'
            )
            ->addOption(
                'organisation_id',
                '',
                InputOption::VALUE_REQUIRED,
                'The identifier of the item bank you want to import content into',
                ''
            )
            ->addOption(
                'item-reference-source',
                '',
                InputOption::VALUE_OPTIONAL,
                'The source to use to extract the reference for the item. ' .
                    'Valid values are the following: ' . PHP_EOL .
                    '  item     - uses the identifier attribute on the <assessmentItem> element' . PHP_EOL .
                    '  metadata - uses the <identifier> element from the LOM metadata in the manifest, if available. If no <identifier> is found, then this parameter operates in "item" mode' . PHP_EOL .
                    '  resource - uses the identifier attribute on the <resource> element in the manifest' . PHP_EOL .
                    '  filename - uses the basename of the <assessmentItem> XML file' . PHP_EOL,
                'metadata'
            )
            ->addOption(
                'passage-only-items',
                '',
                InputOption::VALUE_OPTIONAL,
                'If you pass the value as "Y", the conversion library will convert regular '
                . 'assessment items as well as passage-only items, if defined in '
                . 'the manifest',
                'N'
            )
            ->addOption(
                'single-item',
                '',
                InputOption::VALUE_OPTIONAL,
                'If you pass the value as "Y", the conversion library will convert only single '
                 . 'xml file',
                'N'
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $validationErrors = [];
        $inputPath = $input->getOption('input');
        $outputPath = $input->getOption('output');
        $organisationId = $input->getOption('organisation_id');
        $itemReferenceSource = $input->getOption('item-reference-source');
        $isConvertPassageContent = strtoupper($input->getOption('passage-only-items'));
        $isSingleItemConvert = strtoupper($input->getOption('single-item'));

        // Validate the required options
        if (empty($inputPath) || empty($outputPath)) {
            array_push($validationErrors, "The <info>input</info> and <info>output</info> options are required. Eg:");
        }

        // Make sure we can read the input folder, and write to the output folder
        if (!empty($inputPath) && !is_dir($inputPath) && $isSingleItemConvert != 'Y' && $isSingleItemConvert != 'YES') {
            $output->writeln([
                "Input path isn't a directory (<info>$inputPath</info>)"
            ]);
        }
        if (!empty($outputPath) && !is_dir($outputPath)) {
            $output->writeln([
                "Output path isn't a directory (<info>$outputPath</info>)"
            ]);
        } elseif (!empty($outputPath) && !is_writable($outputPath)) {
            $output->writeln([
                "Output path isn't writable (<info>$outputPath</info>)"
            ]);
        }

        if (empty($organisationId)) {
            array_push($validationErrors, "The <info>organisation_id</info> option is required for asset uploads.");
        }

        $requiredValuesForPassageConversion = ['Yes or Y', 'No or N'];
        if ($isConvertPassageContent != 'Y' && $isConvertPassageContent != 'YES' && $isConvertPassageContent != 'N' && $isConvertPassageContent != 'NO') {
            array_push(
                $validationErrors,
                "The <info>passage-only-items</info> must be one of the following values: " . join(', ', $requiredValuesForPassageConversion)
            );
        }

        $requiredValuesForSingleItemConversion = ['Yes or Y', 'No or N'];
        if ($isSingleItemConvert != 'Y' && $isSingleItemConvert != 'YES' && $isSingleItemConvert != 'N' && $isSingleItemConvert != 'NO') {
            array_push(
                $validationErrors,
                "The <info>single-item</info> must be one of the following values: " . join(', ', $requiredValuesForSingleItemConversion)
            );
        }

        $validItemReferenceSources = ['item', 'metadata', 'filename', 'resource'];
        if (isset($itemReferenceSource) && !in_array($itemReferenceSource, $validItemReferenceSources)) {
            array_push(
                $validationErrors,
                "The <info>item-reference-source</info> must be one of the following values: " . join(', ', $validItemReferenceSources)
            );
        }

        if (!empty($validationErrors)) {
            $output->writeln([
                '',
                "<error>Validation error</error>"
            ]);

            foreach ($validationErrors as $error) {
                $output->writeln($error);
            }

            $output->writeln([
                "  <info>mo convert:to:learnosity --input /path/to/qti --output /path/to/save/folder --organisation_id [integer]</info>"
            ]);
        } else {
            $Convert = ConvertToLearnosityService::initClass($inputPath, $outputPath, $output, $organisationId, $isConvertPassageContent, $isSingleItemConvert);

            $Convert->useMetadataIdentifier(true);
            $Convert->useResourceIdentifier(false);
            $Convert->useFileNameAsIdentifier(false);
            if ($itemReferenceSource === 'item') {
                $Convert->useMetadataIdentifier(false);
                $Convert->useResourceIdentifier(false);
                $Convert->useFileNameAsIdentifier(false);
            } elseif ($itemReferenceSource === 'filename') {
                $Convert->useMetadataIdentifier(false);
                $Convert->useResourceIdentifier(false);
                $Convert->useFileNameAsIdentifier(true);
            } elseif ($itemReferenceSource === 'resource') {
                $Convert->useMetadataIdentifier(false);
                $Convert->useResourceIdentifier(true);
                $Convert->useFileNameAsIdentifier(false);
            }

            $result = $Convert->process();
            if ($result['status'] === false) {
                $output->writeln('<error>Error running job</error>');
                foreach ($result['message'] as $m) {
                    $output->writeln($m);
                }
            }
        }
    }
}
