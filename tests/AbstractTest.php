<?php

namespace LearnosityQti\Tests;

use LearnosityQti\Utils\FileSystemUtil;
use PHPUnit\Framework\TestCase;

abstract class AbstractTest extends TestCase
{
    protected function getFixtureFileContents($filepath)
    {
        $fixturePath = FileSystemUtil::getTestFixturesPath();
        return FileSystemUtil::readFile($fixturePath . '/' . $filepath)->getContents();
    }
}
