<?php

namespace Learnosity\Tests\Mappers\QtiV2\Out;

use Learnosity\Converter;
use Learnosity\Utils\FileSystemUtil;

class QuestionMapperTest extends \PHPUnit_Framework_TestCase
{
    public function testMappingMcqQuestion()
    {
        $questionJson = FileSystemUtil::readFile(FileSystemUtil::getRootPath() . '/src/Tests/Fixtures/learnosityjsons/item_mcq.json')->getContents();
        list($xmlString, $messages) = Converter::convertLearnosityToQtiItem($questionJson);
        $this->assertNotNull($xmlString);
    }
}
