<?php

namespace Learnosity\Tests\Integration\Mappers\QtiV2\Interactions;

use Learnosity\Entities\Item;
use Learnosity\Mappers\QtiV2\Import\ItemMapper;
use Learnosity\Utils\FileSystemUtil;

class ChoiceInteractionTest extends \PHPUnit_Framework_TestCase
{
    private function getFixtureFile($filepath)
    {
        return FileSystemUtil::readFile(FileSystemUtil::getRootPath() . '/src/Tests/Fixtures/' . $filepath)->getContents();
    }

    public function testIntegrationSimpleChoiceInteraction()
    {
        $mapper = new ItemMapper();
        list($item, $questions) = $mapper->parse($this->getFixtureFile('interactions/choice.xml'));

        /** @var Item $item */
        $this->assertInstanceOf('Learnosity\Entities\Item', $item);
        $this->assertTrue($item->get_reference() === 'choice');
        $this->assertTrue($item->get_status() === 'published');

        $this->assertTrue(count($item->get_questionReferences()) === 1);
        $this->assertTrue(substr_count($item->get_content(),
                '<span class="learnosity-response question-' . $item->get_questionReferences()[0] . '"></span>') === 1);
        $this->assertTrue(count($questions) === 1);
        $q = $questions[$item->get_questionReferences()[0]];
        $this->assertInstanceOf('\Learnosity\Entities\Question', $q);

        /* @var $q \Learnosity\Entities\Question */
        $this->assertTrue($q->get_type() === 'mcq');
        $this->assertTrue($q->get_reference() === $item->get_reference() . '_' . 'RESPONSE');

        /* @var $questionType \Learnosity\Entities\QuestionTypes\mcq */
        $questionType = $q->get_data();
        $this->assertInstanceOf('\Learnosity\Entities\QuestionTypes\mcq', $questionType);
        $this->assertNotEmpty($questionType->get_stimulus());
        $this->assertTrue($questionType->get_type() === 'mcq');
        $options = $questionType->get_options();
        $this->assertCount(3, $options);

        $labels = array_column($options, 'label');
        $this->assertContains('You must stay with your luggage at all times.', $labels);
        $this->assertContains('Do not let someone else look after your luggage.', $labels);
        $this->assertContains('Remember your luggage when you leave.', $labels);

        $values = array_column($options, 'value');
        $this->assertContains('ChoiceA', $values);
        $this->assertContains('ChoiceB', $values);
        $this->assertContains('ChoiceC', $values);

        /* @var $validation \Learnosity\Entities\QuestionTypes\mcq_validation */
        $validation = $questionType->get_validation();
        $this->assertInstanceOf('\Learnosity\Entities\QuestionTypes\mcq_validation', $validation);
        $this->assertTrue($validation->get_scoring_type() === 'exactMatch');

        /* @var $validResponse \Learnosity\Entities\QuestionTypes\mcq_validation_valid_response */
        $validResponse = $validation->get_valid_response();
        $this->assertTrue($validResponse->get_score() === 1);
        $this->assertTrue($validResponse->get_value()[0] === 'ChoiceA');
    }
}
