<?php

namespace LearnosityQti\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DefaultCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('mo')
            ->setDescription('mo command for Learnosity QTI')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln([
            '<comment>Usage:</comment>',
            '  <info>mo [command] [options] [--help]</info>',
            '',
            '<comment>Flags:</comment>',
            "  <info>--help</info>\tPrint the applications help",
            "  <info>--version</info>\tPrint the applications version number",
            '',
            '<comment>Commands:</comment>',
            "  <info>mo list</info>\tRun this to see the available commands",
        ]);

        return Command::SUCCESS;
    }
}
