<?php

namespace Learnosity\Tests;

use Learnosity\Processors\Learnosity\In\ValidationBuilder\ValidResponse;
use Learnosity\Utils\ArrayUtil;
use PHPUnit_Framework_TestCase;

class ArrayUtilTest extends PHPUnit_Framework_TestCase
{
    public function testCartesianEmptyArray()
    {
        $res = ArrayUtil::cartesianProduct([]);
        $this->assertEquals($res, []);
    }

    public function testCartesianResponsesBase()
    {
        $res = ArrayUtil::cartesianProduct([['a', 'b']]);
        $this->assertTrue(count($res) === 2);
        $this->assertEquals($res[0][0], 'a');
        $this->assertEquals($res[1][0], 'b');
    }

    public function testCartesianResponsesMultiple()
    {
        $res = ArrayUtil::cartesianProduct([['a', 'b'], ['a', 'd']]);
        $this->assertTrue(count($res) === 4);
        $this->assertEquals($res[0], ['a', 'a']);
        $this->assertEquals($res[1], ['a', 'd']);
        $this->assertEquals($res[2], ['b', 'a']);
        $this->assertEquals($res[3], ['b', 'd']);
    }

    public function testCartesianResponsesMultipleUnEven()
    {
        $res = ArrayUtil::cartesianProduct([['a', 'b', 'f'], ['c', 'd']]);
        $this->assertTrue(count($res) === 6);
        $this->assertEquals($res[0], ['a', 'c']);
        $this->assertEquals($res[1], ['a', 'd']);
        $this->assertEquals($res[2], ['b', 'c']);
        $this->assertEquals($res[3], ['b', 'd']);
        $this->assertEquals($res[4], ['f', 'c']);
        $this->assertEquals($res[5], ['f', 'd']);
    }

    public function testCartesianResponsesWithSameValue()
    {
        $res = ArrayUtil::cartesianProduct([
            [['a' => 1], ['b' => 2]],
            [['a' => 3], ['c' => 4]]
        ]);

        $this->assertTrue(count($res) === 4);
        $this->assertEquals($res[0], [['a' => 1], ['a' => 3]]);
        $this->assertEquals($res[1], [['a' => 1], ['c' => 4]]);
        $this->assertEquals($res[2], [['b' => 2], ['a' => 3]]);
        $this->assertEquals($res[3], [['b' => 2], ['c' => 4]]);
    }

    public function testCartesianResponse()
    {
        $res = ArrayUtil::cartesianProductForResponses([
            [new ValidResponse(1, ['a', 'b']), new ValidResponse(2, ['c', 'd'])],
            [new ValidResponse(3, ['a', 'e'])]
        ]);
        $this->assertCount(2, $res);
        $this->assertEquals(['a', 'b', 'a', 'e'], $res[0]->getValue());
        $this->assertEquals(4, $res[0]->getScore());
        $this->assertEquals(['c', 'd', 'a', 'e'], $res[1]->getValue());
        $this->assertEquals(5, $res[1]->getScore());
    }
}
