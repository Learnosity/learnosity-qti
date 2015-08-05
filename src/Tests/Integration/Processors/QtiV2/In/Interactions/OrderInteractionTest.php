<?php
namespace Learnosity\Tests\Unit\Learnosity\Tests\Integration\Mappers\QtiV2\In\Interactions;

use Learnosity\AppContainer;
use Learnosity\Entities\Item\item;
use Learnosity\Entities\Question;
use Learnosity\Entities\QuestionTypes\orderlist;
use Learnosity\Processors\QtiV2\In\ItemMapper;
use Learnosity\Utils\FileSystemUtil;

class OrderInteractionTest extends \PHPUnit_Framework_TestCase
{

    private $file;
    /* @var $mapper ItemMapper */
    private $mapper;

    public function setup()
    {
        $this->file = FileSystemUtil::readFile(FileSystemUtil::getRootPath() .
            '/src/Tests/Fixtures/interactions/order.xml');
        $this->mapper = AppContainer::getApplicationContainer()->get('qtiv2_item_mapper');
    }

    public function testMappingMatchInteraction()
    {
        list($item, $questions) = $this->mapper->parse($this->file->getContents());

        /** @var item $item */
        $this->assertInstanceOf('Learnosity\Entities\Item\item', $item);
        $this->assertEquals('order', $item->get_reference());
        $this->assertEquals('<span class="learnosity-response question-order_RESPONSE"></span>', $item->get_content());
        $this->assertEquals('published', $item->get_status());
        $this->assertEquals(['order_RESPONSE'], $item->get_questionReferences());

        $this->assertCount(1, $questions);
        /** @var Question $q */
        $q = $questions[0];
        $this->assertInstanceOf('Learnosity\Entities\Question', $q);
        $this->assertEquals('order_RESPONSE', $q->get_reference());
        $this->assertEquals('orderlist', $q->get_type());

        /** @var orderlist $qType */
        $qType = $q->get_data();
        $this->assertInstanceOf('Learnosity\Entities\QuestionTypes\orderlist', $qType);
        $this->assertEquals('The following F1 drivers finished on the podium in the first ever Grand Prix of
				Bahrain. Can you rearrange them into the correct finishing order?',
            $qType->get_stimulus());
        $this->assertEquals('orderlist', $qType->get_type());
        $this->assertEquals(['Rubens Barrichello', 'Jenson Button', 'Michael Schumacher'], $qType->get_list());

        $validation = $qType->get_validation();
        $this->assertInstanceOf('Learnosity\Entities\QuestionTypes\orderlist_validation', $validation);
        $this->assertEquals('exactMatch', $validation->get_scoring_type());

        $validResponse = $validation->get_valid_response();
        $this->assertInstanceOf('Learnosity\Entities\QuestionTypes\orderlist_validation_valid_response',
            $validResponse);
        $this->assertEquals(1, $validResponse->get_score());
        $this->assertEquals([2, 0, 1], $validResponse->get_value());

        $this->assertNull($validation->get_alt_responses());
    }
}
