<?php

namespace Learnosity\Tests\Integration;

use Learnosity\AppContainer;

class QtiImportDocumentationGeneratorTest extends \PHPUnit_Framework_TestCase
{
    public function testGenerateImportDocs()
    {
        $generator = AppContainer::getApplicationContainer()->get('learnosity_documentation_generator');
        $generator->generateDocumentation();
    }
} 
