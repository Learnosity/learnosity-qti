<?php

namespace LearnosityQti\Tests;

use Exception;
use LearnosityQti\Utils\FileSystemUtil;
use PHPUnit_Framework_TestCase;

abstract class AbstractTest extends PHPUnit_Framework_TestCase
{
    /**
     * @throws Exception
     */
    protected function getFixtureFileContents($filepath): string
    {
        $fixturePath = FileSystemUtil::getTestFixturesPath();
        return FileSystemUtil::readFile($fixturePath . '/' . $filepath)->getContents();
    }
}
