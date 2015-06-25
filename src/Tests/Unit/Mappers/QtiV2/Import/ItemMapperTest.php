<?php
namespace Learnosity\Tests\Unit\Mappers\QtiV2\Import;

use Learnosity\Entities\Item\item;
use Learnosity\Entities\QuestionTypes\clozetext;
use Learnosity\Mappers\QtiV2\Import\ItemMapper;
use Learnosity\Mappers\QtiV2\Import\ResponseProcessingTemplate;
use Learnosity\Tests\Unit\Mappers\QtiV2\Import\Fixtures\InlineChoiceInteractionBuilder;
use qtism\data\AssessmentItem;
use qtism\data\content\BlockCollection;
use qtism\data\content\InlineCollection;
use qtism\data\content\interactions\TextEntryInteraction;
use qtism\data\content\ItemBody;
use qtism\data\content\TextRun;
use qtism\data\content\xhtml\text\P;
use qtism\data\processing\ResponseProcessing;

class ItemMapperTest extends \PHPUnit_Framework_TestCase
{
    /* @var $itemMapper ItemMapper */
    private $itemMapper;
    private $mockXmlDocument;
    private $mockMapper;
    private $mockMergedItemBuilder;
    private $mockRegularItemBuilder;

    public function setup()
    {
        $this->mockXmlDocument = $this->getMockBuilder('qtism\data\storage\xml\XmlCompactDocument')
            ->disableOriginalConstructor()
            ->getMock();
        $this->mockXmlDocument->method('loadFromString')->willReturn(true);


        $this->mockMapper =
            $this->getMockBuilder('Learnosity\Mappers\QtiV2\Import\MergedInteractions\MergedInlineChoiceInteraction')
                ->disableOriginalConstructor()->getMock();

        $this->mockMergedItemBuilder = $this->getMockBuilder('Learnosity\Mappers\QtiV2\Import\MergedItemBuilder')
            ->disableOriginalConstructor()->getMock();

        $this->mockRegularItemBuilder = $this->getMockBuilder('Learnosity\Mappers\QtiV2\Import\RegularItemBuilder')
            ->disableOriginalConstructor()->getMock();

        $this->itemMapper =
            new ItemMapper($this->mockXmlDocument, $this->mockMergedItemBuilder, $this->mockRegularItemBuilder);
    }

    public function testParseWithoutInteraction()
    {
        $this->mockXmlDocument->method('getDocumentComponent')
            ->willReturn($this->buildAssessmentItemWithoutInteraction());
        $item = $this->itemMapper->parse('');
        $this->assertCount(1, $this->itemMapper->getExceptions());
        $this->assertNull($item);
    }

    public function testParseWithSameInteractionType()
    {
        $this->mockXmlDocument->method('getDocumentComponent')
            ->willReturn($this->buildAssessmentItemWithSameInteractionTypes());

        $testItem = $this->buildItem();
        $testQuestion = $this->buildQuestion();

        $this->mockMergedItemBuilder->expects($this->once())->method('getExceptions')->willReturn([]);
        $this->mockMergedItemBuilder->expects($this->once())->method('getItem')->willReturn($testItem);
        $this->mockMergedItemBuilder->expects($this->once())->method('getQuestions')->willReturn([$testQuestion]);
        $this->mockMergedItemBuilder->expects($this->once())->method('map')->willReturn(true);

        $data = $this->itemMapper->parse('');

        $this->assertcount(3, $data);

        $item = $data[0];
        $this->assertEquals($testItem, $item);

        $this->assertCount(1, $data[1]);
        $this->assertEquals([$testQuestion], $data[1]);
        $this->assertEmpty($data[2], 'Should have no exception');
    }

    public function testParseWithMultipleInteractionType()
    {

        $this->mockXmlDocument->method('getDocumentComponent')
            ->willReturn($this->buildAssessmentItemWithDifferentInteractionTypes());
        $testItem = $this->buildItem();
        $testQuestion = $this->buildQuestion();

        // item(s) cannot be merged
        $this->mockMergedItemBuilder->expects($this->once())->method('map')->willReturn(false);

        $this->mockRegularItemBuilder->expects($this->once())->method('getExceptions')->willReturn([]);
        $this->mockRegularItemBuilder->expects($this->once())->method('getItem')->willReturn($testItem);
        $this->mockRegularItemBuilder->expects($this->once())->method('getQuestions')->willReturn([$testQuestion]);
        $this->mockRegularItemBuilder->expects($this->once())->method('map')->willReturn(true);


        $data = $this->itemMapper->parse('');
        $this->assertcount(3, $data);

        $item = $data[0];
        $this->assertEquals($testItem, $item);

        $this->assertCount(1, $data[1]);
        $this->assertEquals([$testQuestion], $data[1]);
        $this->assertEmpty($data[2], 'Should have no exception');
    }

    protected function buildItem()
    {
        return new item('testItemID', ['testQuestionReference'], 'testContent');
    }

    protected function buildQuestion()
    {
        return new clozetext('testQuestionType', '');
    }

    protected function buildAssessmentItemWithoutInteraction()
    {
        return $this->buildAssessmentItem([]);
    }

    protected function buildAssessmentItemWithSameInteractionTypes()
    {
        $interactionOne = new TextEntryInteraction('testInteractionOne');
        $interactionTwo = new TextEntryInteraction('testInteractionTwo');
        return $this->buildAssessmentItem([$interactionOne, $interactionTwo], ResponseProcessingTemplate::MAP_RESPONSE);
    }

    protected function buildAssessmentItemWithDifferentInteractionTypes()
    {
        $interactionOne = new TextEntryInteraction('testInteractionOne');
        $interactionTwo = InlineChoiceInteractionBuilder::buildSimple('testInteractionTwo', [
            'Choice1' => 'A'
        ]);
        return $this->buildAssessmentItem([$interactionOne, $interactionTwo], ResponseProcessingTemplate::MAP_RESPONSE);
    }

    protected function buildAssessmentItem(array $interactions, $responseProcessingTemplate = '')
    {

        $assessmentItem = new AssessmentItem('testItemID', 'testItemTitle', false);

        $responseProcessing = new ResponseProcessing();
        $responseProcessing->setTemplate($responseProcessingTemplate);
        $assessmentItem->setResponseProcessing($responseProcessing);

        $itemBody = new ItemBody();
        $p = new P();
        $pCollection = new InlineCollection();
        $pCollection->attach(new TextRun('The Matrix movie is starring '));
        $pCollection->attach(new TextRun('.'));

        foreach ($interactions as $interaction) {
            $pCollection->attach($interaction);
        }

        $p->setContent($pCollection);

        $collection = new BlockCollection();
        $collection->attach($p);
        $itemBody->setContent($collection);
        $assessmentItem->setItemBody($itemBody);

        return $assessmentItem;
    }

}
