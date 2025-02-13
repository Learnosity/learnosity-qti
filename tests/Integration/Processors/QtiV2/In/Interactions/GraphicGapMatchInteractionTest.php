<?php


namespace LearnosityQti\Tests\Integration\Processors\QtiV2\In\Interactions;

use LearnosityQti\AppContainer;
use LearnosityQti\Entities\Item\item;
use LearnosityQti\Entities\QuestionTypes\imageclozeassociation;
use LearnosityQti\Entities\QuestionTypes\imageclozeassociation_image;
use LearnosityQti\Tests\AbstractTest;

class GraphicGapMatchInteractionTest extends AbstractTest
{
    public function testMapResponseBasic()
    {
        $mapper = AppContainer::getApplicationContainer()->get('qtiv2_item_mapper');
        list($item, $questions, $exceptions) = $mapper->parse($this->getFixtureFileContents('interactions/graphic_gap_match.xml'));

        /** @var item $item */
        $this->assertNotNull($item);
        $this->assertInstanceOf('LearnosityQti\Entities\Item\item', $item);
        $this->assertEquals('graphicGapfill', $item->get_reference());
        $this->assertEquals('<span class="learnosity-response question-graphicGapfill_RESPONSE"></span>', $item->get_content());
        $this->assertEquals('published', $item->get_status());
        $this->assertEquals('Airport Tags', $item->get_description());
        $this->assertEquals(['graphicGapfill_RESPONSE'], $item->get_questionReferences());

        $this->assertCount(1, $questions);
        $this->assertContains('<p>Test intro</p>', $questions[0]->get_data()->get_stimulus());
        $this->assertEquals('graphicGapfill_RESPONSE', $questions[0]->get_reference());
        $this->assertEquals('imageclozeassociationV2', $questions[0]->get_type());
        /** @var imageclozeassociation $question */
        $question = $questions[0]->get_data();
        $this->assertEquals([
            '<img src="images/CBG.png"/>',
            '<img src="images/EBG.png"/>',
            '<img src="images/EDI.png"/>',
            '<img src="images/GLA.png"/>',
            '<img src="images/MAN.png"/>',
            '<img src="images/MCH.png"/>'
        ], $question->get_possible_responses());
        $this->assertFalse($question->get_duplicate_responses());
        /** @var imageclozeassociation_image $img */
        $img = $question->get_image();
        $this->assertInstanceOf('LearnosityQti\Entities\QuestionTypes\imageclozeassociation_image', $img);
        $this->assertEquals('images/ukairtags.png', $img->get_src());
        $this->assertEquals(
            [
                ['x' => 5.83, 'y' => 38.57],
                ['x' => 62.14, 'y' => 36.79],
                ['x' => 32.04, 'y' => 58.93],
            ],
            $question->get_response_positions()
        );

        $validation = $question->get_validation();
        $this->assertNotNull($validation);
        $this->assertEquals('exactMatch', $validation->get_scoring_type());
        $this->assertEquals(3, $validation->get_valid_response()->get_score());
        $this->assertEquals(['<img src="images/GLA.png"/>', '<img src="images/EDI.png"/>', '<img src="images/MAN.png"/>'], $validation->get_valid_response()->get_value());

        $this->assertNull($validation->get_alt_responses());
    }
}
