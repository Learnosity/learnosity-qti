<?php

namespace Learnosity\Tests\Integration\Mappers\QtiV2\MergedInteractions;

use Learnosity\Mappers\QtiV2\Import\ItemMapper;
use Learnosity\Utils\ArrayUtil;
use Learnosity\Utils\FileSystemUtil;

class MergedTextEntryInteractionTest extends \PHPUnit_Framework_TestCase
{

    private $file;

    public function setup()
    {
        $this->file = FileSystemUtil::readFile(FileSystemUtil::getRootPath() .
            '/src/Tests/Fixtures/interactions/textentryinteraction.xml');
    }

    public function testMergedTextInteraction()
    {
        $mapper = new ItemMapper();
        list($item, $questions) = $mapper->parse($this->file->getContents());

        /** @var \Learnosity\Entities\Item $item */
        $this->assertInstanceOf('Learnosity\Entities\Item', $item);
        $this->assertTrue($item->get_reference() === 'res_AA-FIB_B13_CH1_geoc_f1f1');
        $this->assertTrue($item->get_status() === 'published');

        $this->assertTrue(count($item->get_questionReferences()) === 1);
        $this->assertTrue(substr_count($item->get_content(),
                '<span class="learnosity-response question-' . $item->get_questionReferences()[0] . '"></span>') === 1);
        $this->assertTrue(count($questions) === 1);
        $q = $questions[$item->get_questionReferences()[0]];
        $this->assertInstanceOf('\Learnosity\Entities\Question', $q);

        /* @var $q \Learnosity\Entities\Question */
        $this->assertTrue($q->get_type() === 'clozetext');

        /* @var $questionType \Learnosity\Entities\QuestionTypes\clozetext */
        $questionType = $q->get_data();
        $this->assertInstanceOf('\Learnosity\Entities\QuestionTypes\clozetext', $questionType);
        $this->assertEmpty($questionType->get_stimulus());
        $this->assertTrue($questionType->get_type() === 'clozetext');
        $this->assertTrue(substr_count($questionType->get_template(), '{{response}}') === 2);

        /* @var $validation \Learnosity\Entities\QuestionTypes\clozetext_validation */
        $validation = $questionType->get_validation();
        $this->assertInstanceOf('\Learnosity\Entities\QuestionTypes\clozetext_validation', $validation);
        $this->assertEquals($validation->get_scoring_type(), 'exactMatch');

        /* @var $validResponse \Learnosity\Entities\QuestionTypes\clozetext_validation_valid_response */
        $validResponse = $validation->get_valid_response();
        $this->assertInstanceOf('\Learnosity\Entities\QuestionTypes\clozetext_validation_valid_response',
            $validResponse);
        $this->assertEquals(1, $validResponse->get_score());

        $options = [];
        $options[] = $validResponse->get_value();

        $altResponses = $validation->get_alt_responses();
        $this->assertTrue(count($altResponses) === 3);
        /* @var $altResponse \Learnosity\Entities\QuestionTypes\clozetext_validation_alt_responses_item */
        foreach ($altResponses as $altResponse) {
            $this->assertInstanceOf('\Learnosity\Entities\QuestionTypes\clozetext_validation_alt_responses_item',
                $altResponse);
            $this->assertEquals(1, $altResponse->get_score());
            $options[] = $altResponse->get_value();
        }

        $expectedOptions = ArrayUtil::mutateResponses([['a', 'b'], ['OHMYGOD', 'x7']]);
        $matchCount = 0;
        foreach($expectedOptions as $expectedKey=>$expectedValue) {
            foreach($options as $optionKey=>$optionValue) {
                $diff = array_diff($expectedValue, $optionValue);
                if(!$diff) {
                    $matchCount++;
                }
            }
        }
        $this->assertEquals($matchCount, count($options));
    }

}
