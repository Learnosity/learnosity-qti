<?php

namespace Learnosity\Tests\Mappers\QtiV2;

use Learnosity\Processors\Learnosity\In\ItemMapper;
use Learnosity\Processors\Learnosity\In\QuestionMapper;
use Learnosity\Processors\QtiV2\Export\ItemWriter;
use Learnosity\Utils\FileSystemUtil;

class ItemWriterTest extends \PHPUnit_Framework_TestCase
{
    public function testWriteXML()
    {
        $this->markTestSkipped('Need to be implemented');

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
