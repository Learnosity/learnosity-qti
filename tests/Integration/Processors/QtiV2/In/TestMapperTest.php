<?php

namespace LearnosityQti\Tests\Integration\Processors\QtiV2;

use Exception;
use LearnosityQti\AppContainer;
use LearnosityQti\Entities\Activity\activity;
use LearnosityQti\Tests\AbstractTest;

class TestMapperTest extends AbstractTest
{
    /**
     * @throws Exception
     */
    public function testParseWithSections()
    {
        $xml = $this->getFixtureFileContents('tests/rtest01.xml');
        $testMapper = AppContainer::getApplicationContainer()->get('qtiv2_test_mapper');
        list($activity, $manifest) = $testMapper->parse($xml);

        /** @var activity $activity */
        $this->assertTrue($activity instanceof activity);
        $this->assertCount(3, $activity->get_data()->get_items());
    }

    /**
     * @throws Exception
     */
    public function testParseWithSectionsTwo()
    {
        $xml = $this->getFixtureFileContents('tests/rtest02.xml');
        $testMapper = AppContainer::getApplicationContainer()->get('qtiv2_test_mapper');
        list($activity, $manifest) = $testMapper->parse($xml);

        /** @var activity $activity */
        $this->assertTrue($activity instanceof activity);
        $this->assertCount(9, $activity->get_data()->get_items());
    }

    /**
     * @throws Exception
     */
    public function testParseWithSectionsThree()
    {
        $xml = $this->getFixtureFileContents('tests/rtest03.xml');
        $testMapper = AppContainer::getApplicationContainer()->get('qtiv2_test_mapper');
        list($activity, $manifest) = $testMapper->parse($xml);

        /** @var activity $activity */
        $this->assertTrue($activity instanceof activity);
        $this->assertCount(9, $activity->get_data()->get_items());
    }
}
