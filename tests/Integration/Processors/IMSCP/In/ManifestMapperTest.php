<?php

namespace LearnosityQti\Tests\Integration\Processors\IMSCP\In;

use LearnosityQti\AppContainer;
use LearnosityQti\Processors\IMSCP\In\ManifestMapper;
use LearnosityQti\Tests\AbstractTest;

class ManifestMapperTest extends AbstractTest
{
    public function testParsingRegularManifest()
    {
        /** @var ManifestMapper $manifestMapper */
        $manifestXml = $this->getFixtureFileContents('otherqtis/imsmanifest.xml');
        $manifestMapper  = AppContainer::getApplicationContainer()->get('imscp_manifest_mapper');
        $result = $manifestMapper->parse($manifestXml);
    }
}
