<?php

namespace LearnosityQti\Tests\Integration;

use LearnosityQti\Converter;
use LearnosityQti\Exceptions\InvalidQtiException;
use LearnosityQti\Exceptions\MappingException;
use LearnosityQti\Tests\AbstractTest;
use qtism\data\storage\xml\XmlStorageException;

class ConverterTest extends AbstractTest
{
    /**
     * @throws MappingException
     * @throws XmlStorageException
     * @throws InvalidQtiException
     */
    public function testConvertInvalidQtiXmlToLearnosityJson()
    {
        $this->setExpectedException('LearnosityQti\Exceptions\InvalidQtiException');
        $xmlString = $this->getFixtureFileContents('otherqtis/invalid.xml');
        list($item, $questions, $manifest) = Converter::convertQtiItemToLearnosity($xmlString);
    }

    /**
     * @throws XmlStorageException
     * @throws MappingException
     * @throws InvalidQtiException
     */
    public function testConvertRegularQtiXmlToLearnosityJson()
    {
        $xmlString = $this->getFixtureFileContents('otherqtis/test.xml');
        list($item, $questions, $manifest) = Converter::convertQtiItemToLearnosity($xmlString);
    }

    /**
     * @throws XmlStorageException
     * @throws MappingException
     * @throws InvalidQtiException
     */
    public function testConvertRegularQtiTestXmlToLearnosityJson()
    {
        $xmlString = $this->getFixtureFileContents('tests/rtest01.xml');
        list($activity, $manifest) = Converter::convertQtiTestToLearnosity($xmlString);
    }

    /**
     * @throws XmlStorageException
     * @throws MappingException
     * @throws InvalidQtiException
     */
    public function testConvertRegularQtiXmlWithCdataToLearnosityJson()
    {
        $xmlString = $this->getFixtureFileContents('otherqtis/withcdata.xml');
        list($item, $questions, $manifest) = Converter::convertQtiItemToLearnosity($xmlString);
    }

    /**
     * @throws MappingException
     */
    public function testConvertRegularManifestXmlToLearnosityJson()
    {
        $xmlString = $this->getFixtureFileContents('imscp/samplecp/imsmanifest.xml');
        list($activities, $activitiesTags, $itemsTags) = Converter::convertQtiManifestToLearnosity($xmlString);
    }
}
