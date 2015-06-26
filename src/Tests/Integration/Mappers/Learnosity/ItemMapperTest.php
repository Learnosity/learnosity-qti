<?php

namespace Learnosity\Tests\Mappers\Learnosity;

use Learnosity\Mappers\Learnosity\Import\ItemMapper;
use Learnosity\Utils\FileSystemUtil;

class ItemMapperTest extends \PHPUnit_Framework_TestCase
{
    public function testMappingItem()
    {
        $this->markTestSkipped('Need to be implemented');

        $itemJson = FileSystemUtil::readJsonContent(FileSystemUtil::getRootPath() . '/src/Tests/Fixtures/sampleitemmcq.json');
        $learnosityItemMapper = new ItemMapper();
        $item = $learnosityItemMapper->parse($itemJson);

        $this->assertInstanceOf('Learnosity\Entities\Item', $item);
    }
} 
