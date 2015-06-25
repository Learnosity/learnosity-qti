<?php

namespace Learnosity\Tests\Unit\Mappers\QtiV2\Import;


use Learnosity\Mappers\QtiV2\Import\RegularItemBuilder;
use Learnosity\Tests\Unit\Mappers\QtiV2\Import\Fixtures\ChoiceInteractionBuilder;
use Learnosity\Tests\Unit\Mappers\QtiV2\Import\Fixtures\ItemBodyBuilder;
use Learnosity\Tests\Unit\Mappers\QtiV2\Import\Fixtures\ResponseDeclarationBuilder;
use qtism\data\content\interactions\TextEntryInteraction;
use qtism\data\QtiComponentCollection;

class RegularItemBuilderTest extends \PHPUnit_Framework_TestCase
{

    /* @var RegularItemBuilder $regularItemBuilder */
    private $regularItemBuilder;

    public function setup()
    {
        $this->regularItemBuilder = new RegularItemBuilder();
    }

    public function testMapWithRegularInteractionType()
    {

        $componentCollection = $this->buildComponentCollectionWithRegularInteractionTypes();
        $result = $this->regularItemBuilder->map(
            'testAssessmentItemIdentifier',
            ItemBodyBuilder::buildItemBody($componentCollection),
            $componentCollection,
            $this->buildResponseDeclaration()
        );
        $this->assertTrue($result);
        $this->assertCount(2, $this->regularItemBuilder->getExceptions());
        $questions = $this->regularItemBuilder->getQuestions();
        $this->assertCount(2, $questions);
        $this->assertEquals(
            '<p>The Matrix movie is starring <span class="learnosity-response question-testAssessmentItemIdentifier_testTextEntryInteractionIdentifier"></span></p><span class="learnosity-response question-testAssessmentItemIdentifier_testChoiceInteractionIdentifier"></span>',
            $this->regularItemBuilder->getItem()->get_content());

        $this->assertTrue(isset($questions['testAssessmentItemIdentifier_testChoiceInteractionIdentifier']));
        $this->assertTrue(isset($questions['testAssessmentItemIdentifier_testTextEntryInteractionIdentifier']));

        $q1 = $questions['testAssessmentItemIdentifier_testChoiceInteractionIdentifier'];
        $this->assertEquals('testAssessmentItemIdentifier_testChoiceInteractionIdentifier', $q1->get_reference());
        $this->assertEquals('mcq', $q1->get_type());
        $this->assertInstanceOf('Learnosity\Entities\QuestionTypes\mcq', $q1->get_data());

        $q2 = $questions['testAssessmentItemIdentifier_testTextEntryInteractionIdentifier'];
        $this->assertEquals('testAssessmentItemIdentifier_testTextEntryInteractionIdentifier', $q2->get_reference());
        $this->assertEquals('clozetext', $q2->get_type());
        $this->assertInstanceOf('Learnosity\Entities\QuestionTypes\clozetext', $q2->get_data());
    }


    protected function buildComponentCollectionWithRegularInteractionTypes()
    {
        $collection = new QtiComponentCollection();
        $choiceInteraction = ChoiceInteractionBuilder::buildSimple('testChoiceInteractionIdentifier', [
            'Choice 1' => 'A',
            'Choice 2' => 'B'
        ]);
        $textEntryInteraction = new TextEntryInteraction('testTextEntryInteractionIdentifier');
        $collection->attach($choiceInteraction);
        $collection->attach($textEntryInteraction);
        return $collection;
    }

    protected function buildResponseDeclaration()
    {
        $responseDeclartation =
            ResponseDeclarationBuilder::buildWithCorrectResponse('testChoiceInteractionIdentifier', ['1', '2']);
        $collection = new QtiComponentCollection();
        $collection->attach($responseDeclartation);
        return $collection;
    }



}
