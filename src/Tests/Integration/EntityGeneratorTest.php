<?php


namespace Learnosity\Tests\Integration;

use Learnosity\AppContainer;

class EntityGeneratorTest extends \PHPUnit_Framework_TestCase
{
    public function testGenerateEverything()
    {
        $this->markTestSkipped();
        $generator = AppContainer::getApplicationContainer()->get('learnosity_entity_generator');
        $generator->generateQuestionsClasses();
        $generator->generateItemClasses();
        $generator->generateActivityClasses();
    }
}
