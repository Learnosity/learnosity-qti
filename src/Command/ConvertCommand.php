<?php

namespace Learnosity\Command;


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
//            ->addArgument(
//                'mode',
//                InputArgument::REQUIRED,
//                'IO Mode')
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
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $mode = 'stdin';
        $inputFormat = $input->getArgument('input-format');
        $outputFormat = $input->getArgument('output-format');

        $inputData = '';

        switch ($mode) {

            case 'stdin':
                while ($f = fgets(STDIN)) {
                    $inputData .= $f . "\n";
                }
                break;
        }

        list($item, $questions, $errors) = Converter::convertQtiItemToLearnosity($inputData);
        $outputData = json_encode([$item, $questions, $errors]);
        $output->write($outputData);
    }
}
