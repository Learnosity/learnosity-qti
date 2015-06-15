<?php

namespace Learnosity\Command;


use Learnosity\AppContainer;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class EntityCommand extends Command
{

    protected function configure()
    {
        $this->setName('entity:generate')->setDescription('Generate Learnosity Entity Classes');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $generator = AppContainer::getApplicationContainer()->get('learnosity_entity_generator');
        $generator->generateQuestionsClasses();
        $output->writeln("Process complete successfully");
    }
}