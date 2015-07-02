<?php

namespace Learnosity\Tests;

use Learnosity\Utils\ArrayUtil;
use PHPUnit_Framework_TestCase;

class ArrayUtilTest extends PHPUnit_Framework_TestCase
{
    public function testCombination()
    {
        $res = ArrayUtil::combinations(['A', 'B', 'C', 'D']);
        $this->assertCount(16, $res);
    }

    public function testMutateEmptyArray()
    {
        $res = ArrayUtil::mutateResponses([]);
        $this->assertEquals($res, []);
    }

    public function testMutateResponsesBase()
    {
        $res = ArrayUtil::mutateResponses([['a', 'b']]);
        $this->assertTrue(count($res) === 2);
        $this->assertEquals($res[0], 'a');
        $this->assertEquals($res[1], 'b');
    }

    public function testMutateResponsesMultiple()
    {
        $res = ArrayUtil::mutateResponses([['a', 'b'], ['c', 'd']]);
        $this->assertTrue(count($res) === 4);
        $this->assertEquals($res[0], ['a', 'c']);
        $this->assertEquals($res[1], ['a', 'd']);
        $this->assertEquals($res[2], ['b', 'c']);
        $this->assertEquals($res[3], ['b', 'd']);
    }

    public function testMutateResponsesMultipleUnEven()
    {
        $res = ArrayUtil::mutateResponses([['a', 'b', 'f'], ['c', 'd']]);
        $this->assertTrue(count($res) === 6);
        $this->assertEquals($res[0], ['a', 'c']);
        $this->assertEquals($res[1], ['a', 'd']);
        $this->assertEquals($res[2], ['b', 'c']);
        $this->assertEquals($res[3], ['b', 'd']);
        $this->assertEquals($res[4], ['f', 'c']);
        $this->assertEquals($res[5], ['f', 'd']);
    }

    public function testMutateResponsesWithSameValue()
    {
        $res = ArrayUtil::mutateResponses([
            [[['a' => 1]], [['b' => 2]]],
            [[['a' => 3]], [['c' => 4]]]
        ]);
        $this->assertTrue(count($res) === 4);
        $this->assertEquals($res[0], [['a' => 1], ['a' => 3]]);
        $this->assertEquals($res[1], [['a' => 1], ['c' => 4]]);
        $this->assertEquals($res[2], [['b' => 2], ['a' => 3]]);
        $this->assertEquals($res[3], [['b' => 2], ['c' => 4]]);
    }

    public function testArrayKeysMulti()
    {
        $res = ArrayUtil::arrayKeysMulti(
            [
                ['a' => 1],
                ['b' => 2]
            ]
        );
        $this->assertEquals(['a', 'b'], $res);
    }

    public function testArrayValsMulti()
    {
        $res = ArrayUtil::arrayValsMulti(
            [
                ['a' => 1],
                ['b' => 2]
            ]
        );
        $this->assertEquals([1, 2], $res);
    }
}
