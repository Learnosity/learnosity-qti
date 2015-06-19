<?php

namespace Learnosity\Tests\Integration\Mappers\QtiV2\MergedInteractions;

use Learnosity\Mappers\QtiV2\Import\ItemMapper;
use Learnosity\Utils\FileSystemUtil;

class MergedTextEntryInteractionTest extends \PHPUnit_Framework_TestCase {

    private $file;

    public function setup()
    {
        $this->file = FileSystemUtil::readFile(FileSystemUtil::getRootPath() . '/src/Tests/Fixtures/interactions/textentryinteraction.xml');
    }

    public function testMergedTextInteraction()
    {
        $mapper = new ItemMapper();
        list($item, $questions) = $mapper->parse($this->file->getContents());
        die;
    }

}
