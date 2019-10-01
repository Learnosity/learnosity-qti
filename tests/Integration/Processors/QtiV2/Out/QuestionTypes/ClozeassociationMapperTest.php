<?php

namespace LearnosityQti\Tests\Integration\Processors\QtiV2\Out\QuestionTypes;

use LearnosityQti\Utils\QtiMarshallerUtil;
use qtism\common\datatypes\QtiDirectedPair;
use qtism\common\enums\BaseType;
use qtism\common\enums\Cardinality;
use qtism\data\AssessmentItem;
use qtism\data\content\interactions\GapMatchInteraction;
use qtism\data\content\interactions\GapText;
use qtism\data\content\ModalFeedbackCollection;
use qtism\data\content\ModalFeedback;
use qtism\data\rules\ResponseElse;
use qtism\data\rules\ResponseIf;
use qtism\data\state\ResponseDeclaration;
use qtism\data\state\Value;
use ReflectionProperty;

class ClozeassociationMapperTest extends AbstractQuestionTypeTest
{
    public function testSimpleCommonCase()
    {
        /** @var AssessmentItem $assessmentItem */
        $question = json_decode($this->getFixtureFileContents('learnosityjsons/data_clozeassociation.json'), true);
        $assessmentItemArray = $this->convertToAssessmentItem($question);
        /** @var GapMatchInteraction $interaction */
        foreach ($assessmentItemArray as $assessmentItem) {
            $interaction = $assessmentItem->getComponentsByClassName('gapMatchInteraction', true)->getArrayCopy()[0];
            $this->assertTrue($interaction instanceof GapMatchInteraction);
            $this->assertTrue($interaction->mustShuffle());

            // And its prompt is mapped correctly
            $promptString = QtiMarshallerUtil::marshallCollection($interaction->getPrompt()->getComponents());
            $this->assertEquals('<p>[This is the stem.]</p>', trim($promptString));

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
            $responseProcessing =  $assessmentItem->getResponseProcessing();
            
            /** @var ResponseDeclaration $responseDeclaration */
            $responseDeclaration = $assessmentItem->getResponseDeclarations()->getArrayCopy()[0];
            $this->assertEquals(Cardinality::MULTIPLE, $responseDeclaration->getCardinality());
            $this->assertEquals(BaseType::DIRECTED_PAIR, $responseDeclaration->getBaseType());

            /** @var Value[] $values */
            $values = $responseDeclaration->getCorrectResponse()->getValues()->getArrayCopy(true);
            $this->assertDirectPair($values[0]->getValue(), 'CHOICE_0', 'GAP_0');
            $this->assertDirectPair($values[1]->getValue(), 'CHOICE_1', 'GAP_1');

            // And, we don't have mapping because we simply won't
            $this->assertEquals(null, $responseDeclaration->getMapping());
        }
    }

    public function testWithValidationAndDistractorRationale()
    {
        $data = json_decode($this->getFixtureFileContents('learnosityjsons/data_clozeassociation.json'), true);
        $assessmentItemArray = $this->convertToAssessmentItem($data);
        foreach($assessmentItemArray as $assessmentItem){

            $this->assertEquals(1, $assessmentItem->getResponseDeclarations()->count());
            $this->assertNotNull($assessmentItem->getResponseProcessing());
            
            $this->assertCount(2,$assessmentItem->getResponseProcessing()->getComponents());
            $this->assertCount(2, $assessmentItem->getResponseProcessing()->getComponentsByClassName('responseIf', true));
            $responseIf = $assessmentItem->getResponseProcessing()->getComponentsByClassName('responseIf', true)->getArrayCopy()[0];
            $this->assertTrue($responseIf instanceof ResponseIf);
            $promptIfString = QtiMarshallerUtil::marshallCollection($responseIf->getComponents());
            $this->assertEquals('<isNull><variable identifier="RESPONSE"/></isNull><setOutcomeValue identifier="SCORE"><baseValue baseType="float">0</baseValue></setOutcomeValue>', $promptIfString);
            
            $this->assertCount(2, $assessmentItem->getResponseProcessing()->getComponentsByClassName('responseElse', true));
            $responseElse = $assessmentItem->getResponseProcessing()->getComponentsByClassName('responseElse', true)->getArrayCopy()[0];
            $this->assertTrue($responseElse instanceof ResponseElse);
            $promptElseString = QtiMarshallerUtil::marshallCollection($responseElse->getComponents());
            $this->assertEquals('<responseCondition><responseIf><match><variable identifier="RESPONSE"/><correct identifier="RESPONSE"/></match><setOutcomeValue identifier="SCORE"><baseValue baseType="float">1</baseValue></setOutcomeValue></responseIf><responseElse><setOutcomeValue identifier="SCORE"><baseValue baseType="float">0</baseValue></setOutcomeValue></responseElse></responseCondition>', $promptElseString);
            
            $modalFeedBackCollections = $assessmentItem->getModalFeedbacks();
            $this->assertTrue($modalFeedBackCollections instanceof ModalFeedbackCollection);
            foreach($modalFeedBackCollections as $modalFeedback) {
                $this->assertTrue($modalFeedback instanceof ModalFeedback);
                $promptFeedbackString = $modalFeedback->getComponents()[0]->getContent();
                $this->assertEquals('This is overall feedback', $promptFeedbackString);
            }
        }
    }

    private function assertDirectPair(QtiDirectedPair $pair, $expectedFirstValue, $expectedSecondValue)
    {
        $this->assertEquals($expectedFirstValue, $pair->getFirst());
        $this->assertEquals($expectedSecondValue, $pair->getSecond());
    }
}
