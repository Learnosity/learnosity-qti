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
}
