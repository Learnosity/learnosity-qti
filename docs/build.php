<?php

require __DIR__ . '/../vendor/autoload.php';

use LearnosityQti\AppContainer;

die('This script is disabled for now. Major issues updating the schema.');

// Generate entities
$generator = AppContainer::getApplicationContainer()->get('learnosity_entity_generator');
$generator->generateQuestionsClasses();
$generator->generateItemClasses();
$generator->generateActivityClasses();

// Generate documentation
// $generator = AppContainer::getApplicationContainer()->get('learnosity_documentation_generator');
// $generator->generateDocumentation();
