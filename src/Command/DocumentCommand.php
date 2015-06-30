<?php


namespace Learnosity\Command;


use Learnosity\AppContainer;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DocumentCommand extends Command
{
    protected function configure()
    {
        $this->setName('doc:generate')->setDescription('Generate Documentation');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $generator = AppContainer::getApplicationContainer()->get('learnosity_documentation_generator');
        $generator->generateDocumentation();
        $output->writeln("Process complete successfully");
    }
}
