<?php

namespace Learnosity\Tests\Integration\Mappers\QtiV2\Interactions;

use Learnosity\AppContainer;
use Learnosity\Utils\FileSystemUtil;

class GapMatchInteractionTest extends \PHPUnit_Framework_TestCase
{
    private function getFixtureFile($filepath)
    {
        return FileSystemUtil::readFile(FileSystemUtil::getRootPath() . '/src/Tests/Fixtures/' . $filepath)->getContents();
    }

    public function testSimpleCaseFromQtiWebsite()
    {
        $mapper = AppContainer::getApplicationContainer()->get('qtiv2_item_mapper');
        list($item, $questions, $exceptions) = $mapper->parse($this->getFixtureFile('interactions/gap_match.xml'));

        $this->assertNotNull($item);
        $this->assertInstanceOf('Learnosity\Entities\Item\item', $item);
    }
}
