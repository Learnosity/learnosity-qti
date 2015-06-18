<?php


namespace Learnosity\Tests\Integration;


use Learnosity\AppContainer;
use Learnosity\EntityGenerator;

class EntityGeneratorTest extends \PHPUnit_Framework_TestCase
{

    public function testGenerateActivityClasses() {
        $generator = AppContainer::getApplicationContainer()->get('learnosity_entity_generator');
        $generator->generateActivityClasses();
    }
}
