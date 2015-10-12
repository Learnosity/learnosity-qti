<?php

namespace Learnosity\Tests\Integration\Processors\QtiV2\Out\QuestionTypes;

use Learnosity\Processors\QtiV2\Out\Constants;
use Learnosity\Utils\QtiMarshallerUtil;
use qtism\common\datatypes\DirectedPair;
use qtism\common\enums\BaseType;
use qtism\common\enums\Cardinality;
use qtism\data\AssessmentItem;
use qtism\data\content\interactions\GapMatchInteraction;
use qtism\data\content\interactions\GapText;
use qtism\data\state\ResponseDeclaration;
use qtism\data\state\Value;

class ClozeassociationMapperTest extends AbstractQuestionTypeTest
{
    public function testSimpleCommonCase()
    {
        /** @var AssessmentItem $assessmentItem */
        $question = json_decode($this->getFixtureFileContents('learnosityjsons/data_clozeassociation.json'), true);
        $assessmentItem = $this->convertToAssessmentItem($question);

        /** @var GapMatchInteraction $interaction */
        $interaction = $assessmentItem->getComponentsByClassName('gapMatchInteraction', true)->getArrayCopy()[0];
        $this->assertTrue($interaction instanceof GapMatchInteraction);
        $this->assertFalse($interaction->mustShuffle());

        // And its prompt is mapped correctly
        $promptString = QtiMarshallerUtil::marshallCollection($interaction->getPrompt()->getComponents());
        $this->assertEquals('<p>[This is the stem.]</p>', $promptString);

        // And its choices mapped well
        /** @var GapText[] $gapChoices */
        $gapChoices = $interaction->getGapChoices()->getArrayCopy();
        $this->assertEquals(2, count($gapChoices));
        $this->assertEquals('CHOICE_0', $gapChoices[0]->getIdentifier());
        $this->assertEquals('Choice A', QtiMarshallerUtil::marshallCollection($gapChoices[0]->getContent()));
        $this->assertEquals('CHOICE_1', $gapChoices[1]->getIdentifier());
        $this->assertEquals('Choice B', QtiMarshallerUtil::marshallCollection($gapChoices[1]->getContent()));

        // And its gaps
        $content = QtiMarshallerUtil::marshallCollection($interaction->getContent());
        $expectedContent = '<p>Risus <gap identifier="GAP_0"/>, et tincidunt turpis facilisis. Curabitur eu nulla justo. Curabitur vulputate ut nisl et bibendum. Nunc diam enim, porta sed eros vitae. <gap identifier="GAP_1"/> dignissim, et tincidunt turpis facilisis. Curabitur eu nulla justo. Curabitur vulputate ut nisl et bibendum.</p>';
        $this->assertEquals($expectedContent, $content);

        // And its response processing and response declaration
        $this->assertEquals(Constants::RESPONSE_PROCESSING_TEMPLATE_MATCH_CORRECT, $assessmentItem->getResponseProcessing()->getTemplate());
        /** @var ResponseDeclaration $responseDeclaration */
        $responseDeclaration = $assessmentItem->getResponseDeclarations()->getArrayCopy()[0];
        $this->assertEquals(Cardinality::MULTIPLE, $responseDeclaration->getCardinality());
        $this->assertEquals(BaseType::DIRECTED_PAIR, $responseDeclaration->getBaseType());

        /** @var Value[] $values */
        $values = $responseDeclaration->getCorrectResponse()->getValues()->getArrayCopy(true);
        $this->assertDirectPair($values[0]->getValue(), 'GAP_0', 'CHOICE_0');
        $this->assertDirectPair($values[1]->getValue(), 'GAP_1', 'CHOICE_1');

        // And, we don't have mapping because we simply won't
        $this->assertEquals(null, $responseDeclaration->getMapping());
    }

    private function assertDirectPair(DirectedPair $pair, $expectedFirstValue, $expectedSecondValue)
    {
        $this->assertEquals($expectedFirstValue, $pair->getFirst());
        $this->assertEquals($expectedSecondValue, $pair->getSecond());
    }
}
