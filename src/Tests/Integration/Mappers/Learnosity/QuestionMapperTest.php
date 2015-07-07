<?php

namespace Learnosity\Tests\Mappers\Learnosity;

use Learnosity\Processors\Learnosity\In\QuestionMapper;
use Learnosity\Utils\FileSystemUtil;

class QuestionMapperTest extends \PHPUnit_Framework_TestCase
{
    public function testMappingMcqQuestion()
    {
        $this->markTestSkipped('Need to be implemented');

        $questionJson = FileSystemUtil::readJsonContent(FileSystemUtil::getRootPath() . '/src/Tests/Fixtures/mcqquestion.json');
        $questionMapper = new QuestionMapper();
        $question = $questionMapper->parse($questionJson);
        $this->assertInstanceOf('Learnosity\Entities\Question', $question);
    }

    public function testMappingAssociationQuestion()
    {
        $this->markTestSkipped('Need to be implemented');

        $questionJson = FileSystemUtil::readJsonContent(FileSystemUtil::getRootPath() . '/src/Tests/Fixtures/associationquestion.json');
        $questionMapper = new QuestionMapper();
        $question = $questionMapper->parse($questionJson);
        $this->assertInstanceOf('Learnosity\Entities\Question', $question);
    }

    public function testMappingAudioFeature()
    {
        $this->markTestSkipped('Need to be implemented');

        $questionJson = FileSystemUtil::readJsonContent(FileSystemUtil::getRootPath() . '/src/Tests/Fixtures/audiofeature.json');
        $questionMapper = new QuestionMapper();
        $question = $questionMapper->parse($questionJson);
        $this->assertInstanceOf('Learnosity\Entities\Question', $question);
    }
} 
