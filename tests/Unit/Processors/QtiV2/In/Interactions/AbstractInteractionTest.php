<?php


namespace LearnosityQti\Tests\Unit\Processors\QtiV2\In\Interactions;

use LearnosityQti\Services\LogService;
use PHPUnit\Framework\TestCase;

abstract class AbstractInteractionTest extends TestCase
{
    public function tearDown()
    {
        LogService::flush();
    }
}
