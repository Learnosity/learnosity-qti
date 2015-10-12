<?php

namespace LearnosityQti\Tests;

use LearnosityQti\Utils\FileSystemUtil;

abstract class AbstractTest extends \PHPUnit_Framework_TestCase
{
    protected function getFixtureFileContents($filepath)
    {
        $fixturePath = FileSystemUtil::getTestFixturesPath();
        return FileSystemUtil::readFile($fixturePath . '/' . $filepath)->getContents();
    }
}
