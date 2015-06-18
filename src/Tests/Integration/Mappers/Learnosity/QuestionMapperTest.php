<?php

namespace Learnosity\Tests\Mappers\Learnosity;

use Learnosity\Mappers\Learnosity\Import\QuestionMapper;
use Learnosity\Utils\FileSystemUtil;

class QuestionMapperTest extends \PHPUnit_Framework_TestCase
{
    public function testMappingMcqQuestion()
    {
        $questionJson = FileSystemUtil::readJsonContent(FileSystemUtil::getRootPath() . '/src/Tests/Fixtures/mcqquestion.json');
        $questionMapper = new QuestionMapper();
        $question = $questionMapper->parse($questionJson);
        $this->assertInstanceOf('Learnosity\Entities\Question', $question);
    }

    public function testMappingAssociationQuestion()
    {
        $questionJson = FileSystemUtil::readJsonContent(FileSystemUtil::getRootPath() . '/src/Tests/Fixtures/associationquestion.json');
        $questionMapper = new QuestionMapper();
        $question = $questionMapper->parse($questionJson);
        $this->assertInstanceOf('Learnosity\Entities\Question', $question);
    }

    public function testMappingAudioFeature()
    {
        $questionJson = FileSystemUtil::readJsonContent(FileSystemUtil::getRootPath() . '/src/Tests/Fixtures/audiofeature.json');
        $questionMapper = new QuestionMapper();
        $question = $questionMapper->parse($questionJson);
        $this->assertInstanceOf('Learnosity\Entities\Question', $question);
    }
} 
