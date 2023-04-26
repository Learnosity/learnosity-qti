<?php

namespace LearnosityQti\Tests\Integration\Processors\QtiV2\In\Interactions;

use LearnosityQti\AppContainer;
use LearnosityQti\Entities\Item\item;
use LearnosityQti\Entities\Question;
use LearnosityQti\Entities\QuestionTypes\choicematrix;
use LearnosityQti\Processors\QtiV2\In\ItemMapper;
use LearnosityQti\Tests\AbstractTest;

class MatchInteractionTest extends AbstractTest
{
    private $file;
    /* @var $mapper ItemMapper */
    private $mapper;

    public function setup()
    {
        $this->file = $this->getFixtureFileContents('interactions/match.xml');
        $this->mapper = AppContainer::getApplicationContainer()->get('qtiv2_item_mapper');
    }

    public function testMappingMatchInteraction()
    {
        list($item, $questions) = $this->mapper->parse($this->file);

        /** @var item $item */
        $this->assertInstanceOf('LearnosityQti\Entities\Item\item', $item);
        $this->assertEquals('match', $item->get_reference());
        $this->assertEquals('<span class="learnosity-response question-match_RESPONSE"></span>', $item->get_content());
        $this->assertEquals('published', $item->get_status());
        $this->assertCount(1, $item->get_questionReferences());
        $this->assertContains('match_RESPONSE', $item->get_questionReferences());

        $this->assertCount(1, $questions);

        /** @var Question $question */
        $question = $questions[0];
        $this->assertInstanceOf('LearnosityQti\Entities\Question', $question);
        $this->assertEquals('match_RESPONSE', $question->get_reference());
        $this->assertEquals('choicematrix', $question->get_type());

        /** @var choicematrix $choicematrix */
        $choicematrix = $question->get_data();
        $this->assertInstanceOf('LearnosityQti\Entities\QuestionTypes\choicematrix', $choicematrix);
        $this->assertEquals('Match the following characters to the Shakespeare play they appeared in:',
            $choicematrix->get_stimulus());
        $this->assertEquals('choicematrix', $choicematrix->get_type());
        $this->assertEquals(
            [
                'A Midsummer-Night\'s Dream',
                'Romeo and Juliet',
                'The
				Tempest'
            ],
            $choicematrix->get_options()
        );
        $this->assertEquals(
            [
                'Capulet',
                'Demetrius',
                'Lysander',
                'Prospero'
            ],
            $choicematrix->get_stems());
        $this->assertTrue($choicematrix->get_multiple_responses());

        $validation = $choicematrix->get_validation();
        $this->assertInstanceOf('LearnosityQti\Entities\QuestionTypes\choicematrix_validation', $validation);
        $this->assertEquals('exactMatch', $validation->get_scoring_type());

        $validResponse = $validation->get_valid_response();
        $this->assertInstanceOf('LearnosityQti\Entities\QuestionTypes\choicematrix_validation_valid_response',
            $validResponse);
        $this->assertEquals(3, $validResponse->get_score());
        $this->assertEquals([[1], [0], [0], [2]], $validResponse->get_value());

        $this->assertNull($validation->get_alt_responses());
    }
}
