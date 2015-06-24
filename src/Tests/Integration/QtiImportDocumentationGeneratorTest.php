<?php

namespace Learnosity\Tests\Integration;

use Learnosity\QtiImportDocumentationGenerator;

class QtiImportDocumentationGeneratorTest extends \PHPUnit_Framework_TestCase
{
    public function testGenerateImportDocs()
    {
        $generator = new QtiImportDocumentationGenerator();
        $generator->generateDocumentation();
    }
} 
