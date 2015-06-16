<?php

require_once "vendor/autoload.php";

$gen = new \Learnosity\QtiImportDocumentationGenerator();
$gen->generateInterationDocument();

$gen = new \Learnosity\EntityGenerator(new \Learnosity\Services\SchemasService());
$gen->generateQuestionsClasses();
