<?php

namespace LearnosityQti\Tests\Integration;

use LearnosityQti\Converter;
use LearnosityQti\Tests\AbstractTest;

class ConverterTest extends AbstractTest
{
    public function testConvertInvalidQtiXmlToLearnosityJson()
    {
        $this->setExpectedException('LearnosityQti\Exceptions\InvalidQtiException');
        $xmlString = $this->getFixtureFileContents('otherqtis/invalid.xml');
        list($item, $questions, $manifest) = Converter::convertQtiItemToLearnosity($xmlString);
    }

    public function testConvertRegularQtiXmlToLearnosityJson()
    {
        $xmlString = $this->getFixtureFileContents('otherqtis/test.xml');
        list($item, $questions, $manifest) = Converter::convertQtiItemToLearnosity($xmlString);
    }

    public function testConvertRegularQtiXmlWithCdataToLearnosityJson()
    {
        $xmlString = $this->getFixtureFileContents('otherqtis/withcdata.xml');
        list($item, $questions, $manifest) = Converter::convertQtiItemToLearnosity($xmlString);
    }

    public function testConvertRegularManifestXmlToLearnosityJson()
    {
        $xmlString = $this->getFixtureFileContents('imscp/samplecp/imsmanifest.xml');
        list($activities, $activitiesTags, $itemsTags) = Converter::convertQtiManifestToLearnosity($xmlString);
    }
}
