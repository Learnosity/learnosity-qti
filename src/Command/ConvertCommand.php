<?php

namespace Learnosity\Command;

use Learnosity\AppContainer;
use Learnosity\Converter;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ConvertCommand extends Command
{
    protected function configure()
    {
        $this->setName('convert')->setDescription('Convert Format')
            ->addArgument(
                'input-format',
                InputArgument::REQUIRED,
                'Input format'
            )->addArgument(
                'output-format',
                InputArgument::REQUIRED,
                'Output format'
            )
            ->addOption(
                'mode',
                null,
                InputOption::VALUE_REQUIRED,
                'Stdin mode'
            )
            ->addOption(
                'base-assets-url',
                null,
                InputOption::VALUE_REQUIRED,
                'Base asset URL used to prepend to internal URLs'
            )
            ->addOption(
                'path',
                null,
                InputOption::VALUE_REQUIRED,
                'Path of the IMS CP package or folder'
            )
            ->addOption(
                'outputPath',
                null,
                InputOption::VALUE_REQUIRED,
                'Path of the IMS CP package or folder'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $mode = 'stdin';

        $inputFormat = $input->getArgument('input-format');
        $outputFormat = $input->getArgument('output-format');
        $baseAssetsUrl = $input->getOption('base-assets-url');

        $p = AppContainer::getApplicationContainer()->get('assets_processing');
        $p->setBaseAssetUrl($baseAssetsUrl);

        $path = $input->getOption('path');
        $outputPath = $input->getOption('outputPath');

        if ($path && $outputPath) {
            // It is a IMS CP Package
            $outputPath = Converter::parseIMSCPPackage($path, $outputPath);
            $output->writeln($outputPath);
        } else {
            $inputData = '';
            switch ($mode) {
                case 'stdin':
                    while ($f = fgets(STDIN)) {
                        $inputData .= $f . "\n";
                    }
                    break;
            }
            $this->processQtiAssessmentItem($inputData, $output, $baseAssetsUrl);
        }
    }

    protected function processQtiAssessmentItem($inputData, OutputInterface $output, $baseAssetsUrl = '')
    {
        list($item, $questions, $errors) = Converter::convertQtiItemToLearnosity($inputData, $baseAssetsUrl);
        $outputData = json_encode([$item, $questions, $errors]);
        $output->write($outputData);
    }
}
