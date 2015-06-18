<?php

namespace Learnosity\Tests\Integration\Mappers\QtiV2\Interactions;

use Learnosity\Mappers\QtiV2\Import\ItemMapper;
use Learnosity\Utils\FileSystemUtil;

class ChoiceInteractionTest extends \PHPUnit_Framework_TestCase
{
    private $file;

    public function setup()
    {
        $this->file = FileSystemUtil::readFile(FileSystemUtil::getRootPath() . '/src/Tests/Fixtures/choices.xml');
    }

    public function testSimpleCase()
    {
        $mapper = new ItemMapper();
        list($item, $questions) = $mapper->parse($this->file->getContents());
        /* @var $item \Learnosity\Entities\Item */
        $this->assertInstanceOf('Learnosity\Entities\Item', $item);
        $this->assertTrue($item->get_reference() === 'XX663445');
        $this->assertTrue($item->get_status() === 'published');
        $this->assertTrue($item->get_description() === '663445');
        $this->assertTrue(count($item->get_questionReferences()) === 1);
        $this->assertTrue(substr_count($item->get_content(),
                '<span class="learnosity-response question-' . $item->get_questionReferences()[0] . '"></span>') === 1);
        $this->assertTrue(count($questions) === 1);
        $q = $questions[$item->get_questionReferences()[0]];
        $this->assertInstanceOf('\Learnosity\Entities\Question', $q);

        /* @var $q \Learnosity\Entities\Question */
        $this->assertTrue($q->get_type() === 'mcq');
        $this->assertTrue($q->get_reference() === 'XX663445_RESPONSE1');

        /* @var $questionType \Learnosity\Entities\QuestionTypes\mcq */
        $questionType = $q->get_data();
        $this->assertInstanceOf('\Learnosity\Entities\QuestionTypes\mcq', $questionType);
        $this->assertNotEmpty($questionType->get_stimulus());
        $this->assertTrue($questionType->get_type() === 'mcq');
        $options = $questionType->get_options();
        $this->assertTrue(count($options) === 4);

        foreach ($options as $o) {
            $this->assertTrue($o['label'] === strtoupper($o['value']));
        }

        /* @var $validation \Learnosity\Entities\QuestionTypes\mcq_validation */
        $validation = $questionType->get_validation();
        $this->assertInstanceOf('\Learnosity\Entities\QuestionTypes\mcq_validation', $validation);
        $this->assertTrue($validation->get_scoring_type() === 'exactMatch');

        /* @var $validResponse \Learnosity\Entities\QuestionTypes\mcq_validation_valid_response */
        $validResponse = $validation->get_valid_response();
        $this->assertTrue($validResponse->get_score() === 1);
        $this->assertTrue($validResponse->get_value()[0] === 'a2');
        $this->assertTrue($validResponse->get_value()[1] === 'a3');

    }

} 
