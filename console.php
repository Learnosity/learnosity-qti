<?php

require_once "vendor/autoload.php";

$application = new \Symfony\Component\Console\Application('LearnosiyConverter', '0.1');

$application->add(new \Learnosity\Command\ConvertCommand());
$application->add(new \Learnosity\Command\EntityCommand());
$application->add(new \Learnosity\Command\DocumentCommand());
$application->run();
