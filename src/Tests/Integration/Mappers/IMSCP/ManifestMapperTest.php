<?php
/**
 * Created by PhpStorm.
 * User: frankan
 * Date: 16/06/2015
 * Time: 2:00 PM
 */

namespace Learnosity\Tests\Integration\Mappers\IMSCP;


use Learnosity\Mappers\IMSCP\Import\ManifestMapper;
use Learnosity\Utils\FileSystemUtil;

class ManifestMapperTest extends \PHPUnit_Framework_TestCase
{
    public function testParseIMSCPManifest()
    {
        $this->markTestSkipped('Need to be implemented');

        $file = FileSystemUtil::readFile('/Users/frankan/workspace/learnosity-qti/src/Tests/Fixtures/HMH_package_1/imsmanifest.xml');
        $mapper = new ManifestMapper();
        $mapper->parse($file->getContents());
    }
}
