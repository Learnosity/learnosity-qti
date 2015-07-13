<?php
namespace Learnosity\Tests\Unit\Mappers\QtiV2\In;

use Learnosity\Entities\Item\item;
use Learnosity\Entities\Question;
use Learnosity\Entities\QuestionTypes\clozetext;
use Learnosity\Processors\QtiV2\In\ItemMapper;
use Learnosity\Processors\QtiV2\In\ResponseProcessingTemplate;
use Learnosity\Tests\Unit\Mappers\QtiV2\In\Fixtures\InlineChoiceInteractionBuilder;
use PHPUnit_Framework_MockObject_MockObject;
use qtism\data\AssessmentItem;
use qtism\data\content\BlockCollection;
use qtism\data\content\InlineCollection;
use qtism\data\content\interactions\TextEntryInteraction;
use qtism\data\content\ItemBody;
use qtism\data\content\Math;
use qtism\data\content\TextRun;
use qtism\data\content\xhtml\text\P;
use qtism\data\processing\ResponseProcessing;

class ItemMapperTest extends \PHPUnit_Framework_TestCase
{
    /* @var ItemMapper $itemMapper */
    private $itemMapper;
    /* @var PHPUnit_Framework_MockObject_MockObject $itemMapper */
    private $itemBuilderFactoryMock;

    public function setup()
    {
        $this->itemBuilderFactoryMock = $this->getMockBuilder('Learnosity\Processors\QtiV2\In\ItemBuilderFactory')
            ->disableOriginalConstructor()->getMock();
        $this->itemMapper = new ItemMapper($this->itemBuilderFactoryMock);
    }

    public function testParseWithoutInteraction()
    {
        $this->setExpectedException('Learnosity\Exceptions\MappingException');

        $assessmentItem = $this->buildAssessmentItemWithoutInteraction();
        $data = $this->itemMapper->parseWithAssessmentItemComponent($assessmentItem);
    }

    private function getMockItemBuilderWith(item $item, array $questions)
    {
        $mockItemBuilder = $this->getMockBuilder('Learnosity\Processors\QtiV2\In\ItemBuilders\AbstractItemBuilder')
            ->disableOriginalConstructor()->getMock();
        $mockItemBuilder->expects($this->once())->method('getItem')->willReturn($item);
        $mockItemBuilder->expects($this->once())->method('getQuestions')->willReturn($questions);
        return $mockItemBuilder;
    }

    public function testParseWithSameInteractionType()
    {
        $assessmentItem = $this->buildAssessmentItemWithSameInteractionTypes();

        $testItem = $this->buildItem();
        $testQuestion = $this->buildQuestion();
        $this->itemBuilderFactoryMock->expects($this->once())
            ->method('getItemBuilder')
            ->willReturn($this->getMockItemBuilderWith($testItem, [$testQuestion]));

        list($item, $questions, $exceptions) = $this->itemMapper->parseWithAssessmentItemComponent($assessmentItem);

        $this->assertEquals($testItem, $item);
        $this->assertCount(1, $questions);
        $this->assertEquals([$testQuestion], $questions);
        $this->assertEmpty($exceptions, 'Should have no exception');
    }

    public function testParseWithMultipleInteractionType()
    {
        $assessmentItem = $this->buildAssessmentItemWithDifferentInteractionTypes();

        $testItem = $this->buildItem();
        $testQuestion = $this->buildQuestion();
        $this->itemBuilderFactoryMock->expects($this->once())
            ->method('getItemBuilder')
            ->willReturn($this->getMockItemBuilderWith($testItem, [$testQuestion]));

        list($item, $questions, $exceptions) = $this->itemMapper->parseWithAssessmentItemComponent($assessmentItem);

        $this->assertEquals($testItem, $item);
        $this->assertCount(1, $questions);
        $this->assertEquals([$testQuestion], $questions);
        $this->assertEmpty($exceptions, 'Should have no exception');
    }

    public function testParseWithMathTags()
    {
        $assessmentItem = $this->buildAssessmentItemWithDifferentInteractionTypes();
        $itemBody = $assessmentItem->getItemBody();
        $itemBodyComponents = $itemBody->getComponents();
        $math = new Math('<m:math xmlns:m="http://www.w3.org/1998/Math/MathML">
                    <m:mrow>
                        <m:mi>E</m:mi>
                        <m:mo>=</m:mo>
                        <m:mi>m</m:mi>
                        <m:msup>
                            <m:mi>c</m:mi>
                            <m:mn>2</m:mn>
                        </m:msup>
                    </m:mrow>
                </m:math>');
        $itemBodyComponents->attach($math);
        $itemBody->setContent($itemBodyComponents);
        $assessmentItem->setItemBody($itemBody);

        $testItem = $this->buildItem();
        $testQuestion = $this->buildQuestion();
        $this->itemBuilderFactoryMock->expects($this->once())
            ->method('getItemBuilder')
            ->willReturn($this->getMockItemBuilderWith($testItem, [$testQuestion]));

        list($item, $questions, $exceptions) = $this->itemMapper->parseWithAssessmentItemComponent($assessmentItem);

        $this->assertCount(1, $questions);
        $this->assertTrue($questions[0]->get_data()->get_is_math());
    }

    protected function buildItem()
    {
        return new item('testItemID', ['testQuestionReference'], 'testContent');
    }

    protected function buildQuestion()
    {
        return new Question('clozetext', 'testQuestionID', new clozetext('testQuestionType', ''));
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
            'choice' => 'The Choice Label'
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
