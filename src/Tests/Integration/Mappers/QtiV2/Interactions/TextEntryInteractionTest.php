<?php

namespace Learnosity\Test\Mappers\QtiV2\Import\Interactions;

use Learnosity\Mappers\QtiV2\Import\ItemMapper;
use Learnosity\Utils\FileSystemUtil;

class TextEntryInteractionTest extends \PHPUnit_Framework_TestCase
{
    public function testSimpleCase()
    {
        $xml = FileSystemUtil::readFile(FileSystemUtil::getRootPath() . '/src/Tests/Fixtures/interactions/textentryinteraction.xml');
        $mapper = new ItemMapper();
        list($item, $questions) = $mapper->parse($xml->getContents());

        $this->assertInstanceOf('Learnosity\Entities\Item', $item);
        $this->assertInstanceOf('Learnosity\Entities\Question', $questions[0]);
    }
}
