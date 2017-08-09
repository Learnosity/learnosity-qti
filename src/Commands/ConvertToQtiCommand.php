<?php

namespace LearnosityQti\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ConvertToQtiCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('convert:to:qti')
            ->setDescription('Converts QTI v2.1 to Learnosity JSON')
            ->setHelp('')
            ->addOption(
                'input',
                'i',
                InputOption::VALUE_REQUIRED,
                'The input path to your Learnosity content',
                null
            )
            ->addOption(
                'output',
                'o',
                InputOption::VALUE_REQUIRED,
                'An output path where the QTI will be saved',
                null
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln([
            'Usage:',
            '  <info>./mo [command] [args] [--help]</info>',
            '',
            'Flags:',
            "  <info>--help</info>\tPrint the applications help",
            "  <info>--version</info>\tPrint the applications version number",
            '',
            'Commands:',
            "  <info>convert:to:learnosity</info>\t\tConverts QTI v2.1 to Learnosity JSON",
            "  <info>convert:from:learnosity</info>\tConverts Learnosity JSON to QTI v2.1",
            '',
        ]);
    }
}
