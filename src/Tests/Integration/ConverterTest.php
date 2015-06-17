<?php

namespace Learnosity\Tests\Integration;

use Learnosity\Converter;
use Learnosity\Utils\FileSystemUtil;

class ConverterTest extends \PHPUnit_Framework_TestCase
{

    public function testConvertQtiToJson()
    {
        $file = FileSystemUtil::readFile(__DIR__ . '/../Fixtures/choices.xml');
       // $file = FileSystemUtil::readFile('/Users/frankan/workspace/learnosity-qti/examples/SampleAssessmentItem/655308.xml');
        list($item, $questions) = Converter::convertQtiItemToLearnosity($file->getContents());
    }

    public function testParseIMSPackage() {

        $res = Converter::parseIMSCPPackage(__DIR__ . '/../Fixtures/HMH_package_1');
    }

}
