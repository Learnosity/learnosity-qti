<?php

namespace Learnosity\Tests\Integration\Mappers\QtiV2\Interactions;


use Learnosity\AppContainer;
use Learnosity\Entities\Item\item;
use Learnosity\Entities\Question;
use Learnosity\Entities\QuestionTypes\choicematrix;
use Learnosity\Utils\FileSystemUtil;

class MatchInteractionTest extends \PHPUnit_Framework_TestCase
{


    private $file;
    /* @var $mapper ItemMapper */
    private $mapper;

    public function setup()
    {
        $this->file = FileSystemUtil::readFile(FileSystemUtil::getRootPath() .
            '/src/Tests/Fixtures/interactions/match.xml');
        $this->mapper = AppContainer::getApplicationContainer()->get('qtiv2_item_mapper');
    }

    public function testMappingMatchInteraction()
    {
        list($item, $questions) = $this->mapper->parse($this->file->getContents());

        /** @var item $item */
        $this->assertInstanceOf('Learnosity\Entities\Item\item', $item);
        $this->assertEquals('match', $item->get_reference());
        $this->assertEquals('<span class="learnosity-response question-match_RESPONSE"></span>', $item->get_content());
        $this->assertEquals('published', $item->get_status());
        $this->assertCount(1, $item->get_questionReferences());
        $this->assertContains('match_RESPONSE', $item->get_questionReferences());

        $this->assertCount(1, $questions);

        /** @var Question $q */
        $q = $questions[0];
        $this->assertInstanceOf('Learnosity\Entities\Question', $q);
        $this->assertEquals('match_RESPONSE', $q->get_reference());
        $this->assertEquals('choicematrix', $q->get_type());


        /** @var choicematrix $qType */
        $qType = $q->get_data();
        $this->assertInstanceOf('Learnosity\Entities\QuestionTypes\choicematrix', $qType);
        $this->assertEquals('Match the following characters to the Shakespeare play they appeared in:',
            $qType->get_stimulus());
        $this->assertEquals('choicematrix', $qType->get_type());
        $this->assertEquals(
            [
                'A Midsummer-Night\'s Dream',
                'Romeo and Juliet',
                'The
				Tempest'
            ],
            $qType->get_options()
        );
        $this->assertEquals(
            [
                'Capulet',
                'Demetrius',
                'Lysander',
                'Prospero'
            ],
            $qType->get_stems());
        $this->assertFalse($qType->get_multiple_responses());

        $validation = $qType->get_validation();
        $this->assertInstanceOf('Learnosity\Entities\QuestionTypes\choicematrix_validation', $validation);
        $this->assertEquals('exactMatch', $validation->get_scoring_type());

        $validResponse = $validation->get_valid_response();
        $this->assertInstanceOf('Learnosity\Entities\QuestionTypes\choicematrix_validation_valid_response',
            $validResponse);
        $this->assertEquals(3, $validResponse->get_score());
        $this->assertEquals([[1], [0], [0], [2]], $validResponse->get_value());

        $this->assertNull($validation->get_alt_responses());
    }
}
