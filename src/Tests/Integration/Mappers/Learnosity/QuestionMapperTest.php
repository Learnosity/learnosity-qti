<?php

namespace Learnosity\Tests\Mappers\Learnosity;

use Learnosity\Processors\Learnosity\In\QuestionMapper;
use Learnosity\Processors\QtiV2\Out\QuestionWriter;
use Learnosity\Utils\FileSystemUtil;

class QuestionMapperTest extends \PHPUnit_Framework_TestCase
{
    public function testMappingMcqQuestion()
    {
        $questionJson = FileSystemUtil::readJsonContent(FileSystemUtil::getRootPath() . '/src/Tests/Fixtures/learnosityjsons/mcqquestion.json');
        $questionMapper = new QuestionMapper();
        $question = $questionMapper->parse($questionJson);
        $this->assertInstanceOf('Learnosity\Entities\Question', $question);

        $questionWriter = new QuestionWriter();
        $xmlString = $questionWriter->convert($question);

        $this->assertNotNull($xmlString);
    }
}
