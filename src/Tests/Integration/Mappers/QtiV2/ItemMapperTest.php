<?php

namespace Learnosity\Tests\Mappers\QtiV2;

use Learnosity\AppContainer;
use Learnosity\Entities\Item\item;
use Learnosity\Entities\QuestionTypes\mcq;
use Learnosity\Utils\FileSystemUtil;
use PHPUnit_Framework_TestCase;

class ItemMapperTest extends PHPUnit_Framework_TestCase
{
    private $fixturesDirectory;

    public function setup()
    {
        $this->fixturesDirectory = FileSystemUtil::getRootPath() . '/src/Tests/Fixtures/';
    }

    public function testParsingWithMathML()
    {
        $xml = FileSystemUtil::readFile($this->fixturesDirectory . 'interactions/math.xml');
        $itemMapper = AppContainer::getApplicationContainer()->get('qtiv2_item_mapper');
        list($item, $questions, $exceptions) = $itemMapper->parse($xml->getContents());

        $this->assertTrue($item instanceof item);

        /** @var mcq $question */
        $question = $questions[0]->get_data();
        $this->assertTrue($question instanceof mcq);

        $this->assertTrue($question->get_is_math());
        $this->assertContains('<math>', $question->get_stimulus());
    }
}
