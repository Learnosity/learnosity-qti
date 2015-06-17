<?php

require_once "vendor/autoload.php";

$gen = new \Learnosity\QtiImportDocumentationGenerator();
$gen->generateDocumentation();

$gen = new \Learnosity\EntityGenerator(new \Learnosity\Services\SchemasService());
$gen->generateQuestionsClasses();
