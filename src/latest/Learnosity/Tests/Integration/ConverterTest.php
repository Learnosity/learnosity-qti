<?php

namespace Learnosity\Tests\Integration;

use Learnosity\Converter;
use Learnosity\Tests\AbstractTest;

class ConverterTest extends AbstractTest
{
    public function testConvertInvalidQtiXmlToLearnosityJson()
    {
        $this->setExpectedException('Learnosity\Exceptions\MappingException');
        $xmlString = $this->getFixtureFileContents('otherqtis/invalid.xml');
        list($item, $questions, $manifest) = Converter::convertQtiItemToLearnosity($xmlString);
    }
}
