<?php

namespace Learnosity\Tests\Unit\Processors\QtiV2\In;


use Learnosity\Processors\QtiV2\In\ItemBuilders\MergedItemBuilder;
use Learnosity\Services\LogService;
use Learnosity\Tests\Unit\Processors\QtiV2\In\Fixtures\ChoiceInteractionBuilder;
use Learnosity\Tests\Unit\Processors\QtiV2\In\Fixtures\ItemBodyBuilder;
use qtism\data\content\interactions\TextEntryInteraction;
use qtism\data\QtiComponentCollection;

class MergedItemBuilderTest extends \PHPUnit_Framework_TestCase
{
    /* @var MergedItemBuilder $mergedItemBuilder */
    private $mergedItemBuilder;

    public function setup()
    {
        $this->mergedItemBuilder = new MergedItemBuilder();
    }

    public function testMapWithNonMergeableInteractionTypes()
    {
        $componentCollection = $this->buildComponentCollectionWithNonMergeableInteractionTypes();
        $result = $this->mergedItemBuilder->map(
            'testAssessmentItemIdentifier',
            ItemBodyBuilder::buildItemBody($componentCollection),
            $componentCollection
        );
        $this->assertFalse($result);
    }

    public function testMapWithMergableInteractionType()
    {
        $componentCollection = $this->buildComponentCollectionWithMergableInteractionTypes();
        $result = $this->mergedItemBuilder->map(
            'testAssessmentItemIdentifier',
            ItemBodyBuilder::buildItemBody($componentCollection),
            $componentCollection
        );
        $this->assertTrue($result);
        $this->assertCount(1, LogService::read());
        $questions = $this->mergedItemBuilder->getQuestions();
        $this->assertCount(1, $questions);
        $this->assertInstanceOf('Learnosity\Entities\Question', $questions[0]);
        $q = $questions[0];
        $this->assertEquals('testAssessmentItemIdentifier_testTextEntryInteractionOne_testTextEntryInteractionTwo', $q->get_reference());
        $this->assertEquals('clozetext', $q->get_type());
        $qData = $q->get_data();
        $this->assertEquals('clozetext', $qData->get_type());
        $this->assertTrue(substr_count($qData->get_template(), '{{response}}') === 2);
    }

    protected function buildComponentCollectionWithMergableInteractionTypes()
    {
        $collection = new QtiComponentCollection();
        $textEntryInteractionOne = new TextEntryInteraction('testTextEntryInteractionOne');
        $textEntryInteractionTwo = new TextEntryInteraction('testTextEntryInteractionTwo');
        $collection->attach($textEntryInteractionOne);
        $collection->attach($textEntryInteractionTwo);
        return $collection;
    }

    protected function buildComponentCollectionWithNonMergeableInteractionTypes()
    {
        $collection = new QtiComponentCollection();
        $choiceInteraction = ChoiceInteractionBuilder::buildSimple('testChoiceInteractionIdentifier', [
            'ChoiceOne' => 'A',
            'ChoiceTwo' => 'B'
        ]);
        $collection->attach($choiceInteraction);
        return $collection;
    }
}

