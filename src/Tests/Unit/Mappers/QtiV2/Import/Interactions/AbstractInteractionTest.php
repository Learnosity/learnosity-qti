<?php


namespace Learnosity\Tests\Unit\Mappers\QtiV2\Import\Interactions;


use Learnosity\Tests\Util\TestUtil;

class AbstractInteractionTest extends \PHPUnit_Framework_TestCase
{


    protected $mutateResponsesMethod;

    protected function setup()
    {
        $this->mutateResponsesMethod = TestUtil::getMethod('mutateResponses',
            'Learnosity\Mappers\QtiV2\Import\Interactions\TextEntryInteraction');
    }

    public function testMutateResponsesBase()
    {
        $res = $this->mutateResponsesMethod->invokeArgs($this->interaction, array([['a', 'b']]));
        $this->assertTrue(count($res) === 2);
        $this->assertEquals($res[0], 'a');
        $this->assertEquals($res[1], 'b');
    }

    public function testMutateResponsesMultiple()
    {
        $res = $this->mutateResponsesMethod->invokeArgs($this->interaction, array([['a', 'b'], ['c', 'd']]));
        $this->assertTrue(count($res) === 4);
        $this->assertEquals($res[0], ['a', 'c']);
        $this->assertEquals($res[1], ['a', 'd']);
        $this->assertEquals($res[2], ['b', 'c']);
        $this->assertEquals($res[3], ['b', 'd']);
    }

    public function testMutateResponsesMultipleUnEven()
    {
        $res = $this->mutateResponsesMethod->invokeArgs($this->interaction, array([['a', 'b', 'f'], ['c', 'd']]));
        $this->assertTrue(count($res) === 6);
        $this->assertEquals($res[0], ['a', 'c']);
        $this->assertEquals($res[1], ['a', 'd']);
        $this->assertEquals($res[2], ['b', 'c']);
        $this->assertEquals($res[3], ['b', 'd']);
        $this->assertEquals($res[4], ['f', 'c']);
        $this->assertEquals($res[5], ['f', 'd']);
    }
}
