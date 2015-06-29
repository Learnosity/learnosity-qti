<?php

namespace Learnosity\Test\Mappers\QtiV2\Import\Interactions;

use Learnosity\AppContainer;
use Learnosity\Entities\Item\item;
use Learnosity\Entities\Question;
use Learnosity\Entities\QuestionTypes\longtext;
use Learnosity\Mappers\QtiV2\Import\ItemMapper;
use Learnosity\Utils\FileSystemUtil;

class ExtendedTextEntryInteractionTest extends \PHPUnit_Framework_TestCase
{
    private $file;
    /* @var $mapper ItemMapper */
    private $mapper;

    public function setup()
    {
        $this->file = FileSystemUtil::readFile(FileSystemUtil::getRootPath() .
            '/src/Tests/Fixtures/interactions/extendedtext.xml');
        $this->mapper = AppContainer::getApplicationContainer()->get('qtiv2_item_mapper');
    }

    public function testSimpleCase()
    {
        $data = $this->mapper->parse($this->file->getContents());
        $this->assertCount(3, $data);
        $this->assertCount(1, $data[1]);
        /** @var item $item */
        $item = $data[0];
        $this->assertInstanceOf('Learnosity\Entities\Item\item', $item);
        $this->assertEquals('extendedText', $item->get_reference());
        $this->assertEquals('published', $item->get_status());
        $this->assertEquals('Writing a Postcard', $item->get_description());
        $this->assertCount(1, $item->get_questionReferences());
        $this->assertContains('<span class="learnosity-response question-extendedText_RESPONSE"></span>',
            $item->get_content());
        $this->assertContains('extendedText_RESPONSE', $item->get_questionReferences());

        /** @var Question $question */
        $question = $data[1][0];
        $this->assertInstanceOf('Learnosity\Entities\Question', $question);
        $this->assertEquals('extendedText_RESPONSE', $question->get_reference());
        $this->assertEquals('longtext', $question->get_type());

        /** @var longtext $q */
        $q = $question->get_data();
        $this->assertInstanceOf('Learnosity\Entities\QuestionTypes\longtext', $q);
        $this->assertEquals('longtext', $q->get_type());
        $this->assertEquals('Write Sam a postcard. Answer the questions. Write 25-35 words.', $q->get_stimulus());
        $this->assertEquals(200, $q->get_max_length());
        $this->assertTrue($q->get_submit_over_limit());

        $this->assertNull($q->get_validation());

        $this->assertCount(2, $data[2]);
    }
} 
