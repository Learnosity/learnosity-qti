<?php

require __DIR__ . '/../vendor/autoload.php';

use LearnosityQti\AppContainer;

// Generate entities
// $generator = AppContainer::getApplicationContainer()->get('learnosity_entity_generator');
// $generator->generateQuestionsClasses();
// $generator->generateItemClasses();
// $generator->generateActivityClasses();

// Generate documentation
$generator = AppContainer::getApplicationContainer()->get('learnosity_documentation_generator');
$generator->generateDocumentation();
