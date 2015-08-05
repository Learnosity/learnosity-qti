<?php

namespace Learnosity\Tests\Unit\Learnosity\Tests\Integration\Mappers\QtiV2\In\Interactions;

use Learnosity\AppContainer;
use Learnosity\Entities\QuestionTypes\tokenhighlight;
use Learnosity\Utils\FileSystemUtil;

class HottextInteractionTest extends \PHPUnit_Framework_TestCase
{
    private function getFixtureFile($filepath)
    {
        return FileSystemUtil::readFile(FileSystemUtil::getRootPath() . '/src/Tests/Fixtures/' . $filepath)->getContents();
    }

    public function testSimpleCaseFromQTIWebsite()
    {
        $mapper = AppContainer::getApplicationContainer()->get('qtiv2_item_mapper');
        list($item, $questions, $exceptions) = $mapper->parse($this->getFixtureFile('interactions/hottext.xml'));

        $this->assertInstanceOf('Learnosity\Entities\Item\item', $item);
        $this->assertEquals('IMS00004_StemError', $item->get_reference());
        $this->assertContains('<span class="learnosity-response question-IMS00004_StemError_RESPONSE"></span>', $item->get_content());
        $this->assertEquals('published', $item->get_status());
        $this->assertCount(1, $item->get_questionReferences());
        $this->assertContains('IMS00004_StemError_RESPONSE', $item->get_questionReferences());

        /** @var tokenhighlight $question */
        $question = $questions[0];
        $this->assertInstanceOf('Learnosity\Entities\Question', $question);
        $this->assertInstanceOf('Learnosity\Entities\QuestionTypes\tokenhighlight', $question->get_data());
        $this->assertEquals('tokenhighlight', $question->get_type());
        $this->assertEquals('custom', $question->get_data()->get_tokenization());

        $validation = $question->get_data()->get_validation();
        $this->assertNotNull($validation);
        $this->assertEquals('exactMatch', $validation->get_scoring_type());
        $this->assertNotNull($validation->get_valid_response());

        $this->assertEquals(1, $question->get_data()->get_max_selection());
    }
}
