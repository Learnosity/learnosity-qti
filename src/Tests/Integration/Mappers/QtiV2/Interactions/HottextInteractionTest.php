<?php

namespace Learnosity\Tests\Integration\Mappers\QtiV2\Interactions;

use Learnosity\AppContainer;
use Learnosity\Utils\FileSystemUtil;

class HottextInteractionTest extends \PHPUnit_Framework_TestCase
{
    private function getFixtureFile($filepath)
    {
        return FileSystemUtil::readFile(FileSystemUtil::getRootPath() . '/src/Tests/Fixtures/' . $filepath)->getContents();
    }

    public function testSimpleCaseFromQTIWebsite()
    {
        $mapper = AppContainer::getApplicationContainer()->get('qtiv2_item_mapper');
        list($item, $questions, $exceptions) = $mapper->parse($this->getFixtureFile('interactions/hottext.xml'));
    }
}
