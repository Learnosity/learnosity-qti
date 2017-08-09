<?php

namespace LearnosityQti\Commands;

use LearnosityQti\Services\ConvertToLearnosityService;
use Symfony\Component\Console\Command\Command;
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
            ->setHelp('Converts Learnosity JSON to QTI v2.1, expects to run on a folder with an imsmanifest.xml file')
            ->addOption(
                'input',
                'i',
                InputOption::VALUE_REQUIRED,
                'The input path to your QTI content',
                null
            )
            ->addOption(
                'output',
                'o',
                InputOption::VALUE_REQUIRED,
                'An output path where the Learnosity JSON will be saved',
                null
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $validationErrors = [];
        $inputPath = $input->getOption('input');
        $outputPath = $input->getOption('output');

        // Validate the required options
        if (empty($inputPath) || empty($outputPath)) {
            array_push($validationErrors, "  The <info>input</info> and <info>output</info> options are required. Eg:");
        }

        // Make sure we can read the input folder, and write to the output folder
        if (!is_dir($inputPath)) {
            $output->writeln([
                "  Input path isn't a directory (<info>$inputPath</info>)"
            ]);
        }
        if (!is_dir($outputPath)) {
            $output->writeln([
                "  Output path isn't a directory (<info>$outputPath</info>)"
            ]);
        }
        if (!is_writable($outputPath)) {
            $output->writeln([
                "  Output path isn't writable (<info>$outputPath</info>)"
            ]);
        }

        if (count($validationErrors)) {
            $output->writeln([
                '',
                "<error>Validation error:</error>"
            ]);

            foreach ($validationErrors as $error) {
                $output->writeln($error);
            }

            $output->writeln([
                "    <info>mo convert:to:learnosity -i /path/to/qti -o /path/to/save/folder</info>"
            ]);
        } else {
            $Convert = new ConvertToLearnosityService($inputPath, $outputPath, $output);
            $result = $Convert->process();
            var_dump($result);
        }
    }
}
