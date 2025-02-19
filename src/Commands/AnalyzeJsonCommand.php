<?php

namespace LearnosityQti\Commands;

use LearnosityQti\Services\AnalyzeJsonService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class AnalyzeJsonCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('analyze:json')
            ->setDescription('Analyses the JSON content for certain criteria')
            ->setHelp('')
            ->addOption(
                'input',
                'i',
                InputOption::VALUE_REQUIRED,
                'The input path to your Learnosity content',
                ''
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $validationErrors = [];
        $inputPath = $input->getOption('input');

        // Validate the required options
        if (empty($inputPath)) {
            array_push($validationErrors, "The <info>input</info> option is required. Eg:");
        }

        // Make sure we can read the input folder, and write to the output folder
        if (!empty($inputPath) && !is_dir($inputPath)) {
            $output->writeln([
                "Input path isn't a directory (<info>$inputPath</info>)"
            ]);
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
                "  <info>mo analyze:json -i /path/to/json</info>"
            ]);

            return Command::SUCCESS;
        } else {
            $Debug = AnalyzeJsonService::initClass($inputPath, $output);
            $Debug->process();
        }

        return Command::SUCCESS;
    }
}
