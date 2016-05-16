<?php

namespace LearnosityQti\Tests\Integration\Processors\IMSCP\Out;

use LearnosityQti\AppContainer;
use LearnosityQti\Processors\IMSCP\In\ManifestMapper;
use LearnosityQti\Processors\IMSCP\Out\ManifestWriter;
use LearnosityQti\Tests\AbstractTest;

class ManifestWriterTest extends AbstractTest
{
    public function testManifestWriter()
    {
        /** @var ManifestMapper $manifestMapper */
        $manifestXml = $this->getFixtureFileContents('otherqtis/imsmanifest.xml');
        $manifestMapper  = AppContainer::getApplicationContainer()->get('imscp_manifest_mapper');
        $manifest = $manifestMapper->parse($manifestXml);

        $manifestWriter = new ManifestWriter();
        list($activity, $activityTags, $itemTags) = $manifestWriter->convert($manifest);
    }
}
