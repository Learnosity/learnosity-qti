<?php


namespace Learnosity\Tests\Unit\Processors\QtiV2\In\Interactions;

use Learnosity\Services\LogService;

abstract class AbstractInteractionTest extends \PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        LogService::flush();
    }
}
