<?php

namespace LearnosityQti\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ListCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('list')
            ->setDescription('Lists tasks available in Learnosity QTI.')
            ->setHelp('This command prints all available commands to run')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln([
            '',
            '<comment>Usage:</comment>',
            '  <info>mo [command] [options] [--help]</info>',
            '',
            '<comment>Flags:</comment>',
            "  <info>--help</info>\t\t\tPrint the applications help",
            '',
            '<comment>Commands:</comment>',
            "  <info>convert:to:learnosity</info>\t\tConverts QTI v2.1 to Learnosity JSON",
            "  <info>convert:to:qti</info>\t\tConverts Learnosity JSON to QTI v2.1",
            "  <info>list</info>\t\t\t\tLists all commands available",
            '',
        ]);

        return Command::SUCCESS;
    }
}
