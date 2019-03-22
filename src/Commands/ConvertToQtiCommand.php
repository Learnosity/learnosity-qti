<?php

namespace LearnosityQti\Commands;

use LearnosityQti\Services\ConvertToQtiService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ConvertToQtiCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('convert:to:qti')
            ->setDescription('Converts Learnosity JSON to QTI v2.1')
            ->setHelp('')
            ->addOption(
                'input',
                'i',
                InputOption::VALUE_REQUIRED,
                'The input path to your Learnosity content',
                './data/input'
            )
            ->addOption(
                'output',
                'o',
                InputOption::VALUE_REQUIRED,
                'An output path where the QTI will be saved',
                './data/output'
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
            array_push($validationErrors, "The <info>input</info> and <info>output</info> options are required. Eg:");
        }

        // Make sure we can read the input folder, and write to the output folder
        if (!empty($inputPath) && !is_dir($inputPath)) {
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

        if (!empty($validationErrors)) {
            $output->writeln([
                '',
                "<error>Validation error</error>"
            ]);

            foreach ($validationErrors as $error) {
                $output->writeln($error);
            }

            $output->writeln([
                "  <info>mo convert:to:qti -i /path/to/qti -o /path/to/save/folder</info>"
            ]);
        } else {
            $Convert = new ConvertToQtiService($inputPath, $outputPath, $output);
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
