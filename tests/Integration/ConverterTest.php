<?php

namespace Learnosity\Tests\Integration;

use Learnosity\Converter;
use Learnosity\Tests\AbstractTest;

class ConverterTest extends AbstractTest
{
    public function testConvertInvalidQtiXmlToLearnosityJson()
    {
        $this->setExpectedException('Learnosity\Exceptions\InvalidQtiException');
        $xmlString = $this->getFixtureFileContents('otherqtis/invalid.xml');
        list($item, $questions, $manifest) = Converter::convertQtiItemToLearnosity($xmlString);
    }

    public function testConvertRegularQtiXmlToLearnosityJson()
    {
        $xmlString = $this->getFixtureFileContents('otherqtis/test.xml');
        list($item, $questions, $manifest) = Converter::convertQtiItemToLearnosity($xmlString);
    }
}
