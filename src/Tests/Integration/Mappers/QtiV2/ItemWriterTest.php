<?php

namespace Learnosity\Tests\Mappers\QtiV2;

use Learnosity\Mappers\Learnosity\Import\ItemMapper;
use Learnosity\Mappers\Learnosity\Import\QuestionMapper;
use Learnosity\Mappers\QtiV2\Export\ItemWriter;
use Learnosity\Utils\FileSystemUtil;

class ItemWriterTest extends \PHPUnit_Framework_TestCase
{
    public function testWriteXML()
    {
        list ($item, $questions) = $this->getItemAndQuestionsForTesting();
        $itemWriter = new ItemWriter($item, $questions);
        $itemWriter->convert($item, $questions);
    }

    /**
     * @throws \Exception
     */
    private function getItemAndQuestionsForTesting()
    {
        $item = FileSystemUtil::readJsonContent(FileSystemUtil::getRootPath() . '/src/Tests/Fixtures/sampleitemmcq.json');
        $mcqQuestion = FileSystemUtil::readJsonContent(FileSystemUtil::getRootPath() . '/src/Tests/Fixtures/mcqquestion.json');
        $audioFeature = FileSystemUtil::readJsonContent(FileSystemUtil::getRootPath() . '/src/Tests/Fixtures/audiofeature.json');

        $itemMapper = new ItemMapper();
        $questionMapper = new QuestionMapper();
        return [$itemMapper->parse($item), [$questionMapper->parse($mcqQuestion), $questionMapper->parse($audioFeature)]];
    }
} 
