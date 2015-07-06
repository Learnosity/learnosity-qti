<?php


namespace Learnosity\Tests\Integration\Mappers\QtiV2\Interactions;


use Learnosity\AppContainer;
use Learnosity\Utils\FileSystemUtil;

class GraphicGapMatchInteractionTest extends \PHPUnit_Framework_TestCase {

    private function getFixtureFile($filepath)
    {
        return FileSystemUtil::readFile(FileSystemUtil::getRootPath() . '/src/Tests/Fixtures/' . $filepath)->getContents();
    }

    public function testMapResponseBasic()
    {
        $mapper = AppContainer::getApplicationContainer()->get('qtiv2_item_mapper');
        list($item, $questions, $exceptions) = $mapper->parse($this->getFixtureFile('interactions/graphic_gap_match.xml'));

//        $this->assertNotNull($item);
//        $this->assertInstanceOf('Learnosity\Entities\Item\item', $item);
//        $this->assertEquals('gapMatch', $item->get_reference());
//
//        $this->assertCount(1, $questions);
//        $this->assertEquals('gapMatch_RESPONSE', $questions[0]->get_reference());
//        /** @var clozeassociation $question */
//        $question = $questions[0]->get_data();
//
//        $validation = $question->get_validation();
//        $this->assertNotNull($validation);
//        $this->assertEquals('exactMatch', $validation->get_scoring_type());
//        $this->assertEquals(3, $validation->get_valid_response()->get_score());
//        $this->assertEquals(['winter', 'summer'], $validation->get_valid_response()->get_value());
//        $this->assertCount(1, $exceptions);
    }
}
