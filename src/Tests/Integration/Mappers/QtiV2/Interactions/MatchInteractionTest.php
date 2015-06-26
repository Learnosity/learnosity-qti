<?php

namespace Learnosity\Tests\Integration\Mappers\QtiV2\Interactions;


use Learnosity\AppContainer;
use Learnosity\Utils\FileSystemUtil;

class MatchInteractionTest extends \PHPUnit_Framework_TestCase {


    private $file;
    /* @var $mapper ItemMapper*/
    private $mapper;

    public function setup()
    {
        $this->file = FileSystemUtil::readFile(FileSystemUtil::getRootPath() .
            '/src/Tests/Fixtures/interactions/match.xml');
        $this->mapper = AppContainer::getApplicationContainer()->get('qtiv2_item_mapper');
    }

    public function testMappingMatchInteraction() {
        list($item, $questions) = $this->mapper->parse($this->file->getContents());
        die;
    }
}
