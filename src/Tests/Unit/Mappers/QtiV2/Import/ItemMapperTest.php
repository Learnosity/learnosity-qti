<?php
namespace Learnosity\Tests\Unit\Mappers\QtiV2\Import;

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
    private $mockMapperFactory;
    private $mockMapper;

    public function setup()
    {
        $this->mockXmlDocument = $this->getMockBuilder('qtism\data\storage\xml\XmlCompactDocument')
            ->disableOriginalConstructor()
            ->getMock();
        $this->mockXmlDocument->method('loadFromString')->willReturn(true);

        $this->mockMapperFactory = $this->getMockBuilder('Learnosity\Mappers\QtiV2\Import\MapperFactory')
            ->disableOriginalConstructor()
            ->getMock();

        $this->mockMapper = $this
            ->getMockBuilder('Learnosity\Mappers\QtiV2\Import\MergedInteractions\MergedInlineChoiceInteraction')
            ->disableOriginalConstructor()->getMock();

        $this->mockMapperFactory->method('getMapper')->willReturn($this->mockMapper);

        $this->itemMapper = new ItemMapper($this->mockXmlDocument, $this->mockMapperFactory);
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

        $this->mockMapper->method('getQuestionType')->willReturn($this->buildQuestion());
        $this->mockMapper->method('getItemContent')->willReturn('testQuestionReference');
        $this->mockMapper->method('getExceptions')->willReturn([]);

        $data = $this->itemMapper->parse('');

        $this->assertcount(3, $data);

        $this->assertInstanceOf('Learnosity\Entities\Item\item', $data[0]);
        /* @var $item \Learnosity\Entities\Item\item */
        $item = $data[0];
        $this->assertEquals('testItemID', $item->get_reference());
        $this->assertEquals('published', $item->get_status());
        $this->assertEquals('testQuestionReference', $item->get_content());
        $this->assertNull($item->get_workflow());
        $this->assertNull($item->get_metadata());
        $this->assertEquals('testItemTitle', $item->get_description());
        $this->assertCount(1, $item->get_questionReferences());
        $this->assertEquals('testItemID_testInteractionOne_testInteractionTwo', $item->get_questionReferences()[0]);

        $this->assertCount(1, $data[1]);
        $this->assertInstanceOf('Learnosity\Entities\Question',
            $data[1]['testItemID_testInteractionOne_testInteractionTwo']);
        /* @var $question \Learnosity\Entities\Question */
        $question = $data[1]['testItemID_testInteractionOne_testInteractionTwo'];
        $this->assertEquals('testItemID_testInteractionOne_testInteractionTwo', $question->get_reference());
        $this->assertEquals('testQuestionType', $question->get_type());
        $this->assertNotNull($question->get_data());
        $this->assertEmpty($data[2], 'Should have no exception');
    }

    public function testParseWithMultipleInteractionType()
    {

        $this->mockXmlDocument->method('getDocumentComponent')
            ->willReturn($this->buildAssessmentItemWithDifferentInteractionTypes());
        $this->mockMapper->method('getQuestionType')->willReturn($this->buildQuestion());
        $this->mockMapper->method('getItemContent')->willReturn('testQuestionReference');
        $this->mockMapper->method('getExceptions')->willReturn([]);

        $data = $this->itemMapper->parse('');
        $this->assertcount(3, $data);
        $this->assertInstanceOf('Learnosity\Entities\Item\item', $data[0]);
        /* @var $item \Learnosity\Entities\Item\item */
        $item = $data[0];
        $this->assertEquals('testItemID', $item->get_reference());
        $this->assertEquals('published', $item->get_status());
        $this->assertEquals('<p>The Matrix movie is starring .<span class="learnosity-response question-testItemID_testInteractionOne"></span><span class="learnosity-response question-testItemID_testInteractionTwo"></span></p>', $item->get_content());
        $this->assertNull($item->get_workflow());
        $this->assertNull($item->get_metadata());
        $this->assertEquals('testItemTitle', $item->get_description());
        $this->assertCount(2, $item->get_questionReferences());
        $this->assertEquals('testItemID_testInteractionOne', $item->get_questionReferences()[0]);
        $this->assertEquals('testItemID_testInteractionTwo', $item->get_questionReferences()[1]);

        $this->assertCount(2, $data[1]);
        $questions = $data[1];
        foreach ($questions as $question) {
            $this->assertInstanceOf('Learnosity\Entities\Question', $question);
            $this->assertNotNull($question->get_data());
        }

        $this->assertEquals('testItemID_testInteractionOne', $questions['testItemID_testInteractionOne']->get_reference());
        $this->assertEquals('testQuestionType', $questions['testItemID_testInteractionOne']->get_type());
        $this->assertEquals('testItemID_testInteractionTwo', $questions['testItemID_testInteractionTwo']->get_reference());
        $this->assertEquals('testQuestionType', $questions['testItemID_testInteractionTwo']->get_type());
        $this->assertEmpty($data[2], 'Should have no exception');
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
