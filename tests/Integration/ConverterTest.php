<?php

namespace LearnosityQti\Tests\Integration;

use LearnosityQti\Converter;
use LearnosityQti\Tests\AbstractTest;
use LearnosityQti\Utils\FileSystemUtil;

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

    public function testConvertImcpDirectoryToLearnosityDirectory()
    {
        $qtiDirectory = FileSystemUtil::getTestFixturesPath() . '/imscp/Bien_Dit_Level_1_QTIv2-1cp_ABGUID__20150605';
        $learnosityDirectory = FileSystemUtil::getTestFixturesPath() . '/imscp/Bien_Dit_Level_1_QTIv2-1cp_ABGUID__20150605_Result';
        FileSystemUtil::createOrReplaceDir($learnosityDirectory);
        Converter::convertImscpDirectoryToLearnosityDirectory($qtiDirectory, $learnosityDirectory);
    }
}
