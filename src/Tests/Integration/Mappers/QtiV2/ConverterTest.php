<?php

namespace Learnosity\Tests\Integration\Mappers\QtiV2;

use Learnosity\Converter;
use Learnosity\Utils\FileSystemUtil;

class ConverterTest extends \PHPUnit_Framework_TestCase
{

    public function testConvertQtiToJson()
    {
        $file = FileSystemUtil::readFile(__DIR__ . '/../../../Fixtures/choices.xml');
        list($item, $questions) = Converter::convertQtiItemToLearnosity($file->getContents());
        echo "Done!";
    }

}
