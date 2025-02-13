<?php

namespace LearnosityQti\Tests\Integration\Processors\QtiV2\In\Interactions;

use LearnosityQti\AppContainer;
use LearnosityQti\Entities\QuestionTypes\clozeassociation;
use LearnosityQti\Tests\AbstractTest;

class GapMatchInteractionTest extends AbstractTest
{
    public function testMapResponseBasic()
    {
        $mapper = AppContainer::getApplicationContainer()->get('qtiv2_item_mapper');
        list($item, $questions, $exceptions) = $mapper->parse($this->getFixtureFileContents('interactions/gap_match.xml'));

        $this->assertNotNull($item);
        $this->assertInstanceOf('LearnosityQti\Entities\Item\item', $item);
        $this->assertEquals('gapMatch', $item->get_reference());

        $this->assertCount(1, $questions);
        $this->assertEquals('gapMatch_RESPONSE', $questions[0]->get_reference());
        /** @var clozeassociation $question */
        $question = $questions[0]->get_data();

        $validation = $question->get_validation();
        $this->assertNotNull($validation);
        $this->assertEquals('exactMatch', $validation->get_scoring_type());
        $this->assertEquals(3, $validation->get_valid_response()->get_score());
        $this->assertEquals(['winter', 'summer'], $validation->get_valid_response()->get_value());
        $this->assertCount(1, $exceptions);
    }

    public function testMapResponseWithDuplicatedMap()
    {
        $mapper = AppContainer::getApplicationContainer()->get('qtiv2_item_mapper');
        list($item, $questions, $exceptions) = $mapper->parse($this->getFixtureFileContents('interactions/gap_match_with_duplicated_map.xml'));

        $this->assertNotNull($item);
        $this->assertInstanceOf('LearnosityQti\Entities\Item\item', $item);
        $this->assertEquals('gapMatch', $item->get_reference());

        $this->assertCount(1, $questions);
        $this->assertEquals('gapMatch_RESPONSE', $questions[0]->get_reference());
        /** @var clozeassociation $question */
        $question = $questions[0]->get_data();

        $validation = $question->get_validation();
        $this->assertNotNull($validation);
        $this->assertEquals('exactMatch', $validation->get_scoring_type());
        $this->assertEquals(9, $validation->get_valid_response()->get_score());
        $this->assertEquals(['spring', 'winter'], $validation->get_valid_response()->get_value());

        $altResponses = $validation->get_alt_responses();
        $this->assertCount(5, $altResponses);
        $this->assertEquals(7, $altResponses[0]->get_score());
        $this->assertEquals(['summer', 'winter'], $altResponses[0]->get_value());
        $this->assertEquals(7, $altResponses[1]->get_score());
        $this->assertEquals(['spring', 'summer'], $altResponses[1]->get_value());
        $this->assertEquals(5, $altResponses[2]->get_score());
        $this->assertEquals(['winter', 'winter'], $altResponses[2]->get_value());
        $this->assertEquals(5, $altResponses[3]->get_score());
        $this->assertEquals(['summer', 'summer'], $altResponses[3]->get_value());
        $this->assertEquals(3, $altResponses[4]->get_score());
        $this->assertEquals(['winter', 'summer'], $altResponses[4]->get_value());
        $this->assertCount(1, $exceptions);
    }

    public function testMapResponseWithMixedImageAndTextObject()
    {
        $mapper = AppContainer::getApplicationContainer()->get('qtiv2_item_mapper');
        list($item, $questions, $exceptions) = $mapper->parse($this->getFixtureFileContents('interactions/gap_match_with_imageObject.xml'));

        $this->assertNotNull($item);
        $this->assertInstanceOf('LearnosityQti\Entities\Item\item', $item);
        $this->assertEquals('gapMatch', $item->get_reference());

        $this->assertCount(1, $questions);
        $this->assertEquals('gapMatch_RESPONSE', $questions[0]->get_reference());
        /** @var clozeassociation $question */
        $question = $questions[0]->get_data();

        $validation = $question->get_validation();
        $this->assertNotNull($validation);
        $this->assertEquals('exactMatch', $validation->get_scoring_type());
        $this->assertEquals(8, $validation->get_valid_response()->get_score());
        $this->assertEquals(['spring', 'spring'], $validation->get_valid_response()->get_value());

        $altResponses = $validation->get_alt_responses();
        $this->assertCount(5, $altResponses);
        $this->assertEquals(7, $altResponses[0]->get_score());
        $this->assertEquals(['autumn', 'spring'], $altResponses[0]->get_value());
        $this->assertEquals(7, $altResponses[1]->get_score());
        $this->assertEquals(['spring', '<img src="images/664892_p01_gi02.gif"/>'], $altResponses[1]->get_value());
        $this->assertEquals(6, $altResponses[2]->get_score());
        $this->assertEquals(['autumn', '<img src="images/664892_p01_gi02.gif"/>'], $altResponses[2]->get_value());
        $this->assertEquals(4, $altResponses[3]->get_score());
        $this->assertEquals(['<img src="images/664892_p01_gi01.gif"/>', 'spring'], $altResponses[3]->get_value());
        $this->assertEquals(3, $altResponses[4]->get_score());
        $this->assertEquals(['<img src="images/664892_p01_gi01.gif"/>', '<img src="images/664892_p01_gi02.gif"/>'], $altResponses[4]->get_value());
    }
}
