<?php

namespace Learnosity\Tests\Integration\Mappers\QtiV2;

use Learnosity\Mappers\QtiV2\Import\TestMapper;
use Learnosity\Utils\FileSystemUtil;

class TestMapperTest extends \PHPUnit_Framework_TestCase
{

    public function testParseActivity()
    {
        $file = FileSystemUtil::readFile('/Users/frankan/workspace/learnosity-qti/src/Tests/Fixtures/HMH_package_1/testitems/-FGM_FL15E_CAR_GALBT1_000.xml');
        $mapper = new TestMapper();
        $mapper->parse($file->getContents());
    }
}
