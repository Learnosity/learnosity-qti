<?php

require 'vendor/autoload.php';

use Learnosity\AppContainer;

// Generate documentation
$generator = AppContainer::getApplicationContainer()->get('learnosity_documentation_generator');
$generator->generateDocumentation();

// Generate entities
$generator = AppContainer::getApplicationContainer()->get('learnosity_entity_generator');
$generator->generateQuestionsClasses();
$generator->generateItemClasses();
$generator->generateActivityClasses();
